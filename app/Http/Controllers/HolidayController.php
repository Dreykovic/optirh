<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HolidayController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:voir-un-férié|voir-un-tout'], ['only' => ['index']]);
        $this->middleware(['permission:créer-un-férié|créer-un-tout'], ['only' => ['store',  'create']]);

        $this->middleware(['permission:configurer-un-férié|écrire-un-tout'], ['only' => ['destroy', 'update']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $holidays = Holiday::orderBy('date', 'asc')->get();

        return view('pages.admin.attendances.holidays.index', compact('holidays'));

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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        \DB::table('holidays')->where('id', $id)->delete();

        return response()->json(['ok' => true, 'message' => 'Le jour fériée a été retiré avec succès.']);

    }
}
