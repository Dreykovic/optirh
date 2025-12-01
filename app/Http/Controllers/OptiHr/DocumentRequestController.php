<?php

namespace App\Http\Controllers\OptiHr;

use App\Http\Controllers\Controller;
use App\Mail\DocumentRequestCreated;
use App\Mail\DocumentRequestStatus;
use App\Models\OptiHr\DocumentRequest;
use App\Models\OptiHr\DocumentType;
use App\Models\OptiHr\Duty;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\DocumentPdfService;
use App\Traits\SendsEmails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DocumentRequestController extends Controller
{
    use SendsEmails;

    /**
     * Le service de journalisation des activités
     *
     * @var ActivityLogService
     */
    protected $activityLogger;

    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;

        $this->middleware(['permission:voir-un-document|écrire-un-document|créer-un-document|configurer-un-document|voir-un-tout'], ['only' => ['index', 'download']]);
        $this->middleware(['permission:créer-un-document|créer-un-tout'], ['only' => ['store', 'cancel', 'create']]);
    }

    /**
     * Télécharger le PDF d'une demande de document
     *
     * @param  int  $documentRequestId  L'identifiant de la demande de document
     * @return mixed
     */
    public function download($documentRequestId)
    {
        try {
            $documentRequest = DocumentRequest::findOrFail($documentRequestId);
            $documentPdf = new DocumentPdfService;

            $this->activityLogger->log(
                'download',
                "Téléchargement du PDF de demande de document #{$documentRequest->id}",
                $documentRequest
            );

            return $documentPdf->generate($documentRequest);

        } catch (\Exception $e) {
            $this->activityLogger->log(
                'error',
                'Erreur lors du téléchargement du PDF: '.$e->getMessage()
            );

            return back()->with('error', 'Impossible de générer le document. '.$e->getMessage());
        }
    }

    /**
     * Afficher la liste des demandes de documents filtrée par étape
     *
     * @param  string  $stage
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $stage = 'TO_PROCESS')
    {
        // Liste des stages valides (incluant les stages virtuels)
        $validStages = ['PENDING', 'APPROVED', 'REJECTED', 'CANCELLED', 'IN_PROGRESS', 'TO_PROCESS', 'FINISHED'];

        // Vérification de la validité du stage
        if ($stage !== 'ALL' && ! in_array($stage, $validStages)) {
            $this->activityLogger->log(
                'error',
                "Tentative d'accès aux demandes de documents avec un stage invalide: {$stage}"
            );

            return redirect()->route('documents.requests', 'TO_PROCESS')->with('error', 'Stage invalide');
        }

        // Récupérer les filtres de recherche
        $type = $request->input('type');
        $search = $request->input('search');

        // Récupérer les types de document
        $document_types = DocumentType::all();

        // Récupérer les compteurs pour les badges
        $counts = $this->getRequestCounts();

        // Construire la requête principale
        $query = $this->buildDocumentRequestQuery($search, $type, $stage);

        // Pagination adaptative selon le type de stage
        $documentRequests = $this->getPaginatedResults($query, $stage);

        $this->activityLogger->log(
            'view',
            "Consultation de la liste des demandes de documents - Stage: {$stage}".
            ($type ? ", Type: {$type}" : '').
            ($search ? ", Recherche: {$search}" : '')
        );

        // Retourner la vue avec les données nécessaires
        return view('modules.opti-hr.pages.documents.main.index', compact('documentRequests', 'stage', 'document_types', 'counts'));
    }

    /**
     * Construire la requête de demandes de documents avec filtres
     *
     * @param  string|null  $search
     * @param  string|null  $type
     * @param  string  $stage
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildDocumentRequestQuery($search, $type, $stage)
    {
        // Construire la requête principale avec les relations nécessaires
        $query = DocumentRequest::with(['document_type', 'duty', 'duty.employee']);

        // Appliquer le filtre de recherche
        $query->when($search, function ($q) use ($search) {
            $q->whereHas('duty.employee', function ($query) use ($search) {
                $query->where('first_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$search.'%');
            });
        });

        // Trier par date de demande (plus récent en premier)
        $query->orderByDesc('date_of_application');

        // Filtrer par type de document, si précisé
        $query->when($type, function ($q) use ($type) {
            $q->where('document_type_id', $type);
        });

        // Filtrer par stage (gérer les stages virtuels)
        if ($stage === 'TO_PROCESS') {
            // À traiter = En attente + En cours
            $query->whereIn('stage', ['PENDING', 'IN_PROGRESS']);
        } elseif ($stage === 'FINISHED') {
            // Terminées = Approuvées + Rejetées
            $query->whereIn('stage', ['APPROVED', 'REJECTED']);
        } elseif ($stage !== 'ALL') {
            // Stage simple
            $query->where('stage', $stage);
        }

        return $query;
    }

    /**
     * Récupérer les compteurs pour les badges de navigation
     */
    private function getRequestCounts(): array
    {
        return [
            'all' => DocumentRequest::count(),
            'to_process' => DocumentRequest::whereIn('stage', ['PENDING', 'IN_PROGRESS'])->count(),
            'finished' => DocumentRequest::whereIn('stage', ['APPROVED', 'REJECTED'])->count(),
        ];
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
        // Appliquer la pagination pour les stages à traiter
        return (in_array($stage, ['PENDING', 'IN_PROGRESS', 'TO_PROCESS']))
            ? $query->paginate(10)
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
            'Accès au formulaire de création de demande de document'
        );

        return view('modules.opti-hr.pages.documents.main.create', compact('documentTypes'));

    }

    /**
     * Enregistrer une nouvelle demande de document
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Récupération du type de document
        $document_type_id = $request->input('document_type');
        $documentType = DocumentType::findOrFail($document_type_id);

        // Définir les règles de validation en fonction du type de document
        $rules = [
            'document_type' => 'required|exists:document_types,id',
        ];

        // Ajouter les règles de validation pour les dates sauf si type EXCEPTIONAL
        if ($documentType->type !== 'EXCEPTIONAL') {
            $rules['start_date'] = 'required|date|before_or_equal:end_date';
            $rules['end_date'] = 'required|date|after_or_equal:start_date';
        }

        // Validation des champs avec les règles dynamiques
        $validatedData = $request->validate($rules);

        // Récupération de l'employé actuel et de sa mission en cours
        $currentUser = User::with('employee')->findOrFail(auth()->id());
        $currentEmployee = $currentUser->employee;

        $currentEmployeeDuty = Duty::where('evolution', 'ON_GOING')
            ->where('employee_id', $currentEmployee->id)
            ->firstOrFail();

        // Préparation des données pour la création
        $documentRequestData = [
            'duty_id' => $currentEmployeeDuty->id,
            'document_type_id' => $document_type_id,
        ];

        // Gestion des dates selon le type
        if ($documentType->type === 'EXCEPTIONAL') {
            // Vérifier que la date de début de fonction existe
            if (! $currentEmployeeDuty->begin_date) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Impossible de créer cette demande : la date de début de fonction n\'est pas définie.',
                ], 422);
            }

            // Pour les types EXCEPTIONALs, utiliser les dates du duty actuel
            $documentRequestData['start_date'] = $currentEmployeeDuty->begin_date;

            // Calculer la date de fin pour les documents EXCEPTIONALs
            $today = now();

            if ($currentEmployeeDuty->duration) {
                // Calculer la date de fin théorique du duty
                $dutyEndDate = Carbon::parse($currentEmployeeDuty->begin_date)
                    ->addMonths($currentEmployeeDuty->duration);

                // Si la date du jour dépasse la fin théorique du duty, on prend la date de fin du duty
                // Sinon, on prend la date du jour
                $documentRequestData['end_date'] = $today->gt($dutyEndDate) ? $dutyEndDate : $today;
            } else {
                // Si pas de durée spécifiée, utiliser la date actuelle
                $documentRequestData['end_date'] = $today;
            }
        } else {
            // Pour les types normaux, utiliser les dates fournies
            $documentRequestData['start_date'] = $validatedData['start_date'];
            $documentRequestData['end_date'] = $validatedData['end_date'];
        }

        // Enregistrement de la demande de document
        $documentRequest = DocumentRequest::create($documentRequestData);

        // Notification au responsable RH
        $receiver = User::role('GRH')->first();
        if ($receiver) {
            $this->notifyApprover($documentRequest, $receiver);
        } else {
            $this->activityLogger->log(
                'warning',
                "Aucun utilisateur avec le rôle GRH trouvé pour la notification de la demande de document #{$documentRequest->id}",
                $documentRequest
            );
        }

        // Journalisation de l'activité
        $this->activityLogger->log(
            'created',
            "Création d'une demande de document de type {$documentType->label}",
            $documentRequest
        );

        $documentTypeLabel = $documentType->label ?? 'document';

        return response()->json([
            'message' => "Demande de {$documentTypeLabel} créée avec succès.",
            'ok' => true,
            'redirect' => route('documents.requests', 'PENDING'),
        ]);
    }

    /**
     * Met à jour le stage et le level d'une demande de document
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
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve($id)
    {

        // Rechercher la demande de document par ID
        $documentRequest = DocumentRequest::findOrFail($id);
        $oldStage = $documentRequest->stage;
        $oldLevel = $documentRequest->level;

        // Mise à jour du niveau et du statut
        $documentRequest->updateLevelAndStage();
        $receiver = User::role('GRH')->first();
        $toEmployee = false;

        switch ($documentRequest->level) {

            case 'ONE':

                $receiver = User::role('DG')->first();
                break;

            case 'TWO':

                $toEmployee = true;
                break;

            default:

                break;
        }
        if (! $documentRequest->date_of_application instanceof Carbon) {
            $documentRequest->date_of_application = Carbon::parse($documentRequest->date_of_application);
        }

        if ($toEmployee) {

            $this->notifyRequestor($documentRequest);

        } else {
            $this->notifyApprover($documentRequest, $receiver);

        }

        $this->activityLogger->log(
            'approved',
            "Approbation de la demande de document #{$id} - Stage: {$oldStage} → {$documentRequest->stage}, Level: {$oldLevel} → {$documentRequest->level}",
            $documentRequest
        );

        return response()->json([
            'message' => 'Demande de document acceptée',
            'ok' => true,
            'documentRequest' => $documentRequest,
        ]);

    }

    /**
     * Rejeter une demande de document
     *
     * @param  int  $id
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

        if ($documentRequest->stage == 'REJECTED') {
            // code...
            $this->notifyRequestor($documentRequest);
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

    // Dans votre controller ou service
    public function notifyRequestor(DocumentRequest $documentRequest)
    {
        try {
            $status = $documentRequest->stage === 'APPROVED' ? 'approuvée' : 'refusée';
            $url = route('documents.requests', $documentRequest->stage === 'APPROVED' ? 'APPROVED' : 'REJECTED');
            if (! $documentRequest->date_of_application instanceof Carbon) {
                $documentRequest->date_of_application = Carbon::parse($documentRequest->date_of_application);
            }

            // Récupérer l'utilisateur qui a fait la demande
            $requestor = $documentRequest->duty->employee->users->first();

            // Vérifier que le demandeur a une adresse email valide
            if (! $requestor || ! $requestor->email) {
                Log::debug("Impossible d'envoyer l'email pour la demande de document #{$documentRequest->id}: demandeur sans email");

                return;
            }

            // Créer et envoyer le mail de manière sécurisée
            $mailable = new DocumentRequestStatus(
                receiver: $requestor,
                documentRequest: $documentRequest,
                status: $status,
                url: $url
            );

            $sent = $this->sendEmail($mailable, true);

            if ($sent) {
                Log::debug("Email de statut envoyé pour la demande de document #{$documentRequest->id}", [
                    'to' => $requestor->email,
                    'status' => $status,
                ]);
            } else {
                Log::debug("Échec de l'envoi d'email pour la demande de document #{$documentRequest->id}");
            }
        } catch (\Exception $e) {
            Log::debug("Erreur lors de l'envoi de notification pour la demande de document #{$documentRequest->id}: ".$e->getMessage());
        }
    }

    // Dans votre controller ou service
    public function notifyApprover(DocumentRequest $documentRequest, User $approver)
    {
        try {
            // Vérifier que l'approbateur a une adresse email valide
            if (! $approver || ! $approver->email) {
                Log::debug("Impossible d'envoyer l'email à l'approbateur pour la demande de document #{$documentRequest->id}: email manquant");

                return;
            }

            $url = route('documents.requests', 'IN_PROGRESS');
            $mailable = new DocumentRequestCreated(
                receiver: $approver,
                documentRequest: $documentRequest,
                url: $url
            );

            $sent = $this->sendEmail($mailable, true);

            if ($sent) {
                Log::debug("Email envoyé à l'approbateur pour la demande de document #{$documentRequest->id}", [
                    'to' => $approver->email,
                ]);
            } else {
                Log::debug("Échec de l'envoi d'email à l'approbateur pour la demande de document #{$documentRequest->id}");
            }
        } catch (\Exception $e) {
            Log::debug("Erreur lors de l'envoi à l'approbateur pour la demande de document #{$documentRequest->id}: ".$e->getMessage());
        }
    }

    /**
     * Ajouter un commentaire à une demande de document
     *
     * @param  int  $id
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
     * @param  int  $id
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
