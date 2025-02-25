<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\DocumentRequest;
use App\Models\DocumentType;
use App\Models\Duty;
use App\Models\User;
use App\Services\DocumentPdfService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DocumentRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:voir-un-document|écrire-un-document|créer-un-document|configurer-un-document|voir-un-tout'], ['only' => ['index']]);
        $this->middleware(['permission:créer-un-document|créer-un-tout'], ['only' => ['store', 'cancel', 'create']]);
    }

    public function download($absenceId)
    {
        try {
            $documentRequest = DocumentRequest::findOrFail($absenceId);
            $documentPdf = new DocumentPdfService();

            return $documentPdf->generate($documentRequest);
        } catch (\Throwable $th) {
            dd('Erreur lors du chargement des absences : '.$th->getMessage());
            // Log propre de l'erreur et affichage d'un message utilisateur
            \Log::error('Erreur lors du chargement des absences : '.$th->getMessage());

            return back()->with('error', 'Une erreur s\'est produite lors du chargement des absences. Veuillez réessayer.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $stage = 'PENDING')
    {
        try {
            // Liste des stages valides
            $validStages = ['PENDING', 'APPROVED', 'REJECTED', 'CANCELLED', 'IN_PROGRESS', 'COMPLETED'];

            // Vérification de la validité du stage
            if ($stage !== 'ALL' && !in_array($stage, $validStages)) {
                return redirect()->route('documents.requests')->with('error', 'Stage invalide');
            }

            // Récupérer les filtres de recherche
            $type = $request->input('type');
            $search = $request->input('search');

            // Récupérer les types de document (éviter de faire la requête à chaque appel)
            $document_types = DocumentType::all();

            // Construire la requête principale avec les relations nécessaires
            $query = DocumentRequest::with(['document_type', 'duty', 'duty.employee']);

            // Appliquer le filtre de recherche (groupe de conditions OR)
            $query->when($search, function ($q) use ($search) {
                $q->whereHas('duty.employee', function ($query) use ($search) {
                    $query->where('first_name', 'ILIKE', '%'.$search.'%')
                          ->orWhere('last_name', 'ILIKE', '%'.$search.'%');
                });
            });
            $query->orderBy('date_of_application');

            // Filtrer par type de document, si précisé
            $query->when($type, function ($q) use ($type) {
                $q->where('document_type_id', $type);
            });

            // Filtrer par stage si le stage n'est pas "ALL"
            $query->when($stage !== 'ALL', function ($q) use ($stage) {
                $q->where('stage', $stage);
            });
            // $limit = in_array($stage, ['PENDING', 'IN_PROGRESS']) ? 2 : 10;
            // Appliquer la pagination seulement si on filtre par stage (sauf ALL)
            $documentRequests = (in_array($stage, ['PENDING', 'IN_PROGRESS']))
                ? $query->paginate(2)
                : $query->get();

            // Retourner la vue avec les données nécessaires
            return view('pages.admin.documents.main.index', compact('documentRequests', 'stage', 'document_types'));
        } catch (\Throwable $th) {
            dd('Erreur lors du chargement des demandes de documents : '.$th->getMessage());
            // Log propre de l'erreur et affichage d'un message utilisateur
            \Log::error('Erreur lors du chargement des demandes de documents : '.$th->getMessage());

            return back()->with('error', 'Une erreur s\'est produite lors du chargement des demandes de documents. Veuillez réessayer.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $documentTypes = DocumentType::all();

            return view('pages.admin.documents.main.create', compact('documentTypes'));
        } catch (\Throwable $th) {
            dd($th->getMessage());

            // Gestion des erreurs avec un message d'erreur plus propre
            return back()->with('error', 'Une erreur s\'est produite lors du chargement des types de document.');
            // abort(500);
        }
    }

    /**
     * Enregistre une demande d'absence.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validation des champs de la requête
            $validatedData = $request->validate([
                'document_type' => 'required|exists:absence_types,id',

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

            // Enregistrement de la demande d'absence
            $documentRequest = DocumentRequest::create([
                'duty_id' => $currentEmployeeDuty->id,
                'document_type_id' => $document_type_id,

                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
            ]);

            $var = $documentRequest->document_type() ? $documentRequest->document_type->label : '';

            return response()->json([
                'message' => "Demande de {$var}  créée avec succès.",
                'ok' => true,
            ]);
        } catch (ValidationException $e) {
            // Gestion des erreurs de validation
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            // Gestion des cas où le modèle n'est pas trouvé
            return response()->json([
                'ok' => false,
                'message' => 'Données introuvables. Veuillez vérifier les entrées.',
                'errors' => $e->getMessage(),
            ], 404);
        } catch (\Throwable $th) {
            // Gestion générale des erreurs
            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s’est produite. Veuillez réessayer.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Absence $absence)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absence $absence)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absence $absence)
    {
    }

    /**
     * Met à jour le stage et le level d'une absence.
     *
     * @param int $id L'ID de l'absence à mettre à jour
     *
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

            // Rechercher l'absence par ID
            $absence = Absence::find($id);

            if (!$absence) {
                return response()->json([
                    'message' => 'Absence not found.',
                ], 404);
            }

            // Mettre à jour les champs stage et level
            $absence->stage = $validatedData['stage'];
            $absence->level = $validatedData['level'];

            // Sauvegarder les modifications
            $absence->save();

            return response()->json([
                'message' => 'Stage and level updated successfully.',
                'ok' => true,
            ]);
        } catch (ValidationException $e) {
            // Gestion des erreurs de validation
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            // Gestion des cas où le modèle n'est pas trouvé
            return response()->json([
                'ok' => false,
                'message' => 'Données introuvables. Veuillez vérifier les entrées.',
            ], 404);
        } catch (\Throwable $th) {
            // Gestion générale des erreurs
            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s’est produite. Veuillez réessayer.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function approve($id)
    {
        try {
            // Rechercher l'absence par ID
            $documentRequest = DocumentRequest::findOrFail($id);

            // Mise à jour du niveau et du statut
            $documentRequest->updateLevelAndStage();

            return $this->successResponse(
                'Demande de document acceptée',
                ['absence' => $documentRequest]
            );
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e);
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Throwable $th) {
            return $this->generalErrorResponse($th);
        }
    }

    public function reject($id)
    {
        try {
            // Rechercher l'absence par ID
            $documentRequest = DocumentRequest::findOrFail($id);
            if (!in_array($documentRequest->level, ['TWO', 'THREE', 'FOUR'])) {
                // Sauvegarder les modifications
            }

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

            return response()->json([
                'message' => "Demande De {$documentRequest->document_type->label} rejeté",

                'ok' => true,
            ]);
        } catch (ValidationException $e) {
            // Gestion des erreurs de validation
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            // Gestion des cas où le modèle n'est pas trouvé
            return response()->json([
                'ok' => false,
                'message' => 'Données introuvables. Veuillez vérifier les entrées.',
            ], 404);
        } catch (\Throwable $th) {
            // Gestion générale des erreurs
            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s’est produite. Veuillez réessayer.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function comment(Request $request, $id)
    {
        try {
            // Valider les entrées
            $request->validate([
                'comment' => 'sometimes',
            ]);
            // Rechercher l'absence par ID
            $documentRequest = DocumentRequest::findOrFail($id);

            $documentRequest->comment = $request->input('comment') ?? null;

            $documentRequest->save();

            return response()->json([
                'message' => "Demande De {$documentRequest->document_type->label} rejeté",

                'ok' => true,
            ]);
        } catch (ValidationException $e) {
            // Gestion des erreurs de validation
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            // Gestion des cas où le modèle n'est pas trouvé
            return response()->json([
                'ok' => false,
                'message' => 'Données introuvables. Veuillez vérifier les entrées.',
            ], 404);
        } catch (\Throwable $th) {
            // Gestion générale des erreurs
            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s’est produite. Veuillez réessayer.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function cancel($id)
    {
        try {
            // Rechercher l'absence par ID
            $documentRequest = DocumentRequest::findOrFail($id);
            if ($documentRequest->level != 'ZERO') {
                return response()->json([
                    'ok' => false,
                    'message' => "Vous ne pouvez plus annulé cette demande de {$documentRequest->document_type->label}.",
                ], 403);
            }
            $documentRequest->stage = 'CANCELLED';

            $documentRequest->save();

            return response()->json([
                'message' => "Demande De {$documentRequest->document_type->label} rejeté",

                'ok' => true,
            ]);
        } catch (ValidationException $e) {
            // Gestion des erreurs de validation
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            // Gestion des cas où le modèle n'est pas trouvé
            return response()->json([
                'ok' => false,
                'message' => 'Données introuvables. Veuillez vérifier les entrées.',
            ], 404);
        } catch (\Throwable $th) {
            // Gestion générale des erreurs
            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s’est produite. Veuillez réessayer. '.$th->getMessage(),
                // 'error' => $th->getMessage(),
            ], 500);
        }
    }
}
