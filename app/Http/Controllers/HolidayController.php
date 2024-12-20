<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Holiday;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $holidays = Holiday::all();

            return view('pages.admin.attendances.holidays.index', compact('holidays'));
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Valider les entrées
            $validatedData = $request->validate([
                'name' => 'required|string',
                'date' => 'required|date',
            ]);

            // Rechercher l'absence par ID
            $holiday = new Holiday();

            // Mettre à jour les champs stage et level
            $holiday->date = $validatedData['date'];
            $holiday->name = $validatedData['name'];

            // Sauvegarder les modifications
            $holiday->save();

            return response()->json([
                'message' => 'Jour fériéAjouté avec succès.',
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
     * Display the specified resource.
     */
    public function show(Holiday $holiday)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Holiday $holiday)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Valider les entrées
            $validatedData = $request->validate([
                'name' => 'required|string',
                'date' => 'required|date',
            ]);

            // Rechercher l'absence par ID
            $holiday = Holiday::findOrFail($id);

            // Mettre à jour les champs stage et level
            $holiday->date = $validatedData['date'];
            $holiday->name = $validatedData['name'];

            // Sauvegarder les modifications
            $holiday->save();

            return response()->json([
                'message' => 'Jour férié a été mis à jour avec succès.',
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            \DB::table('holidays')->where('id', $id)->delete();

            return response()->json(['ok' => true, 'message' => 'Le jour fériée a été retiré avec succès.']);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()]);
            // return response()->json(['ok' => false, 'message' => 'Une erreur s\'est produite. Veuillez réessayer.']);
        }
    }
}
