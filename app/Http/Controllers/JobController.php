<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        return redirect()->back()->with('success', 'Job created successfully!');
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
    // public function update(Request $request, Job $job)
    // {
    //     //
    // }

    /**
     * Mettre à jour un job existant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    // public function update(Request $request, $id)
    // {
    //     // Récupération du job en fonction de l'ID
    //     $job = Job::find($id);

    //     // Si le job n'existe pas, renvoyer une erreur 404
    //     if (!$job) {
    //         return response()->json(['error' => 'Job not found'], 404);
    //     }

    //     // Validation des données reçues
    //     $validatedData = $request->validate([
    //         'title' => 'required|unique:jobs,title|string|max:255',
    //         'description' => 'nullable|string',
    //         'department_id' => 'required|exists:departments,id',
    //         'status' => 'required|in:ACTIVATED,DEACTIVATED,PENDING,DELETED,ARCHIVED',
    //         'n_plus_one_job_id' => 'nullable|exists:jobs,id',
    //     ]);

    //     // Mise à jour des attributs du job
    //     $job->update($validatedData);

    //     // Retourner une réponse JSON avec les informations mises à jour
    //     return response()->json([
    //         'message' => 'Job updated successfully',
    //         'job' => $job,
    //     ]);
    // }
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

            return redirect()->back()->with('success', 'Poste mis à jour avec succès.');
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
        return redirect()->back()->with('success', 'Poste supprimé avec succès.');

    }
}
