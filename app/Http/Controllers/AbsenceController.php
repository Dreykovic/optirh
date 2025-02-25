<?php

namespace App\Http\Controllers;

use App\Mail\AbsenceRequestCreated;
use App\Mail\AbsenceRequestUpdated;
use App\Models\Absence;
use App\Models\AbsenceType;
use App\Models\Decision;
use App\Models\Duty;
use App\Models\User;
use App\Services\AbsencePdfService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

// use Illuminate\Support\Facades\Mail;

class AbsenceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:voir-une-absence|écrire-une-absence|créer-une-absence|configurer-une-absence|voir-un-tout'], ['only' => ['index']]);
        $this->middleware(['permission:créer-une-absence|créer-un-tout'], ['only' => ['store', 'cancel', 'create']]);
        $this->middleware(['permission:écrire-une-absence|écrire-un-tout'], ['only' => ['approve', 'reject', 'comment']]);
        // $this->middleware(['permission:écrire-un-utilisateur|écrire-un-tout'], ['only' => ['destroy', 'destroyAll']]);
    }

    public function download($absenceId)
    {
        try {
            $absence = Absence::findOrFail($absenceId);
            $decision = Decision::where('state', 'current')->first();
            $absencePdf = new AbsencePdfService();

            return $absencePdf->generate($absence, $decision);
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
                return redirect()->route('absences.index')->with('error', 'Stage invalide');
            }

            // Récupérer les filtres de recherche
            $type = $request->input('type');
            $search = $request->input('search');

            // Récupérer les types d'absences (éviter de faire la requête à chaque appel)
            $absence_types = AbsenceType::all();

            // Construire la requête principale avec les relations nécessaires
            $query = Absence::with(['absence_type', 'duty', 'duty.employee']);

            // Appliquer le filtre de recherche (groupe de conditions OR)
            $query->when($search, function ($q) use ($search) {
                $q->whereHas('duty.employee', function ($query) use ($search) {
                    $query->where('first_name', 'ILIKE', '%'.$search.'%')
                          ->orWhere('last_name', 'ILIKE', '%'.$search.'%');
                });
            });
            $query->orderBy('date_of_application');

            // Filtrer par type d'absence, si précisé
            $query->when($type, function ($q) use ($type) {
                $q->where('absence_type_id', $type);
            });

            // Filtrer par stage si le stage n'est pas "ALL"
            $query->when($stage !== 'ALL', function ($q) use ($stage) {
                $q->where('stage', $stage);
            });
            // $limit = in_array($stage, ['PENDING', 'IN_PROGRESS']) ? 2 : 10;
            // Appliquer la pagination seulement si on filtre par stage (sauf ALL)
            $absences = (in_array($stage, ['PENDING', 'IN_PROGRESS']))
                ? $query->paginate(2)
                : $query->get();

            // Retourner la vue avec les données nécessaires
            return view('pages.admin.attendances.absences.index', compact('absences', 'stage', 'absence_types'));
        } catch (\Throwable $th) {
            dd('Erreur lors du chargement des absences : '.$th->getMessage());
            // Log propre de l'erreur et affichage d'un message utilisateur
            \Log::error('Erreur lors du chargement des absences : '.$th->getMessage());

            return back()->with('error', 'Une erreur s\'est produite lors du chargement des absences. Veuillez réessayer.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $absenceTypes = AbsenceType::all();

            return view('pages.admin.attendances.absences.create', compact('absenceTypes'));
        } catch (\Throwable $th) {
            dd($th->getMessage());

            // Gestion des erreurs avec un message d'erreur plus propre
            return back()->with('error', 'Une erreur s\'est produite lors du chargement des types d\'absence.');
            // abort(500);
        }
    }

    /**
     * Calculer le nombre de jours ouvrés entre deux dates sans inclure les week-ends.
     *
     * @param string $startDate
     * @param string $endDate
     *
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
     * Enregistre une demande d'absence.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validation des champs de la requête
            $validatedData = $request->validate([
                'absence_type' => 'required|exists:absence_types,id',
                'address' => 'required|string|max:255',
                'start_date' => 'required|date|before_or_equal:end_date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reasons' => 'nullable|string|max:1000',
            ]);

            // Calcul du nombre de jours d'absence
            $workingDays = calculateWorkingDays($validatedData['start_date'], $validatedData['end_date']);

            // Récupération de l'employé actuel et de sa mission en cours
            $currentUser = User::with('employee')->findOrFail(auth()->id());
            $currentEmployee = $currentUser->employee;

            $currentEmployeeDuty = Duty::where('evolution', 'ON_GOING')
                                        ->where('employee_id', $currentEmployee->id)
                                        ->firstOrFail();
            $absence_type_id = $request->input('absence_type');

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
            // $receiver = $absence->duty->job->n_plus_one_job ?
            // $absence->duty->job->n_plus_one_job->duties->firstWhere('evolution', 'ON_GOING')->employee->users->first() : User::role('GRH')->first();

            // Mail::send(new AbsenceRequestCreated($receiver, $absence, route('absences.requests')));
            // Redirection avec message de succès

            $var = $absence->absence_type ? $absence->absence_type->label : '';

            return response()->json([
                'message' => "Demande d\'absence {$var}  créée avec succès.",
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
                'message' => $th->getMessage(),
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Calcule le nombre de jours entre deux dates via une requête AJAX.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateDays(Request $request)
    {
        // Validation des dates
        $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Calcul du nombre de jours d'absence
        $workingDays = calculateWorkingDays($request->start_date, $request->end_date);

        return response()->json(['working_days' => $workingDays]);
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
            $absence = Absence::findOrFail($id);
            $receiver = User::role('GRH')->first();
            $toEmployee = false;
            // Mise à jour du niveau et du statut
            // $absence->updateLevelAndStage();

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

                    // Trouver le maximum actuel de absence_number de manière sécurisée
                    $maxAbsenceNumber = DB::table($absence->getTable())
                        ->whereNotNull('absence_number') // Filtrer les entrées valides
                        ->orderByDesc('absence_number') // Trier par ordre décroissant
                        ->lockForUpdate() // Verrouiller les lignes pour éviter les conflits
                        ->value('absence_number'); // Obtenir la valeur maximale

                    $absence->absence_number = $maxAbsenceNumber ? $maxAbsenceNumber + 1 : 1;
                    $absence->date_of_approval = new Carbon();
                    break;

                default:
                    $absence->stage = 'APPROVED';
                    $absence->level = 'THREE';
                    $toEmployee = true;

                    // Trouver le maximum actuel de absence_number de manière sécurisée
                    $maxAbsenceNumber = DB::table($absence->getTable())
                        ->whereNotNull('absence_number') // Filtrer les entrées valides
                        ->orderByDesc('absence_number') // Trier par ordre décroissant
                        ->lockForUpdate() // Verrouiller les lignes pour éviter les conflits
                        ->value('absence_number'); // Obtenir la valeur maximale

                    $absence->absence_number = $maxAbsenceNumber ? $maxAbsenceNumber + 1 : 1;
                    $absence->date_of_approval = new Carbon();

                    break;
            }

            // Sauvegarder les changements dans la transaction
            $absence->save();
            if ($toEmployee) {
                $url = route('absences.requests', 'ALL');
            // Mail::send(new AbsenceRequestUpdated($absence, $url));
            // code...
            } else {
                $url = route('absences.requests', 'IN_PROGRESS');
                // Mail::send(new AbsenceRequestCreated($receiver, $absence, $url));
            }

            return $this->successResponse(
                'Demande de congé acceptée',
                ['absence' => $absence]
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
            $absence = Absence::findOrFail($id);
            if (!in_array($absence->level, ['THREE', 'FOUR'])) {
                // Sauvegarder les modifications
            }

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

            return response()->json([
                'message' => "Demande De {$absence->absence_type->label} rejeté",

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
            $absence = Absence::findOrFail($id);

            $absence->comment = $request->input('comment') ?? null;

            $absence->save();

            return response()->json([
                'message' => "Demande De {$absence->absence_type->label} rejeté",

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
            $absence = Absence::findOrFail($id);
            if ($absence->level != 'ZERO') {
                return response()->json([
                    'ok' => false,
                    'message' => "Vous ne pouvez plus annulé cette demande de {$absence->absence_type->label}.",
                ], 403);
            }
            $absence->stage = 'CANCELLED';

            $absence->save();

            return response()->json([
                'message' => "Demande De {$absence->absence_type->label} rejeté",

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absence $absence)
    {
    }
}
