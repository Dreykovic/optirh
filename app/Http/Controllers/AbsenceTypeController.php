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

        $this->middleware(['permission:configurer-une-absence|écrire-un-tout'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $absenceTypes = AbsenceType::all();

        return view('pages.admin.attendances.types.index', compact('absenceTypes'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

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

    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $absenceTypeId)
    {

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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        \DB::table('absence_types')->where('id', $id)->delete();

        return response()->json(['ok' => true, 'message' => 'Le type d\absence a été retiré avec succès.']);

    }
}
