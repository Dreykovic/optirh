<?php

namespace App\Http\Controllers;

use App\Models\AbsenceType;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AbsenceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        try {

            $absenceTypes = AbsenceType::all();
            return view("pages.admin.attendances.types.index", compact('absenceTypes'));
        } catch (\Throwable $th) {
            dd($th->getMessage());
            // Gestion des erreurs avec un message d'erreur plus propre
            return back()->with('error', 'Une erreur s\'est produite lors du chargement des types d\'absence.');
            // abort(500);
        }
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
    public function store(Request $request)
    {
        try {
            // Valider les fichiers et l'image
            $request->validate([
                'libelle' => 'required|string',
                'description' => 'sometimes',

            ]);


            AbsenceType::create([
                'label' => $request->input('libelle'),
                'description' => $request->input('description'),

            ]);

            // Redirection avec message de succès
            return response()->json(['message' => 'Type Absence créé avec succès.', 'ok' => true]);
        } catch (ValidationException $e) {
            // Gestion des erreurs de validation
            // return response()->json(['ok' => false, 'errors' => $e->errors(), 'message' => 'Données invalides. Veuillez vérifier votre saisie.']);
            return response()->json(['ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Throwable $th) {
            // return response()->json(['ok' => false,  'message' => 'Une erreur s\'est produite. Veuillez réessayer.'], 500);

            return response()->json(['ok' => false,  'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AbsenceType $absenceType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AbsenceType $absenceType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $absenceTypeId)
    {
        try {
            // Valider les fichiers et l'image
            $request->validate([
                'libelle' => 'required|string',
                'description' => 'sometimes',

            ]);
            $absenceType = AbsenceType::find($absenceTypeId);
            $absenceType->label = $request->input('libelle');
            $absenceType->description = $request->input('description');

            $absenceType->save();


            // Redirection avec message de succès
            return response()->json(['message' => 'Type Absence mis à jour avec succès.', 'ok' => true]);
        } catch (ValidationException $e) {
            // Gestion des erreurs de validation
            // return response()->json(['ok' => false, 'errors' => $e->errors(), 'message' => 'Données invalides. Veuillez vérifier votre saisie.']);
            return response()->json(['ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Throwable $th) {
            // return response()->json(['ok' => false,  'message' => 'Une erreur s\'est produite. Veuillez réessayer.'], 500);

            return response()->json(['ok' => false,  'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AbsenceType $absenceType)
    {
        //
    }
}
