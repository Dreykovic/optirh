<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\AbsenceType;
use App\Models\Duty;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AbsenceController extends Controller
{
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

            // Appliquer la pagination seulement si on filtre par stage (sauf ALL)
            $absences = ($stage !== 'ALL')
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
            if (!$currentDate->isWeekend()) {
                ++$count;
            }
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
            $workingDays = $this->calculateWorkingDays($validatedData['start_date'], $validatedData['end_date']);

            // Récupération de l'employé actuel et de sa mission en cours
            $currentEmployee = Employee::findOrFail(auth()->id());
            $currentEmployeeDuty = Duty::where('evolution', 'ON_GOING')
                                        ->where('employee_id', $currentEmployee->id)
                                        ->firstOrFail();

            // Enregistrement de la demande d'absence
            Absence::create([
                'duty_id' => $currentEmployeeDuty->id,
                'absence_type_id' => $validatedData['absence_type'],
                'address' => $validatedData['address'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'reasons' => $validatedData['reasons'],
                'requested_days' => $workingDays,
            ]);

            // Redirection avec message de succès
            return response()->json([
                'message' => 'Demande d\'absence créée avec succès.',
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
        $workingDays = $this->calculateWorkingDays($request->start_date, $request->end_date);

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
     * Remove the specified resource from storage.
     */
    public function destroy(Absence $absence)
    {
    }
}
