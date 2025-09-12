<?php

namespace App\Http\Controllers\OptiHr;

use App\Http\Controllers\Controller;
use App\Mail\AbsenceRequestCreated;
use App\Mail\AbsenceRequestUpdated;
use App\Models\OptiHr\Absence;
use App\Models\OptiHr\AbsenceType;
use App\Models\OptiHr\Duty;
use App\Models\OptiHr\AnnualDecision;
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
        $this->middleware(['permission:écrire-une-absence|écrire-un-tout'], ['only' => ['approve', 'reject', 'comment']]);
    }

   /**
    * Télécharger le PDF d'une absence
    *
    * @param int $absenceId L'identifiant de l'absence
    * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
    */
    public function download($absenceId)
    {
        try {
            $absence = Absence::findOrFail($absenceId);
            $decision = AnnualDecision::where('state', 'current')->first();
            
            if (!$decision) {
                $this->activityLogger->log(
                    'error',
                    "Échec du téléchargement du PDF d'absence #{$absence->id}: Aucune décision annuelle active",
                    $absence
                );
                
                return response()->json([
                    'error' => 'Aucune décision annuelle active trouvée',
                    'message' => 'Impossible de générer le PDF sans décision annuelle'
                ], 404);
            }
            
            $absencePdf = new AbsencePdfService();
            
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
                "Erreur lors du téléchargement du PDF d'absence #{$absenceId}: " . $e->getMessage(),
                isset($absence) ? $absence : null
            );
            
            return response()->json([
                'error' => 'Erreur lors de la génération du PDF',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher la liste des absences filtrée par étape
     *
     * @param Request $request
     * @param string $stage
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request, $stage = 'PENDING')
    {
        // Liste des stages valides
        $validStages = ['PENDING', 'APPROVED', 'REJECTED', 'CANCELLED', 'IN_PROGRESS', 'COMPLETED'];

        // Vérification de la validité du stage
        if ($stage !== 'ALL' && !in_array($stage, $validStages)) {
            $this->activityLogger->log(
                'error',
                "Tentative d'accès avec un stage invalide: {$stage}"
            );

            return redirect()->route('absences.index')->with('error', 'Stage invalide');
        }

        // Récupérer les filtres de recherche
        $type = $request->input('type');
        $search = $request->input('search');

        // Récupérer les types d'absences (éviter de faire la requête à chaque appel)
        $absence_types = AbsenceType::all();

        // Construire la requête principale avec les relations nécessaires
        $query = $this->buildAbsenceQuery($search, $type, $stage);

        // Pagination adaptative selon le type de stage
        $absences = $this->getPaginatedResults($query, $stage);

        $this->activityLogger->log(
            'view',
            "Consultation de la liste des absences - Stage: {$stage}" .
            ($type ? ", Type: {$type}" : "") .
            ($search ? ", Recherche: {$search}" : "")
        );

        // Retourner la vue avec les données nécessaires
        return view('modules.opti-hr.pages.attendances.absences.index', compact('absences', 'stage', 'absence_types'));
    }

    /**
     * Construire la requête d'absences avec filtres
     *
     * @param string|null $search
     * @param string|null $type
     * @param string $stage
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildAbsenceQuery($search, $type, $stage)
    {
        // Construire la requête principale avec les relations nécessaires
        $query = Absence::with(['absence_type', 'duty', 'duty.employee']);

        // Appliquer le filtre de recherche (groupe de conditions OR)
        $query->when($search, function ($q) use ($search) {
            $q->whereHas('duty.employee', function ($query) use ($search) {
                $query->where('first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $search . '%');
            });
        });

        // Trier par date de demande
        $query->orderBy('date_of_application');

        // Filtrer par type d'absence, si précisé
        $query->when($type, function ($q) use ($type) {
            $q->where('absence_type_id', $type);
        });

        // Filtrer par stage si le stage n'est pas "ALL"
        $query->when($stage !== 'ALL', function ($q) use ($stage) {
            $q->where('stage', $stage);
        });

        return $query;
    }

    /**
     * Obtenir les résultats paginés en fonction du stage
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $stage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    private function getPaginatedResults($query, $stage)
    {
        // Appliquer la pagination seulement pour certains stages
        return (in_array($stage, ['PENDING', 'IN_PROGRESS']))
            ? $query->paginate(10) // Augmenté à 10 pour une meilleure visibilité des demandes
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
     * @param string $startDate Date de début au format Y-m-d
     * @param string $endDate Date de fin au format Y-m-d
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation des champs de la requête
        $validatedData = $request->validate([
            'absence_type' => 'required|exists:absence_types,id',
            'address' => 'required|string|max:255',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reasons' => 'nullable|string|max:1000',
            'is_deductible' => 'sometimes|boolean',
        ]);

        // Calcul du nombre de jours d'absence
        $workingDays = $this->calculateWorkingDays($validatedData['start_date'], $validatedData['end_date']);

        // Récupération de l'employé actuel et de sa mission en cours
        $currentUser = User::with('employee')->findOrFail(auth()->id());
        $currentEmployee = $currentUser->employee;

        $currentEmployeeDuty = Duty::where('evolution', 'ON_GOING')
            ->where('employee_id', $currentEmployee->id)
            ->firstOrFail();
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
        ]);

        $this->activityLogger->log(
            'created',
            "Création d'une demande d'absence de type {$absenceType->label} " . 
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
            "redirect" => route('absences.requests', 'PENDING')
        ]);
    }

    /**
     * Calcule le nombre de jours entre deux dates via une requête AJAX
     *
     * @param Request $request
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
     * @param Request $request
     * @param int $id
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
     * @param int $id
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
                            "Absence non déductible approuvée - Aucune déduction du solde de congés",
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
                            "Absence non déductible approuvée - Aucune déduction du solde de congés",
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

            // Gestion des notifications
            $this->handleNotifications($absence, $receiver, $toEmployee);

            DB::commit();

            return response()->json([
                'message' => 'Demande de congé acceptée',
                'ok' => true,
                'absence' => $absence
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->activityLogger->log(
                'error',
                "Erreur lors de l'approbation de la demande d'absence #{$id}: " . $e->getMessage(),
                isset($absence) ? $absence : null
            );
            
            return response()->json([
                'message' => "Erreur lors de l'approbation de la demande: " . $e->getMessage(),
                'ok' => false
            ], 500);
        }
    }

    /**
     * Attribuer un numéro d'absence lors de l'approbation finale
     *
     * @param Absence $absence
     * @return void
     */
    private function assignAbsenceNumber(Absence $absence)
    {
        // Trouver le maximum actuel de absence_number de manière sécurisée
        $maxAbsenceNumber = DB::table($absence->getTable())
            ->whereNotNull('absence_number')
            ->orderByDesc('absence_number')
            ->lockForUpdate()
            ->value('absence_number');

        $absence->absence_number = $maxAbsenceNumber ? $maxAbsenceNumber + 1 : 1;
        $absence->date_of_approval = Carbon::now();
    }

    /**
     * Gérer les notifications après approbation
     *
     * @param Absence $absence
     * @param User $receiver
     * @param bool $toEmployee
     * @return void
     */
    private function handleNotifications(Absence $absence, User $receiver, bool $toEmployee)
    {
        try {
            // Vérifier que le destinataire a une adresse email valide
            if (!$receiver || !$receiver->email) {
                $this->activityLogger->log(
                    'warning',
                    "Impossible d'envoyer l'email pour l'absence #{$absence->id}: destinataire sans email",
                    $absence,
                    ['receiver_id' => $receiver?->id]
                );
                return;
            }
            
            // Valider l'adresse email
            if (!filter_var($receiver->email, FILTER_VALIDATE_EMAIL)) {
                $this->activityLogger->log(
                    'warning',
                    "Email invalide pour l'envoi de notification d'absence #{$absence->id}",
                    $absence,
                    ['email' => $receiver->email]
                );
                return;
            }
            
            // Préparer le mail approprié
            if ($toEmployee) {
                $url = route('absences.requests', $absence->stage == "APPROVED" ? 'APPROVED' : 'REJECTED');
                $mailable = new AbsenceRequestUpdated($absence, $url);
            } else {
                $url = route('absences.requests', 'IN_PROGRESS');
                $mailable = new AbsenceRequestCreated($receiver, $absence, $url);
            }
            
            // Envoyer l'email avec le système sécurisé
            $sent = $this->sendEmail($mailable, true); // true pour utiliser la queue si disponible
            
            if ($sent) {
                $this->activityLogger->log(
                    'info',
                    "Email de notification envoyé pour l'absence #{$absence->id}",
                    $absence,
                    [
                        'to' => $receiver->email,
                        'type' => $toEmployee ? 'update' : 'creation'
                    ]
                );
            } else {
                $this->activityLogger->log(
                    'error',
                    "Échec de l'envoi d'email pour l'absence #{$absence->id}",
                    $absence,
                    ['to' => $receiver->email]
                );
            }
            
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas bloquer le processus
            $this->activityLogger->log(
                'error',
                "Erreur lors de l'envoi de notification pour l'absence #{$absence->id}: " . $e->getMessage(),
                $absence,
                [
                    'error' => $e->getMessage(),
                    'receiver' => $receiver->email ?? 'unknown'
                ]
            );
        }
    }

    /**
     * Rejeter une demande d'absence
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject($id)
    {
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

        $absence->save();

        $this->activityLogger->log(
            'rejected',
            "Rejet de la demande d'absence #{$id} - Stage: {$oldStage} → {$absence->stage}, Level: {$oldLevel} → {$absence->level}",
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
     *  Modifier le statut de déductibilité d'une absence
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function comment(Request $request, $id)
    {
      
        // Valider les entrées
        $request->validate([
            'is_deductible' => 'required|boolean',
                        'comment' => 'sometimes',

        ]);

        // Rechercher l'absence par ID
        $absence = Absence::findOrFail($id);
        
        // Vérifier si l'absence est déjà approuvée et était déductible
        if ($absence->stage === 'APPROVED' && $absence->is_deductible && !$request->boolean('is_deductible')) {
            // L'absence passe de déductible à non déductible
            // On doit rembourser les jours déduits précédemment
            $absence->duty->absence_balance += $absence->requested_days;
            $absence->duty->save();
            
            $this->activityLogger->log(
                'updated',
                "Remboursement de {$absence->requested_days} jours au solde de congés - Nouveau solde: {$absence->duty->absence_balance}",
                $absence
            );
        } 
        // Vérifier si l'absence est déjà approuvée et n'était pas déductible
        else if ($absence->stage === 'APPROVED' && !$absence->is_deductible && $request->boolean('is_deductible')) {
            // L'absence passe de non déductible à déductible
            // On doit déduire les jours
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
        
        // Mettre à jour le statut de déductibilité
        $oldDeductibility = $absence->is_deductible;
        $absence->is_deductible = $request->boolean('is_deductible');
                $oldComment = $absence->comment;

          $absence->comment = $request->input('comment') ?? null;
        $absence->save();



       
        $this->activityLogger->log(
            'updated',
            "Modification du statut de déductibilité de l'absence #{$id} - Déductible: " . 
            ($oldDeductibility ? 'Oui' : 'Non') . " → " . 
            ($absence->is_deductible ? 'Oui' : 'Non'),
            $absence
        );
        
        return response()->json([
            'message' => "Statut de déductibilité mis à jour",
            'ok' => true,
        ]);

    }


       

      

       
    

    /**
     * Annuler une demande d'absence
     *
     * @param int $id
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
                "Erreur lors de l'annulation de la demande d'absence #{$id}: " . $e->getMessage(),
                isset($absence) ? $absence : null
            );
            
            return response()->json([
                'message' => "Erreur lors de l'annulation de la demande: " . $e->getMessage(),
                'ok' => false
            ], 500);
        }
    }
}