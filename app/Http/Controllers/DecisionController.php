<?php

namespace App\Http\Controllers;

use App\Models\Decision;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DecisionController extends Controller
{
    /**
     * Store or update a decision.
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

        Decision::updateOrCreate(
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

        $decision = Decision::where('state', 'current')->first();

        return view('pages.admin.attendances.decisions.index', compact('decision'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Decision $decision)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Decision $decision)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Decision $decision)
    {
    }
}
