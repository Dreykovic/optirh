<?php

namespace App\Http\Controllers;

use App\Models\AbsenceType;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AbsenceTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:configurer-une-absence|voir-un-tout'], ['only' => ['index']]);
        $this->middleware(['permission:configurer-une-absence|créer-un-tout'], ['only' => ['store', 'update', 'create']]);
        // $this->middleware(['permission:écrire-une-absence|écrire-un-tout'], ['only' => ['approve', 'reject', 'comment']]);
        $this->middleware(['permission:configurer-une-absence|écrire-un-tout'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $absenceTypes = AbsenceType::all();

            return view('pages.admin.attendances.types.index', compact('absenceTypes'));
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
            // Valider les fichiers et l'image
            $request->validate([
                'libelle' => 'required|string',
                'description' => 'sometimes',
                'type' => 'sometimes',
            ]);

            AbsenceType::create([
                'label' => $request->input('libelle'),
                'description' => $request->input('description'),
                'type' => $request->input('type') ?? 'NORMAL',
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
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AbsenceType $absenceType)
    {
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
                'type' => 'sometimes',
            ]);
            $absenceType = AbsenceType::find($absenceTypeId);
            $absenceType->label = $request->input('libelle');
            $absenceType->description = $request->input('description');
            $absenceType->type = $request->input('type') ?? $absenceType->type;

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
    public function destroy($id)
    {
        try {
            \DB::table('absence_types')->where('id', $id)->delete();

            return response()->json(['ok' => true, 'message' => 'Le type d\absence a été retiré avec succès.']);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()]);
            // return response()->json(['ok' => false, 'message' => 'Une erreur s\'est produite. Veuillez réessayer.']);
        }
    }
}
