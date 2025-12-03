<?php

namespace App\Http\Controllers\OptiHr;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\AbsenceRequestCreated;
use App\Mail\AbsenceRequestUpdated;
use App\Models\OptiHr\Absence;
use App\Models\OptiHr\AbsenceType;
use App\Models\OptiHr\AnnualDecision;
use App\Models\OptiHr\Duty;
use App\Models\User;
use App\Services\AbsencePdfService;
use App\Services\ActivityLogService;
use App\Traits\SendsEmails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AbsenceController extends Controller
{
    use SendsEmails;

    public function __construct()
    {
        parent::__construct(app(ActivityLogService::class)); // Injection automatique

        $this->middleware(['permission:voir-une-absence|écrire-une-absence|créer-une-absence|configurer-une-absence|voir-un-tout'], ['only' => ['index']]);
        $this->middleware(['permission:créer-une-absence|créer-un-tout'], ['only' => ['store', 'cancel', 'create']]);
        $this->middleware(['permission:écrire-une-absence|écrire-un-tout'], ['only' => ['approve', 'reject', 'comment', 'updateDeductibility']]);
    }

    /**
     * Télécharger le PDF d'une absence
     *
     * @param  int  $absenceId  L'identifiant de l'absence
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function download($absenceId)
    {
        try {
            $absence = Absence::findOrFail($absenceId);
            $decision = AnnualDecision::where('state', 'current')->first();

            if (! $decision) {
                $this->activityLogger->log(
                    'error',
                    "Échec du téléchargement du PDF d'absence #{$absence->id}: Aucune décision annuelle active",
                    $absence
                );

                return response()->json([
                    'error' => 'Aucune décision annuelle active trouvée',
                    'message' => 'Impossible de générer le PDF sans décision annuelle',
                ], 404);
            }

            $absencePdf = new AbsencePdfService;

            $this->activityLogger->log(
                'download',
                "Téléchargement du PDF d'absence #{$absence->id}",
                $absence
            );

            return $absencePdf->generate($absence, $decision);
        } catch (\Exception $e) {
            // Log l'erreur
            $this->activityLogger->log(
                'error',
                "Erreur lors du téléchargement du PDF d'absence #{$absenceId}: ".$e->getMessage(),
                isset($absence) ? $absence : null
            );

            return response()->json([
                'error' => 'Erreur lors de la génération du PDF',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Afficher la piece justificative d'une absence
     *
     * @param int $absenceId L'identifiant de l'absence
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
     */
    public function showProof($absenceId)
    {
        $absence = Absence::findOrFail($absenceId);

        if (!$absence->proof) {
            abort(404, 'Aucun justificatif disponible');
        }

        $filePath = storage_path('app/public/' . $absence->proof);

        if (!file_exists($filePath)) {
            abort(404, 'Fichier introuvable');
        }

        $mimeType = mime_content_type($filePath);
        $fileName = basename($absence->proof);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Mapping des stages virtuels vers les stages réels
     */
    private const STAGE_MAPPING = [
        'TO_PROCESS' => ['PENDING', 'IN_PROGRESS'],
        'HISTORY' => ['APPROVED', 'REJECTED'],
        'CANCELLED' => ['CANCELLED'],
    ];

    /**
     * Afficher la liste des absences filtrée par étape
     *
     * @param  string  $stage
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request, $stage = 'TO_PROCESS')
    {
        // Liste des stages valides (incluant les nouveaux stages virtuels)
        $validStages = ['PENDING', 'APPROVED', 'REJECTED', 'CANCELLED', 'IN_PROGRESS', 'COMPLETED', 'TO_PROCESS', 'HISTORY', 'ALL'];

        // Vérification de la validité du stage
        if (! in_array($stage, $validStages)) {
            $this->activityLogger->log(
                'error',
                "Tentative d'accès avec un stage invalide: {$stage}"
            );

            return redirect()->route('absences.requests', 'TO_PROCESS')->with('error', 'Stage invalide');
        }

        // Récupérer les filtres de recherche
        $type = $request->input('type');
        $search = $request->input('search');
        $subFilter = $request->input('filter', 'all'); // Sous-filtre : all, mine, to_validate

        // Récupérer les types d'absences (éviter de faire la requête à chaque appel)
        $absence_types = AbsenceType::all();

        // Construire la requête principale avec les relations nécessaires
        $query = $this->buildAbsenceQuery($search, $type, $stage);

        // Appliquer le sous-filtre pour le tab "À traiter"
        if (in_array($stage, ['TO_PROCESS', 'PENDING', 'IN_PROGRESS'])) {
            $query = $this->applySubFilter($query, $subFilter);
        }

        // Pagination adaptative selon le type de stage
        $absences = $this->getPaginatedResults($query, $stage);

        // Compter les demandes en attente pour le badge du tab "À traiter"
        $pendingCount = $this->countPendingAbsences();

        // Obtenir les compteurs des sous-filtres
        $subFilterCounts = $this->getSubFilterCounts();

        $this->activityLogger->log(
            'view',
            "Consultation de la liste des absences - Stage: {$stage}".
            ($type ? ", Type: {$type}" : '').
            ($search ? ", Recherche: {$search}" : '').
            ($subFilter !== 'all' ? ", Filtre: {$subFilter}" : '')
        );

        // Retourner la vue avec les données nécessaires
        return view('modules.opti-hr.pages.attendances.absences.index', compact(
            'absences',
            'stage',
            'absence_types',
            'pendingCount',
            'subFilter',
            'subFilterCounts'
        ));
    }

    /**
     * Compter les demandes en attente de traitement
     */
    private function countPendingAbsences(): int
    {
        return Absence::whereIn('stage', ['PENDING', 'IN_PROGRESS'])->count();
    }

    /**
     * Obtenir les compteurs pour les sous-filtres du tab "À traiter"
     */
    private function getSubFilterCounts(): array
    {
        $user = auth()->user();
        $employeeId = $user->employee_id;

        // Récupérer le job_id de l'utilisateur courant
        $currentDuty = $user->employee?->duties?->firstWhere('evolution', 'ON_GOING');
        $currentJobId = $currentDuty?->job_id;

        // Toutes les demandes à traiter
        $allCount = Absence::whereIn('stage', ['PENDING', 'IN_PROGRESS'])->count();

        // Mes demandes (demandes créées par l'utilisateur courant)
        $myCount = Absence::whereIn('stage', ['PENDING', 'IN_PROGRESS'])
            ->whereHas('duty', function ($q) use ($employeeId) {
                $q->where('employee_id', $employeeId);
            })
            ->count();

        // À valider (demandes que l'utilisateur peut valider)
        $toValidateQuery = Absence::whereIn('stage', ['PENDING', 'IN_PROGRESS'])
            ->whereHas('duty', function ($q) use ($employeeId) {
                $q->where('employee_id', '!=', $employeeId);
            });

        // Filtrer selon le rôle
        if ($user->hasRole('DG')) {
            $toValidateQuery->whereIn('level', ['TWO', 'THREE']);
        } elseif ($user->hasRole('GRH') || $user->hasRole('DSAF')) {
            $toValidateQuery->whereIn('level', ['ONE', 'TWO', 'THREE']);
        } elseif ($currentJobId) {
            // Chef direct (N+1)
            $toValidateQuery->whereHas('duty.job', function ($q) use ($currentJobId) {
                $q->where('n_plus_one_job_id', $currentJobId);
            });
        }

        $toValidateCount = $toValidateQuery->count();

        return [
            'all' => $allCount,
            'mine' => $myCount,
            'to_validate' => $toValidateCount,
        ];
    }

    /**
     * Appliquer le sous-filtre à la requête
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applySubFilter($query, string $subFilter)
    {
        $user = auth()->user();
        $employeeId = $user->employee_id;
        $currentDuty = $user->employee?->duties?->firstWhere('evolution', 'ON_GOING');
        $currentJobId = $currentDuty?->job_id;

        switch ($subFilter) {
            case 'mine':
                // Mes demandes uniquement
                $query->whereHas('duty', function ($q) use ($employeeId) {
                    $q->where('employee_id', $employeeId);
                });
                break;

            case 'to_validate':
                // Demandes à valider (exclure mes propres demandes)
                $query->whereHas('duty', function ($q) use ($employeeId) {
                    $q->where('employee_id', '!=', $employeeId);
                });

                // Filtrer selon le rôle
                if ($user->hasRole('DG')) {
                    $query->whereIn('level', ['TWO', 'THREE']);
                } elseif ($user->hasRole('GRH') || $user->hasRole('DSAF')) {
                    $query->whereIn('level', ['ONE', 'TWO', 'THREE']);
                } elseif ($currentJobId) {
                    // Chef direct (N+1)
                    $query->whereHas('duty.job', function ($q) use ($currentJobId) {
                        $q->where('n_plus_one_job_id', $currentJobId);
                    });
                }
                break;

            case 'all':
            default:
                // Pas de filtre supplémentaire
                break;
        }

        return $query;
    }

    /**
     * Construire la requête d'absences avec filtres
     *
     * @param  string|null  $search
     * @param  string|null  $type
     * @param  string  $stage
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildAbsenceQuery($search, $type, $stage)
    {
        // Construire la requête principale avec les relations nécessaires
        $query = Absence::with(['absence_type', 'duty', 'duty.employee', 'duty.job', 'duty.job.n_plus_one_job']);

        // Appliquer le filtre de recherche (groupe de conditions OR)
        $query->when($search, function ($q) use ($search) {
            $q->whereHas('duty.employee', function ($query) use ($search) {
                $query->where('first_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$search.'%');
            });
        });

        // Trier par date de demande (les plus récentes en premier)
        $query->orderByDesc('date_of_application');

        // Filtrer par type d'absence, si précisé
        $query->when($type, function ($q) use ($type) {
            $q->where('absence_type_id', $type);
        });

        // Filtrer par stage en utilisant le mapping si nécessaire
        if ($stage !== 'ALL') {
            if (isset(self::STAGE_MAPPING[$stage])) {
                // Stage virtuel - utiliser le mapping
                $query->whereIn('stage', self::STAGE_MAPPING[$stage]);
            } else {
                // Stage réel
                $query->where('stage', $stage);
            }
        }

        return $query;
    }

    /**
     * Obtenir les résultats paginés en fonction du stage
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $stage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    private function getPaginatedResults($query, $stage)
    {
        // Appliquer la pagination pour les stages "À traiter" (accordion)
        // Les autres stages utilisent DataTable qui gère sa propre pagination côté client
        $paginatedStages = ['PENDING', 'IN_PROGRESS', 'TO_PROCESS'];

        return in_array($stage, $paginatedStages)
            ? $query->paginate(15) // Pagination pour les demandes à traiter
            : $query->get();
    }

    /**
     * Afficher le formulaire de création d'absence
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $absenceTypes = AbsenceType::all();

        return view('modules.opti-hr.pages.attendances.absences.create', compact('absenceTypes'));
    }

    /**
     * Calculer le nombre total de jours entre deux dates (y compris week-ends et jours fériés)
     *
     * @param  string  $startDate  Date de début au format Y-m-d
     * @param  string  $endDate  Date de fin au format Y-m-d
     * @return int Nombre total de jours
     */
    private function calculateWorkingDays($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // diffInDays() retourne la différence en jours, +1 pour inclure le jour de fin
        return $endDate->diffInDays($startDate) + 1;
    }

    /**
     * Enregistrer une nouvelle demande d'absence
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation des champs de la requête avec règles renforcées
        $validatedData = $request->validate([
            'absence_type' => 'required|exists:absence_types,id',
            'address' => 'required|string|min:5|max:255',
            'start_date' => 'required|date|after_or_equal:today|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reasons' => 'nullable|string|max:1000',
            'is_deductible' => 'sometimes|boolean',
            'proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5 Mo
        ], [
            'absence_type.required' => 'Le type d\'absence est obligatoire.',
            'absence_type.exists' => 'Le type d\'absence sélectionné n\'existe pas.',
            'address.required' => 'L\'adresse pendant l\'absence est obligatoire.',
            'address.min' => 'L\'adresse doit contenir au moins 5 caractères.',
            'address.max' => 'L\'adresse ne peut pas dépasser 255 caractères.',
            'start_date.required' => 'La date de début est obligatoire.',
            'start_date.date' => 'La date de début n\'est pas valide.',
            'start_date.after_or_equal' => 'La date de début doit être à partir d\'aujourd\'hui.',
            'start_date.before_or_equal' => 'La date de début doit être antérieure ou égale à la date de fin.',
            'end_date.required' => 'La date de fin est obligatoire.',
            'end_date.date' => 'La date de fin n\'est pas valide.',
            'end_date.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'reasons.max' => 'Le motif ne peut pas dépasser 1000 caractères.',
            'proof.file' => 'Le justificatif doit être un fichier.',
            'proof.mimes' => 'Le justificatif doit être au format PDF, JPG ou PNG.',
            'proof.max' => 'Le justificatif ne peut pas dépasser 5 Mo.',
        ]);

        // Calcul du nombre de jours d'absence
        $workingDays = $this->calculateWorkingDays($validatedData['start_date'], $validatedData['end_date']);

        // Récupération de l'employé actuel et de sa mission en cours
        $currentUser = User::with('employee')->findOrFail(auth()->id());
        $currentEmployee = $currentUser->employee;

        $currentEmployeeDuty = Duty::where('evolution', 'ON_GOING')
            ->where('employee_id', $currentEmployee->id)
            ->firstOrFail();

        // Vérifier le chevauchement avec des absences existantes
        $startDate = $validatedData['start_date'];
        $endDate = $validatedData['end_date'];

        $overlap = Absence::where('duty_id', $currentEmployeeDuty->id)
            ->whereNotIn('stage', ['REJECTED', 'CANCELLED'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();

        if ($overlap) {
            $this->activityLogger->log(
                'warning',
                "Tentative de création d'absence avec chevauchement de dates",
                null,
                ['employee_id' => $currentEmployee->id, 'start_date' => $startDate, 'end_date' => $endDate]
            );

            return response()->json([
                'ok' => false,
                'message' => 'Vous avez déjà une demande d\'absence sur cette période.',
            ], 422);
        }

        $absence_type_id = $request->input('absence_type');

        // Obtenir le type d'absence pour le log et la déductibilité
        $absenceType = AbsenceType::find($absence_type_id);

        // Définir la déductibilité selon les critères spécifiés
        // Si explicitement fourni dans la requête, utiliser cette valeur
        // Sinon, utiliser la valeur par défaut du type d'absence
        $isDeductible = $request->has('is_deductible')
            ? $request->boolean('is_deductible')
            : $absenceType->is_deductible;

        // Vérification du solde de congés si l'absence est déductible
        if ($isDeductible && $workingDays > $currentEmployeeDuty->absence_balance) {
            // On pourrait choisir de bloquer la demande ici ou juste avertir
            // Pour l'instant, on continue mais on log l'avertissement
            $this->activityLogger->log(
                'warning',
                "Création d'une demande d'absence de {$workingDays} jours avec un solde disponible de {$currentEmployeeDuty->absence_balance} jours",
                null,
                ['employee_id' => $currentEmployee->id]
            );
        }

        // Gestion du fichier justificatif
        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofFile = $request->file('proof');
            $proofPath = $proofFile->store('absences/proofs', 'public');
        }

        // Enregistrement de la demande d'absence
        $absence = Absence::create([
            'duty_id' => $currentEmployeeDuty->id,
            'absence_type_id' => $absence_type_id,
            'address' => $validatedData['address'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'reasons' => $validatedData['reasons'],
            'requested_days' => $workingDays,
            'is_deductible' => $isDeductible,
            'date_of_application' => Carbon::now(),
            'status' => 'PENDING',
            'stage' => 'PENDING',
            'level' => 'ZERO',
            'proof' => $proofPath,
        ]);

        $this->activityLogger->log(
            'created',
            "Création d'une demande d'absence de type {$absenceType->label} ".
            ($isDeductible ? 'déductible' : 'non déductible'),
            $absence
        );

        // Pour la notification par email
        $receiver = $absence->duty->job->n_plus_one_job ?
            $absence->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->users->first()
            : User::role('GRH')->first();

        $this->handleNotifications($absence, $receiver, false);

        return response()->json([
            'message' => "Demande d'absence {$absenceType->label} créée avec succès.",
            'ok' => true,
            'redirect' => route('absences.requests', 'PENDING'),
        ]);
    }

    /**
     * Calcule le nombre de jours entre deux dates via une requête AJAX
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateDays(Request $request)
    {
        // Validation des dates
        $validatedData = $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Calcul du nombre de jours d'absence
        $workingDays = $this->calculateWorkingDays($validatedData['start_date'], $validatedData['end_date']);

        return response()->json(['working_days' => $workingDays]);
    }

    /**
     * Met à jour le stage et le level d'une absence
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStageAndLevel(Request $request, $id)
    {
        // Valider les entrées
        $validatedData = $request->validate([
            'stage' => 'required|in:PENDING,APPROVED,REJECTED,CANCELLED,IN_PROGRESS,COMPLETED',
            'level' => 'required|in:ZERO,ONE,TWO,THREE',
        ]);

        // Rechercher l'absence par ID
        $absence = Absence::findOrFail($id);

        // Sauvegarder les valeurs précédentes pour le log
        $oldStage = $absence->stage;
        $oldLevel = $absence->level;

        // Mettre à jour les champs stage et level
        $absence->stage = $validatedData['stage'];
        $absence->level = $validatedData['level'];

        // Sauvegarder les modifications
        $absence->save();

        $this->activityLogger->log(
            'updated',
            "Mise à jour du statut de l'absence #{$id} - Stage: {$oldStage} → {$absence->stage}, Level: {$oldLevel} → {$absence->level}",
            $absence
        );

        return response()->json([
            'message' => 'Stage and level updated successfully.',
            'ok' => true,
        ]);
    }

    /**
     * Approuver une demande d'absence
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve($id)
    {
        DB::beginTransaction();
        try {
            // Rechercher l'absence par ID
            $absence = Absence::findOrFail($id);
            $oldStage = $absence->stage;
            $oldLevel = $absence->level;

            $receiver = User::role('GRH')->first();
            $toEmployee = false;

            switch ($absence->level) {
                case 'ZERO':
                    $absence->stage = 'IN_PROGRESS';
                    $absence->level = 'ONE';
                    break;

                case 'ONE':
                    // Vérifier le solde AVANT de transmettre au DG (uniquement pour le GRH)
                    if ($absence->is_deductible && $absence->duty->absence_balance < $absence->requested_days) {
                        return response()->json([
                            'ok' => false,
                            'insufficient_balance' => true,
                            'message' => 'Solde de congés insuffisant',
                            'current_balance' => $absence->duty->absence_balance,
                            'requested_days' => $absence->requested_days,
                            'absence_id' => $absence->id,
                            'employee_name' => $absence->duty->employee->first_name.' '.$absence->duty->employee->last_name,
                        ], 422);
                    }

                    $absence->stage = 'IN_PROGRESS';
                    $absence->level = 'TWO';
                    $receiver = User::role('DG')->first();
                    break;

                case 'TWO':
                    $absence->stage = 'APPROVED';
                    $absence->level = 'THREE';

                    // Déduction du solde si applicable (en fonction du flag is_deductible de l'absence)
                    if ($absence->is_deductible) {
                        // Vérifier le solde avant de déduire
                        if ($absence->duty->absence_balance < $absence->requested_days) {
                            $this->activityLogger->log(
                                'warning',
                                "Approbation d'une absence déductible avec solde insuffisant: {$absence->duty->absence_balance} jours disponibles, {$absence->requested_days} jours demandés",
                                $absence
                            );
                        }

                        $absence->duty->absence_balance -= $absence->requested_days;
                        $absence->duty->save();

                        $this->activityLogger->log(
                            'updated',
                            "Déduction de {$absence->requested_days} jours du solde de congés - Nouveau solde: {$absence->duty->absence_balance}",
                            $absence
                        );
                    } else {
                        $this->activityLogger->log(
                            'info',
                            'Absence non déductible approuvée - Aucune déduction du solde de congés',
                            $absence
                        );
                    }

                    $toEmployee = true;
                    $this->assignAbsenceNumber($absence);
                    break;

                default:
                    $absence->stage = 'APPROVED';
                    $absence->level = 'THREE';

                    // Déduction du solde si applicable
                    if ($absence->is_deductible) {
                        // Vérifier le solde avant de déduire
                        if ($absence->duty->absence_balance < $absence->requested_days) {
                            $this->activityLogger->log(
                                'warning',
                                "Approbation d'une absence déductible avec solde insuffisant: {$absence->duty->absence_balance} jours disponibles, {$absence->requested_days} jours demandés",
                                $absence
                            );
                        }

                        $absence->duty->absence_balance -= $absence->requested_days;
                        $absence->duty->save();

                        $this->activityLogger->log(
                            'updated',
                            "Déduction de {$absence->requested_days} jours du solde de congés - Nouveau solde: {$absence->duty->absence_balance}",
                            $absence
                        );
                    } else {
                        $this->activityLogger->log(
                            'info',
                            'Absence non déductible approuvée - Aucune déduction du solde de congés',
                            $absence
                        );
                    }

                    $toEmployee = true;
                    $this->assignAbsenceNumber($absence);
                    break;
            }

            // Sauvegarder les changements
            $absence->save();

            $this->activityLogger->log(
                'approved',
                "Approbation de la demande d'absence #{$id} - Stage: {$oldStage} → {$absence->stage}, Level: {$oldLevel} → {$absence->level}",
                $absence
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->activityLogger->log(
                'error',
                "Erreur lors de l'approbation de la demande d'absence #{$id}: ".$e->getMessage(),
                isset($absence) ? $absence : null
            );

            return response()->json([
                'message' => "Erreur lors de l'approbation de la demande: ".$e->getMessage(),
                'ok' => false,
            ], 500);
        }

        // Gestion des notifications APRÈS le commit (asynchrone, ne bloque pas)
        $this->handleNotifications($absence, $receiver, $toEmployee);

        return response()->json([
            'message' => 'Demande de congé acceptée',
            'ok' => true,
            'absence' => $absence,
        ]);
    }

    /**
     * Attribuer un numéro d'absence lors de l'approbation finale
     *
     * @return void
     */
    private function assignAbsenceNumber(Absence $absence)
    {
        // Utiliser MAX() sans verrou pour éviter le blocage des requêtes parallèles
        // Le risque de conflit est minimal car les approbations finales sont rares
        $maxAbsenceNumber = Absence::whereNotNull('absence_number')
            ->max('absence_number');

        $absence->absence_number = ($maxAbsenceNumber ?? 0) + 1;
        $absence->date_of_approval = Carbon::now();
    }

    /**
     * Gérer les notifications après approbation (asynchrone via Job)
     *
     * @return void
     */
    private function handleNotifications(Absence $absence, User $receiver, bool $toEmployee)
    {
        try {
            // Vérifier que le destinataire a une adresse email valide
            if (! $receiver || ! $receiver->email || ! filter_var($receiver->email, FILTER_VALIDATE_EMAIL)) {
                Log::debug("Impossible d'envoyer l'email pour l'absence #{$absence->id}: destinataire sans email valide", [
                    'receiver_id' => $receiver?->id,
                ]);

                return;
            }

            // Préparer le mail approprié
            if ($toEmployee) {
                $url = route('absences.requests', $absence->stage == 'APPROVED' ? 'APPROVED' : 'REJECTED');
                $mailable = new AbsenceRequestUpdated($absence, $url);
            } else {
                $url = route('absences.requests', 'IN_PROGRESS');
                $mailable = new AbsenceRequestCreated($receiver, $absence, $url);
            }

            // Dispatcher le job pour envoi asynchrone (ne bloque pas la requête)
            SendEmailJob::dispatch($mailable);

            Log::debug("Job d'envoi d'email dispatché pour l'absence #{$absence->id}", [
                'to' => $receiver->email,
                'type' => $toEmployee ? 'update' : 'creation',
            ]);

        } catch (\Exception $e) {
            // Log l'erreur mais ne pas bloquer le processus
            Log::debug("Erreur lors du dispatch du job email pour l'absence #{$absence->id}: ".$e->getMessage(), [
                'error' => $e->getMessage(),
                'receiver' => $receiver->email ?? 'unknown',
            ]);
        }
    }

    /**
     * Rejeter une demande d'absence
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(Request $request, $id)
    {
        // Valider le commentaire obligatoire
        $request->validate([
            'comment' => 'required|string|min:10|max:1000',
        ], [
            'comment.required' => 'Un motif de rejet est obligatoire.',
            'comment.min' => 'Le motif doit contenir au moins 10 caractères.',
            'comment.max' => 'Le motif ne peut pas dépasser 1000 caractères.',
        ]);

        // Rechercher l'absence par ID
        $absence = Absence::findOrFail($id);
        $oldStage = $absence->stage;
        $oldLevel = $absence->level;

        switch ($absence->level) {
            case 'ZERO':
                $absence->level = 'ONE';
                break;
            case 'ONE':
                $absence->level = 'TWO';
                break;
            case 'TWO':
                $absence->level = 'THREE';
                break;
            default:
                $absence->level = 'THREE';
                break;
        }
        $absence->stage = 'REJECTED';
        $absence->comment = $request->input('comment');

        $absence->save();

        $this->activityLogger->log(
            'rejected',
            "Rejet de la demande d'absence #{$id} - Stage: {$oldStage} → {$absence->stage}, Level: {$oldLevel} → {$absence->level}. Motif: {$absence->comment}",
            $absence
        );
        $receiver = $absence->duty->employee->users->first();
        $toEmployee = true;
        $this->handleNotifications($absence, $receiver, $toEmployee);

        return response()->json([
            'message' => "Demande de {$absence->absence_type->label} rejetée",
            'ok' => true,
        ]);
    }

    /**
     * Ajouter ou modifier un commentaire sur une absence
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function comment(Request $request, $id)
    {
        // Valider les entrées
        $request->validate([
            'comment' => 'nullable|string|max:1000',
        ], [
            'comment.max' => 'Le commentaire ne peut pas dépasser 1000 caractères.',
        ]);

        // Rechercher l'absence par ID
        $absence = Absence::findOrFail($id);

        $oldComment = $absence->comment;
        $absence->comment = $request->input('comment');
        $absence->save();

        $this->activityLogger->log(
            'updated',
            "Modification du commentaire de l'absence #{$id}",
            $absence,
            ['old_comment' => $oldComment, 'new_comment' => $absence->comment]
        );

        return response()->json([
            'message' => 'Commentaire enregistré',
            'ok' => true,
        ]);
    }

    /**
     * Modifier le statut de déductibilité d'une absence (GRH uniquement)
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDeductibility(Request $request, $id)
    {
        // Vérifier que l'utilisateur a le rôle GRH
        if (! auth()->user()->hasRole('GRH')) {
            $this->activityLogger->log(
                'denied',
                "Tentative non autorisée de modification de déductibilité de l'absence #{$id}",
                null
            );

            return response()->json([
                'ok' => false,
                'message' => 'Seul le GRH peut modifier la déductibilité d\'une absence.',
            ], 403);
        }

        // Valider les entrées
        $request->validate([
            'is_deductible' => 'required|boolean',
        ]);

        // Rechercher l'absence par ID
        $absence = Absence::findOrFail($id);

        $oldDeductibility = $absence->is_deductible;
        $newDeductibility = $request->boolean('is_deductible');

        // Si pas de changement, retourner directement
        if ($oldDeductibility === $newDeductibility) {
            return response()->json([
                'message' => 'Aucun changement effectué',
                'ok' => true,
            ]);
        }

        // Gestion du solde si l'absence est déjà approuvée
        if ($absence->stage === 'APPROVED') {
            if ($oldDeductibility && ! $newDeductibility) {
                // Passage de déductible à non déductible : rembourser
                $absence->duty->absence_balance += $absence->requested_days;
                $absence->duty->save();

                $this->activityLogger->log(
                    'updated',
                    "Remboursement de {$absence->requested_days} jours au solde de congés - Nouveau solde: {$absence->duty->absence_balance}",
                    $absence
                );
            } elseif (! $oldDeductibility && $newDeductibility) {
                // Passage de non déductible à déductible : déduire
                if ($absence->duty->absence_balance < $absence->requested_days) {
                    $this->activityLogger->log(
                        'warning',
                        "Déduction avec solde insuffisant: {$absence->duty->absence_balance} jours disponibles, {$absence->requested_days} jours demandés",
                        $absence
                    );
                }

                $absence->duty->absence_balance -= $absence->requested_days;
                $absence->duty->save();

                $this->activityLogger->log(
                    'updated',
                    "Déduction de {$absence->requested_days} jours du solde de congés - Nouveau solde: {$absence->duty->absence_balance}",
                    $absence
                );
            }
        }

        // Mettre à jour le statut de déductibilité
        $absence->is_deductible = $newDeductibility;
        $absence->save();

        $this->activityLogger->log(
            'updated',
            "Modification du statut de déductibilité de l'absence #{$id} - Déductible: ".
            ($oldDeductibility ? 'Oui' : 'Non').' → '.
            ($newDeductibility ? 'Oui' : 'Non'),
            $absence
        );

        return response()->json([
            'message' => $newDeductibility ? 'Absence marquée comme déductible' : 'Absence marquée comme non déductible',
            'ok' => true,
            'is_deductible' => $newDeductibility,
            'new_balance' => $absence->duty->absence_balance,
        ]);
    }

    /**
     * Approuver une absence avec une option de résolution pour solde insuffisant (GRH uniquement)
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approveWithOption(Request $request, $id)
    {
        // Vérifier que l'utilisateur a le rôle GRH
        if (! auth()->user()->hasRole('GRH')) {
            return response()->json([
                'ok' => false,
                'message' => 'Action réservée au GRH.',
            ], 403);
        }

        // Valider l'option choisie
        $request->validate([
            'option' => 'required|in:make_non_deductible,deduct_available',
        ], [
            'option.required' => 'Veuillez choisir une option.',
            'option.in' => 'Option invalide.',
        ]);

        DB::beginTransaction();
        try {
            $absence = Absence::findOrFail($id);

            // Vérifier que l'absence est bien au level ONE
            if ($absence->level !== 'ONE') {
                return response()->json([
                    'ok' => false,
                    'message' => 'Cette action n\'est possible qu\'au niveau de validation GRH.',
                ], 422);
            }

            $option = $request->input('option');
            $oldDeductibility = $absence->is_deductible;

            switch ($option) {
                case 'make_non_deductible':
                    // Option A : Passer en non-déductible
                    $absence->is_deductible = false;

                    $this->activityLogger->log(
                        'updated',
                        "GRH a choisi de passer l'absence #{$id} en non-déductible (solde insuffisant: {$absence->duty->absence_balance} jours disponibles, {$absence->requested_days} jours demandés)",
                        $absence
                    );
                    break;

                case 'deduct_available':
                    // Option B : Déduire uniquement le solde disponible
                    $availableBalance = $absence->duty->absence_balance;
                    $daysToDeduct = min($absence->requested_days, $availableBalance);

                    // Effectuer la déduction immédiatement
                    $absence->duty->absence_balance = 0;
                    $absence->duty->save();

                    // Marquer comme non-déductible pour éviter double déduction au level TWO
                    $absence->is_deductible = false;

                    // Stocker l'info dans le commentaire pour traçabilité
                    $deductionComment = "Déduction partielle effectuée par GRH: {$daysToDeduct} jour(s) déduit(s) sur {$absence->requested_days} demandé(s).";
                    $absence->comment = $absence->comment
                        ? $absence->comment."\n".$deductionComment
                        : $deductionComment;

                    $this->activityLogger->log(
                        'updated',
                        "GRH a choisi de déduire uniquement le solde disponible ({$daysToDeduct} jours) pour l'absence #{$id} - Nouveau solde: 0",
                        $absence
                    );
                    break;
            }

            // Continuer avec l'approbation normale (passage au level TWO)
            $absence->stage = 'IN_PROGRESS';
            $absence->level = 'TWO';
            $absence->save();

            $this->activityLogger->log(
                'approved',
                "Approbation de la demande d'absence #{$id} avec option '{$option}' - Level: ONE → TWO",
                $absence
            );

            // Récupérer le destinataire pour la notification
            $receiver = User::role('DG')->first();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->activityLogger->log(
                'error',
                "Erreur lors de l'approbation avec option de l'absence #{$id}: ".$e->getMessage(),
                isset($absence) ? $absence : null
            );

            return response()->json([
                'message' => 'Erreur lors de la validation: '.$e->getMessage(),
                'ok' => false,
            ], 500);
        }

        // Notification au DG APRÈS le commit (asynchrone, ne bloque pas)
        $this->handleNotifications($absence, $receiver, false);

        return response()->json([
            'message' => 'Demande validée et transmise au DG',
            'ok' => true,
            'absence' => $absence,
        ]);
    }

    /**
     * Annuler une demande d'absence
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        try {
            DB::beginTransaction();

            // Rechercher l'absence par ID
            $absence = Absence::findOrFail($id);
            $oldStage = $absence->stage;

            // Vérifier si l'annulation est possible
            if ($absence->level != 'ZERO') {
                $this->activityLogger->log(
                    'denied',
                    "Tentative d'annulation d'une demande d'absence #{$id} non annulable",
                    $absence
                );

                return response()->json([
                    'ok' => false,
                    'message' => "Vous ne pouvez plus annuler cette demande de {$absence->absence_type->label}.",
                ], 403);
            }

            // Si l'absence était approuvée et déductible, rembourser les jours
            if ($absence->stage === 'APPROVED' && $absence->is_deductible) {
                $absence->duty->absence_balance += $absence->requested_days;
                $absence->duty->save();

                $this->activityLogger->log(
                    'updated',
                    "Remboursement de {$absence->requested_days} jours au solde de congés suite à annulation - Nouveau solde: {$absence->duty->absence_balance}",
                    $absence
                );
            }

            $absence->stage = 'CANCELLED';
            $absence->save();

            $this->activityLogger->log(
                'cancelled',
                "Annulation de la demande d'absence #{$id} - Stage: {$oldStage} → {$absence->stage}",
                $absence
            );

            DB::commit();

            return response()->json([
                'message' => "Demande de {$absence->absence_type->label} annulée",
                'ok' => true,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->activityLogger->log(
                'error',
                "Erreur lors de l'annulation de la demande d'absence #{$id}: ".$e->getMessage(),
                isset($absence) ? $absence : null
            );

            return response()->json([
                'message' => "Erreur lors de l'annulation de la demande: ".$e->getMessage(),
                'ok' => false,
            ], 500);
        }
    }
}
