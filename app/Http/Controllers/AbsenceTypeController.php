<?php

namespace App\Http\Controllers;

use App\Models\AbsenceType;
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
        //
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
    public function update(Request $request, AbsenceType $absenceType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AbsenceType $absenceType)
    {
        //
    }
}
