<?php

namespace App\Http\Controllers;

use App\Models\AnnualDecision;
use Illuminate\Http\Request;


use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Validation\ValidationException;

class AnnualDecisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

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
    public function storeOrUpdate(Request $request, $id = null)
    {
        try {
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

    public function show()
    {
        try {
            $decision = AnnualDecision::where('state', 'current')->first();

            return view('pages.admin.attendances.annual-decisions.index', compact('decision'));
        } catch (\Throwable $th) {
            dd($th->getMessage());

            // Gestion des erreurs avec un message d'erreur plus propre
            return back()->with('error', 'Une erreur s\'est produite lors du chargement des types d\'absence.');
            // abort(500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AnnualDecision $annualDecision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AnnualDecision $annualDecision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AnnualDecision $annualDecision)
    {
        //
    }
}
