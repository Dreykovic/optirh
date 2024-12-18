<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\AbsenceType;
use Illuminate\Http\Request;

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
                    $query->where('first_name', 'ILIKE', '%' . $search . '%')
                          ->orWhere('last_name', 'ILIKE', '%' . $search . '%');
                });
            });


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
            dd('Erreur lors du chargement des absences : ' . $th->getMessage());
            // Log propre de l'erreur et affichage d'un message utilisateur
            \Log::error('Erreur lors du chargement des absences : ' . $th->getMessage());
            return back()->with('error', 'Une erreur s\'est produite lors du chargement des absences. Veuillez réessayer.');
        }
    }


    /**
     * ['PENDING', 'APPROVED', 'REJECTED', 'CANCELLED', 'IN_PROGRESS', 'COMPLETED']
     */

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Absence $absence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absence $absence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absence $absence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absence $absence)
    {
        //
    }
}
