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
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AbsenceController extends Controller
{


    public function __construct()
    {
        parent::__construct(app(ActivityLogger::class)); // Injection automatique


        $this->middleware(['permission:voir-une-absence|écrire-une-absence|créer-une-absence|configurer-une-absence|voir-un-tout'], ['only' => ['index']]);
        $this->middleware(['permission:créer-une-absence|créer-un-tout'], ['only' => ['store', 'cancel', 'create']]);
        $this->middleware(['permission:écrire-une-absence|écrire-un-tout'], ['only' => ['approve', 'reject', 'comment']]);
    }

    /**
     * Télécharger le PDF d'une absence
     *
     * @param int $absenceId L'identifiant de l'absence
     * @return mixed
     */
    public function download($absenceId)
    {
        $absence = Absence::findOrFail($absenceId);
        $decision = AnnualDecision::where('state', 'current')->first();
        $absencePdf = new AbsencePdfService();

        $this->activityLogger->log(
            'download',
            "Téléchargement du PDF d'absence #{$absence->id}",
            $absence
        );

        return $absencePdf->generate($absence, $decision);

    }

    /**
     * Afficher la liste des absences filtrée par étape
     *
     * @param Request $request
     * @param string $stage
     * @return \Illuminate\View\View
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
        // Récupérer les filtres de recherche
        $type = $request->input('type');
        $search = $request->input('search');

        // Récupérer les types d'absences (éviter de faire la requête à chaque appel)
        $absence_types = AbsenceType::all();
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
                $query->where('first_name', 'ILIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'ILIKE', '%' . $search . '%');
            });
        });

        // Trier par date de demande
        $query->orderBy('date_of_application');

        // Filtrer par type d'absence, si précisé
        $query->when($type, function ($q) use ($type) {
            $q->where('absence_type_id', $type);
        });
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
            ? $query->paginate(2)
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
        return view('modules.opti-hr.pages.attendances.absences.create', compact('absenceTypes'));

    }

    /**
     * Calculer le nombre de jours ouvrés entre deux dates
     *
     * @param string $startDate
     * @param string $endDate
     * @return int
     */
    private function calculateWorkingDays($startDate, $endDate)
    {
        $count = 0;
        $currentDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        while ($currentDate->lte($endDate)) {
            ++$count;
            $currentDate->addDay();
        }

        return $count;
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
        ]);

        // Calcul du nombre de jours d'absence
        $workingDays = $this->calculateWorkingDays($validatedData['start_date'], $validatedData['end_date']);

        // Récupération de l'employé actuel et de sa mission en cours
        $currentUser = User::with('employee')->findOrFail(auth()->id());
        $currentEmployee = $currentUser->employee;
        // Récupération de l'employé actuel et de sa mission en cours
        $currentUser = User::with('employee')->findOrFail(auth()->id());
        $currentEmployee = $currentUser->employee;

        $currentEmployeeDuty = Duty::where('evolution', 'ON_GOING')
            ->where('employee_id', $currentEmployee->id)
            ->firstOrFail();
        $absence_type_id = $request->input('absence_type');

        // Obtenir le type d'absence pour le log
        $absenceType = AbsenceType::find($absence_type_id);

        // Enregistrement de la demande d'absence
        $absence = Absence::create([
            'duty_id' => $currentEmployeeDuty->id,
            'absence_type_id' => $absence_type_id,
            'address' => $validatedData['address'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'reasons' => $validatedData['reasons'],
            'requested_days' => $workingDays,
        ]);

        $this->activityLogger->log(
            'created',
            "Création d'une demande d'absence de type {$absenceType->label}",
            $absence
        );

        // Pour la notification par email (à activer si nécessaire)
        // $receiver = $absence->duty->job->n_plus_one_job ?
        //   $absence->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->users->first()
        //   : User::role('GRH')->first();
        // Mail::send(new AbsenceRequestCreated($receiver, $absence, route('absences.requests')));

        return response()->json([
            'message' => "Demande d'absence {$absenceType->label} créée avec succès.",
            'ok' => true,
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
                $toEmployee = true;
                $this->assignAbsenceNumber($absence);
                break;

            default:
                $absence->stage = 'APPROVED';
                $absence->level = 'THREE';
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
        $this->handleApprovalNotifications($absence, $receiver, $toEmployee);

        DB::commit();

        return response()->json([
            'message' => 'Demande de congé acceptée',
            'ok' => true,
            'absence' => $absence
        ]);

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
    private function handleApprovalNotifications(Absence $absence, User $receiver, bool $toEmployee)
    {
        if ($toEmployee) {
            $url = route('absences.requests', 'ALL');
            // Mail::send(new AbsenceRequestUpdated($absence, $url));
        } else {
            $url = route('absences.requests', 'IN_PROGRESS');
            // Mail::send(new AbsenceRequestCreated($receiver, $absence, $url));
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

        return response()->json([
            'message' => "Demande de {$absence->absence_type->label} rejetée",
            'ok' => true,
        ]);

    }

    /**
     * Ajouter un commentaire à une absence
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function comment(Request $request, $id)
    {
        // Valider les entrées
        $request->validate([
            'comment' => 'sometimes',
        ]);

        // Rechercher l'absence par ID
        $absence = Absence::findOrFail($id);
        $oldComment = $absence->comment;

        $absence->comment = $request->input('comment') ?? null;
        $absence->save();

        $this->activityLogger->log(
            'commented',
            "Ajout/Modification d'un commentaire sur l'absence #{$id}",
            $absence
        );

        return response()->json([
            'message' => "Commentaire ajouté à la demande de {$absence->absence_type->label}",
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
        // Rechercher l'absence par ID
        $absence = Absence::findOrFail($id);
        $oldStage = $absence->stage;

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

        $absence->stage = 'CANCELLED';
        $absence->save();

        $this->activityLogger->log(
            'cancelled',
            "Annulation de la demande d'absence #{$id} - Stage: {$oldStage} → {$absence->stage}",
            $absence
        );

        return response()->json([
            'message' => "Demande de {$absence->absence_type->label} annulée",
            'ok' => true,
        ]);

    }
}
