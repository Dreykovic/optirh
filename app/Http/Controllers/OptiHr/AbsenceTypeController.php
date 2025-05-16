<?php

namespace App\Http\Controllers\OptiHr;

use App\Http\Controllers\Controller;
use App\Models\OptiHr\AbsenceType;
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

        return view('modules.opti-hr.pages.attendances.types.index', compact('absenceTypes'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate all inputs in a single validation call
        $request->validate([
            'libelle' => 'required|string',
            'description' => 'sometimes',
            'type' => 'sometimes',
            'is_deductible' => 'sometimes|boolean',
        ]);

        // Create absence type only once
        AbsenceType::create([
            'label' => $request->input('libelle'),
            'description' => $request->input('description'),
            'type' => $request->input('type') ?? 'NORMAL',
            'is_deductible' => $request->input('is_deductible') ?? true,
        ]);

        // Return success response
        return response()->json(['message' => 'Type Absence créé avec succès.', 'ok' => true]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $absenceTypeId)
    {
        // Validate inputs
        $request->validate([
            'libelle' => 'required|string',
            'description' => 'sometimes',
            'type' => 'sometimes',
            'is_deductible' => 'sometimes|boolean',
        ]);

        // Find record or fail with 404
        $absenceType = AbsenceType::findOrFail($absenceTypeId);
        // Update record
        $absenceType->label = $request->input('libelle');
        $absenceType->description = $request->input('description');
        $absenceType->type = $request->input('type') ?? $absenceType->type;

        $absenceType->is_deductible = $request->input('is_deductible',false) ;

        $absenceType->save();

        // Return success response
        return response()->json(['message' => 'Type Absence mis à jour avec succès.'.$request->input('is_deductible'), 'ok' => true]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        \DB::table('absence_types')->where('id', $id)->delete();

        return response()->json(['ok' => true, 'message' => 'Le type d\'absence a été retiré avec succès.']);
    }
}
