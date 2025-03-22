<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\DocumentType;
use App\Models\Duty;
use App\Models\User;
use App\Services\ActivityLogger;
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
     * @var ActivityLogger
     */
    protected $activityLogger;

    public function __construct(ActivityLogger $activityLogger)
    {
        $this->activityLogger = $activityLogger;

        $this->middleware(['permission:voir-un-document|écrire-un-document|créer-un-document|configurer-un-document|voir-un-tout'], ['only' => ['index']]);
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
        try {
            $documentRequest = DocumentRequest::findOrFail($documentRequestId);
            $documentPdf = new DocumentPdfService();

            $this->activityLogger->log(
                'download',
                "Téléchargement du PDF de demande de document #{$documentRequest->id}",
                $documentRequest
            );

            return $documentPdf->generate($documentRequest);
        } catch (\Throwable $th) {
            Log::error('Erreur lors du téléchargement du PDF de demande de document', [
                'document_request_id' => $documentRequestId,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            $this->activityLogger->log(
                'error',
                "Échec du téléchargement du PDF de demande de document #{$documentRequestId}"
            );

            return back()->with('error', 'Une erreur s\'est produite lors du téléchargement du PDF. Veuillez réessayer.');
        }
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
        try {
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
            return view('pages.admin.documents.main.index', compact('documentRequests', 'stage', 'document_types'));
        } catch (\Throwable $th) {
            Log::error('Erreur lors du chargement des demandes de documents', [
                'stage' => $stage,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            $this->activityLogger->log(
                'error',
                "Erreur lors du chargement des demandes de documents - Stage: {$stage}"
            );

            return back()->with('error', 'Une erreur s\'est produite lors du chargement des demandes de documents. Veuillez réessayer.');
        }
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
                $query->where('first_name', 'ILIKE', '%'.$search.'%')
                      ->orWhere('last_name', 'ILIKE', '%'.$search.'%');
            });
        });

        // Trier par date de demande
        $query->orderBy('date_of_application');

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
        try {
            $documentTypes = DocumentType::all();

            $this->activityLogger->log(
                'access',
                "Accès au formulaire de création de demande de document"
            );

            return view('pages.admin.documents.main.create', compact('documentTypes'));
        } catch (\Throwable $th) {
            Log::error('Erreur lors du chargement du formulaire de création de demande de document', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            $this->activityLogger->log(
                'error',
                "Erreur lors de l'accès au formulaire de création de demande de document"
            );

            return back()->with('error', 'Une erreur s\'est produite lors du chargement des types de document.');
        }
    }

    /**
     * Enregistrer une nouvelle demande de document
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
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
        } catch (ValidationException $e) {
            Log::warning('Erreur de validation lors de la création d\'une demande de document', [
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            Log::warning('Entité non trouvée lors de la création d\'une demande de document', [
                'error' => $e->getMessage(),
            ]);

            $this->activityLogger->log(
                'error',
                "Échec de création d'une demande de document - Entité non trouvée"
            );

            return response()->json([
                'ok' => false,
                'message' => 'Données introuvables. Veuillez vérifier les entrées.',
                'errors' => $e->getMessage(),
            ], 404);
        } catch (\Throwable $th) {
            Log::error('Erreur lors de la création d\'une demande de document', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
                'inputs' => $request->except(['_token']),
            ]);

            $this->activityLogger->log(
                'error',
                "Échec de création d'une demande de document - Erreur serveur"
            );

            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s\'est produite lors de la création de la demande de document.',
                'error' => $th->getMessage(),
            ], 500);
        }
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
        try {
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
        } catch (ValidationException $e) {
            Log::warning('Erreur de validation lors de la mise à jour du statut d\'une demande de document', [
                'document_request_id' => $id,
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            Log::warning('Demande de document non trouvée lors de la mise à jour du statut', [
                'document_request_id' => $id,
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Demande de document introuvable. Veuillez vérifier l\'identifiant.',
            ], 404);
        } catch (\Throwable $th) {
            Log::error('Erreur lors de la mise à jour du statut d\'une demande de document', [
                'document_request_id' => $id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s\'est produite. Veuillez réessayer.',
                'error' => $th->getMessage(),
            ], 500);
        }
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

        try {
            // Rechercher la demande de document par ID
            $documentRequest = DocumentRequest::findOrFail($id);
            $oldStage = $documentRequest->stage;
            $oldLevel = $documentRequest->level;

            // Mise à jour du niveau et du statut
            $documentRequest->updateLevelAndStage();

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
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Erreur de validation lors de l\'approbation d\'une demande de document', [
                'document_request_id' => $id,
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::warning('Demande de document non trouvée lors de l\'approbation', [
                'document_request_id' => $id,
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Demande de document introuvable. Veuillez vérifier l\'identifiant.',
            ], 404);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Erreur lors de l\'approbation d\'une demande de document', [
                'document_request_id' => $id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s\'est produite. Veuillez réessayer.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Rejeter une demande de document
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject($id)
    {
        try {
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

            $this->activityLogger->log(
                'rejected',
                "Rejet de la demande de document #{$id} - Stage: {$oldStage} → {$documentRequest->stage}, Level: {$oldLevel} → {$documentRequest->level}",
                $documentRequest
            );

            return response()->json([
                'message' => "Demande de {$documentRequest->document_type->label} rejetée",
                'ok' => true,
            ]);
        } catch (ValidationException $e) {
            Log::warning('Erreur de validation lors du rejet d\'une demande de document', [
                'document_request_id' => $id,
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            Log::warning('Demande de document non trouvée lors du rejet', [
                'document_request_id' => $id,
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Demande de document introuvable. Veuillez vérifier l\'identifiant.',
            ], 404);
        } catch (\Throwable $th) {
            Log::error('Erreur lors du rejet d\'une demande de document', [
                'document_request_id' => $id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s\'est produite. Veuillez réessayer.',
                'error' => $th->getMessage(),
            ], 500);
        }
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
        try {
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
        } catch (ValidationException $e) {
            Log::warning('Erreur de validation lors de l\'ajout d\'un commentaire à une demande de document', [
                'document_request_id' => $id,
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            Log::warning('Demande de document non trouvée lors de l\'ajout d\'un commentaire', [
                'document_request_id' => $id,
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Demande de document introuvable. Veuillez vérifier l\'identifiant.',
            ], 404);
        } catch (\Throwable $th) {
            Log::error('Erreur lors de l\'ajout d\'un commentaire à une demande de document', [
                'document_request_id' => $id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s\'est produite. Veuillez réessayer.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Annuler une demande de document
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        try {
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
        } catch (ValidationException $e) {
            Log::warning('Erreur de validation lors de l\'annulation d\'une demande de document', [
                'document_request_id' => $id,
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            Log::warning('Demande de document non trouvée lors de l\'annulation', [
                'document_request_id' => $id,
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Demande de document introuvable. Veuillez vérifier l\'identifiant.',
            ], 404);
        } catch (\Throwable $th) {
            Log::error('Erreur lors de l\'annulation d\'une demande de document', [
                'document_request_id' => $id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s\'est produite. Veuillez réessayer.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
