<?php

namespace App\Http\Controllers\OptiHr;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentRequestMail;
use App\Http\Controllers\Controller;
use App\Models\OptiHr\DocumentRequest;
use App\Models\OptiHr\DocumentType;
use App\Models\OptiHr\Duty;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\DocumentPdfService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class DocumentRequestController extends Controller
{
    /**
     * Le service de journalisation des activités
     *
     * @var ActivityLogService
     */
    protected $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;

        $this->middleware(['permission:voir-un-document|écrire-un-document|créer-un-document|configurer-un-document|voir-un-tout'], ['only' => ['index', "download"]]);
        $this->middleware(['permission:créer-un-document|créer-un-tout'], ['only' => ['store', 'cancel', 'create']]);
    }

    /**
     * Télécharger le PDF d'une demande de document
     *
     * @param int $documentRequestId L'identifiant de la demande de document
     * @return mixed
     */
    public function download($documentRequestId)
    {

        $documentRequest = DocumentRequest::findOrFail($documentRequestId);
        $documentPdf = new DocumentPdfService();

        $this->activityLogger->log(
            'download',
            "Téléchargement du PDF de demande de document #{$documentRequest->id}",
            $documentRequest
        );

        return $documentPdf->generate($documentRequest);

    }

    /**
     * Afficher la liste des demandes de documents filtrée par étape
     *
     * @param Request $request
     * @param string $stage
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $stage = 'PENDING')
    {

        // Liste des stages valides
        $validStages = ['PENDING', 'APPROVED', 'REJECTED', 'CANCELLED', 'IN_PROGRESS', 'COMPLETED'];

        // Liste des stages valides
        $validStages = ['PENDING', 'APPROVED', 'REJECTED', 'CANCELLED', 'IN_PROGRESS', 'COMPLETED'];

        // Vérification de la validité du stage
        if ($stage !== 'ALL' && !in_array($stage, $validStages)) {
            $this->activityLogger->log(
                'error',
                "Tentative d'accès aux demandes de documents avec un stage invalide: {$stage}"
            );

            return redirect()->route('documents.requests')->with('error', 'Stage invalide');
        }

        // Récupérer les filtres de recherche
        $type = $request->input('type');
        $search = $request->input('search');
        // Récupérer les filtres de recherche
        $type = $request->input('type');
        $search = $request->input('search');

        // Récupérer les types de document
        $document_types = DocumentType::all();

        // Construire la requête principale
        $query = $this->buildDocumentRequestQuery($search, $type, $stage);

        // Pagination adaptative selon le type de stage
        $documentRequests = $this->getPaginatedResults($query, $stage);

        $this->activityLogger->log(
            'view',
            "Consultation de la liste des demandes de documents - Stage: {$stage}" .
            ($type ? ", Type: {$type}" : "") .
            ($search ? ", Recherche: {$search}" : "")
        );

        // Retourner la vue avec les données nécessaires
        return view('modules.opti-hr.pages.documents.main.index', compact('documentRequests', 'stage', 'document_types'));

    }

    /**
     * Construire la requête de demandes de documents avec filtres
     *
     * @param string|null $search
     * @param string|null $type
     * @param string $stage
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildDocumentRequestQuery($search, $type, $stage)
    {
        // Construire la requête principale avec les relations nécessaires
        $query = DocumentRequest::with(['document_type', 'duty', 'duty.employee']);

        // Appliquer le filtre de recherche
        $query->when($search, function ($q) use ($search) {
            $q->whereHas('duty.employee', function ($query) use ($search) {
                $query->where('first_name', 'ILIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'ILIKE', '%' . $search . '%');
            });
        });

        // Trier par date de demande
        $query->orderBy('date_of_application');

        // Filtrer par type de document, si précisé
        $query->when($type, function ($q) use ($type) {
            $q->where('document_type_id', $type);
        });
        // Filtrer par type de document, si précisé
        $query->when($type, function ($q) use ($type) {
            $q->where('document_type_id', $type);
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
     * Afficher le formulaire de création de demande de document
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {

        $documentTypes = DocumentType::all();

        $this->activityLogger->log(
            'access',
            "Accès au formulaire de création de demande de document"
        );

        return view('modules.opti-hr.pages.documents.main.create', compact('documentTypes'));


    }

    /**
     * Enregistrer une nouvelle demande de document
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        // Validation des champs de la requête
        $validatedData = $request->validate([
            'document_type' => 'required|exists:document_types,id', // Corrigé de absence_types à document_types
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Récupération de l'employé actuel et de sa mission en cours
        $currentUser = User::with('employee')->findOrFail(auth()->id());
        $currentEmployee = $currentUser->employee;

        $currentEmployeeDuty = Duty::where('evolution', 'ON_GOING')
            ->where('employee_id', $currentEmployee->id)
            ->firstOrFail();
        $document_type_id = $request->input('document_type');


        // Obtenir le type de document pour le log
        $documentType = DocumentType::find($document_type_id);

        // Enregistrement de la demande de document
        $documentRequest = DocumentRequest::create([
            'duty_id' => $currentEmployeeDuty->id,
            'document_type_id' => $document_type_id,
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
        ]);
        $receiver = User::role('GRH')->first();
        // Là où vous voulez envoyer l'email
        $emailData = [
            'receiverName' => $receiver->employee->last_name . " " . $receiver->employee->first_name,
            'requesterName' => $currentEmployee->last_name . " " . $currentEmployee->first_name,
            'documentType' => $documentRequest->document_type->name,
            'dateOfApplication' => $documentRequest->date_of_application,
            'startDate' => $documentRequest->start_date,
            'endDate' => $documentRequest->end_date,
            'reasons' => $documentRequest->reasons,
            'url' => route('document-requests.show', $documentRequest->id)
        ];

        Mail::to($user->email)
            ->send(new DocumentRequestMail($emailData));
        $this->activityLogger->log(
            'created',
            "Création d'une demande de document de type {$documentType->label}",
            $documentRequest
        );

        $var = $documentRequest->document_type ? $documentRequest->document_type->label : '';

        return response()->json([
            'message' => "Demande de {$var} créée avec succès.",
            'ok' => true,
        ]);

    }

    /**
     * Met à jour le stage et le level d'une demande de document
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

        // Valider les entrées
        $validatedData = $request->validate([
            'stage' => 'required|in:PENDING,APPROVED,REJECTED,CANCELLED,IN_PROGRESS,COMPLETED',
            'level' => 'required|in:ZERO,ONE,TWO,THREE',
        ]);

        // Rechercher la demande de document par ID (corrigé de Absence à DocumentRequest)
        $documentRequest = DocumentRequest::findOrFail($id);

        // Sauvegarder les valeurs précédentes pour le log
        $oldStage = $documentRequest->stage;
        $oldLevel = $documentRequest->level;

        // Mettre à jour les champs stage et level
        $documentRequest->stage = $validatedData['stage'];
        $documentRequest->level = $validatedData['level'];

        // Sauvegarder les modifications
        $documentRequest->save();

        $this->activityLogger->log(
            'updated',
            "Mise à jour du statut de la demande de document #{$id} - Stage: {$oldStage} → {$documentRequest->stage}, Level: {$oldLevel} → {$documentRequest->level}",
            $documentRequest
        );

        return response()->json([
            'message' => 'Stage and level updated successfully.',
            'ok' => true,
        ]);

    }

    /**
     * Approuver une demande de document
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve($id)
    {
        DB::beginTransaction();


        // Rechercher la demande de document par ID
        $documentRequest = DocumentRequest::findOrFail($id);
        $oldStage = $documentRequest->stage;
        $oldLevel = $documentRequest->level;

        // Mise à jour du niveau et du statut
        $documentRequest->updateLevelAndStage();
        if ($documentRequest->status == 'APPROVED') {
            # code...

            // Préparer les données
            $emailData = [
                'receiverName' => $documentRequest->duty->employee->name, // Adaptez selon votre modèle
                'documentRequest' => $documentRequest,
                'documentType' => $documentRequest->document_type->name,
                'status' => $documentRequest->status == 'APPROVED' ? 'approuvée' : 'refusée',
                'comment' => $documentRequest->comment, // Commentaire facultatif
                'url' => route('documents.requests', "APPROVED")
            ];
            // Envoyer l'email
            Mail::to($documentRequest->duty->employee->email) // Adaptez selon votre modèle
                ->send(new DocumentRequestStatusMail($emailData));
        }

        $this->activityLogger->log(
            'approved',
            "Approbation de la demande de document #{$id} - Stage: {$oldStage} → {$documentRequest->stage}, Level: {$oldLevel} → {$documentRequest->level}",
            $documentRequest
        );

        DB::commit();

        return response()->json([
            'message' => 'Demande de document acceptée',
            'ok' => true,
            'documentRequest' => $documentRequest
        ]);

    }

    /**
     * Rejeter une demande de document
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject($id)
    {

        // Rechercher la demande de document par ID
        $documentRequest = DocumentRequest::findOrFail($id);
        $oldStage = $documentRequest->stage;
        $oldLevel = $documentRequest->level;

        switch ($documentRequest->level) {
            case 'ZERO':
                $documentRequest->level = 'ONE';
                break;
            case 'ONE':
                $documentRequest->level = 'TWO';
                break;
            case 'TWO':
                $documentRequest->level = 'THREE';
                break;
            default:
                $documentRequest->level = 'THREE';
                break;
        }
        $documentRequest->stage = 'REJECTED';

        $documentRequest->save();
        if ($documentRequest->status == 'REJECTED') {
            # code...

            // Préparer les données
            $emailData = [
                'receiverName' => $documentRequest->duty->employee->name, // Adaptez selon votre modèle
                'documentRequest' => $documentRequest,
                'documentType' => $documentRequest->document_type->name,
                'status' => $documentRequest->status == 'APPROVED' ? 'approuvée' : 'refusée',
                'comment' => $documentRequest->comment, // Commentaire facultatif
                'url' => route('documents.requests', "REJECTED")
            ];
            // Envoyer l'email
            Mail::to($documentRequest->duty->employee->email) // Adaptez selon votre modèle
                ->send(new DocumentRequestStatusMail($emailData));
        }
        $this->activityLogger->log(
            'rejected',
            "Rejet de la demande de document #{$id} - Stage: {$oldStage} → {$documentRequest->stage}, Level: {$oldLevel} → {$documentRequest->level}",
            $documentRequest
        );

        return response()->json([
            'message' => "Demande de {$documentRequest->document_type->label} rejetée",
            'ok' => true,
        ]);

    }

    /**
     * Ajouter un commentaire à une demande de document
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

        // Rechercher la demande de document par ID
        $documentRequest = DocumentRequest::findOrFail($id);
        $oldComment = $documentRequest->comment;

        $documentRequest->comment = $request->input('comment') ?? null;
        $documentRequest->save();

        $this->activityLogger->log(
            'commented',
            "Ajout/Modification d'un commentaire sur la demande de document #{$id}",
            $documentRequest
        );

        return response()->json([
            'message' => "Commentaire ajouté à la demande de {$documentRequest->document_type->label}",
            'ok' => true,
        ]);

    }

    /**
     * Annuler une demande de document
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {

        // Rechercher la demande de document par ID
        $documentRequest = DocumentRequest::findOrFail($id);
        $oldStage = $documentRequest->stage;

        if ($documentRequest->level != 'ZERO') {
            $this->activityLogger->log(
                'denied',
                "Tentative d'annulation d'une demande de document #{$id} non annulable",
                $documentRequest
            );

            return response()->json([
                'ok' => false,
                'message' => "Vous ne pouvez plus annuler cette demande de {$documentRequest->document_type->label}.",
            ], 403);
        }

        $documentRequest->stage = 'CANCELLED';
        $documentRequest->save();

        $this->activityLogger->log(
            'cancelled',
            "Annulation de la demande de document #{$id} - Stage: {$oldStage} → {$documentRequest->stage}",
            $documentRequest
        );

        return response()->json([
            'message' => "Demande de {$documentRequest->document_type->label} annulée",
            'ok' => true,
        ]);

    }
}
