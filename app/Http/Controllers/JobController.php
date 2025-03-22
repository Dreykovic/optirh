<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function getJobsByDepartment($departmentId)
    {
        $jobs = Job::where('department_id', $departmentId)->where('status', 'ACTIVATED')->get(['id', 'title']);
        return response()->json($jobs);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created job in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'title' => 'required|unique:jobs,title|string|max:255',
            'description' => 'required|string|max:500',
            'department_id' => 'required|exists:departments,id',
            'n_plus_one_job_id' => 'nullable|exists:jobs,id',
        ]);

        // Create the job
        Job::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'department_id' => $validatedData['department_id'],
            'n_plus_one_job_id' => $validatedData['n_plus_one_job_id'],
        ]);

        return response()->json(['message' => 'Poste créé avec succès.', 'ok' => true]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $job = Job::findOrFail($id);

        $validatedData = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                // 'unique:jobs,title',
            ],
            'description' => 'required|string|max:500',
            'n_plus_one_job_id' => 'nullable|exists:jobs,id',
        ]);

        $job->update($validatedData);

        return response()->json(['message' => 'Poste mis à jour avec succès.', 'ok' => true]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        // Trouver le job avec l'ID passé en paramètre
        $job = Job::findOrFail($id);

        // Supprimer le job
        $job->delete();

        // Retourner une réponse JSON ou rediriger avec un message de succès
        return response()->json(['message' => 'Poste supprimé avec succès.', 'ok' => true]);

    }
}
