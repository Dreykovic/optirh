<?php

namespace App\Http\Controllers\OptiHr;

use App\Http\Controllers\Controller;
use App\Models\OptiHr\AnnualDecision;
use Illuminate\Http\Request;


use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Validation\ValidationException;

class AnnualDecisionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function storeOrUpdate(Request $request, $id = null)
    {

        // Validation des données
        $validatedData = $request->validate([
            'number' => 'required|string|max:255',
            'year' => 'required|string|max:4',
            'reference' => 'nullable|string|max:255',
            'date' => 'required|date',
            'pdf' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Gestion du fichier PDF
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('decisions', 'public');
            $validatedData['pdf'] = $pdfPath;
        }

        // Création ou mise à jour de la décision

        AnnualDecision::updateOrCreate(
            ['id' => $id],  // Condition de mise à jour
            $validatedData   // Données mises à jour ou créées
        );

        return response()->json([
            'message' => $id ? 'Decision updated successfully' : 'Decision created successfully',
            'ok' => true,
        ]);

    }

    public function show()
    {
        try {
            $decision = AnnualDecision::where('state', 'current')->first();

            return view('pages.admin.opti-hr.attendances.annual-decisions.index', compact('decision'));
        } catch (\Throwable $th) {
            dd($th->getMessage());

            // Gestion des erreurs avec un message d'erreur plus propre
            return back()->with('error', 'Une erreur s\'est produite lors du chargement des types d\'absence.');
            // abort(500);
        }
    }



}
