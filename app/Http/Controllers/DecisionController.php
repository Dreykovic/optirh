<?php

namespace App\Http\Controllers;

use App\Models\Decision;
use Illuminate\Http\Request;

class DecisionController extends Controller
{
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
    }

    public function show()
    {
        try {
            $decision = Decision::where('state', 'current')->get();

            return view('pages.admin.attendances.holidays.index', compact('decision'));
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
