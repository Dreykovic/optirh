<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Duty;
use App\Models\Job;
use App\Models\Department;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $employees = Employee::all();
        $departments = Department::orderBy('created_at', 'desc')->get();
        $employees = Employee::orderBy('created_at', 'desc')->get();
        $nbre_employees = $employees->count();
        return view('pages.admin.personnel.membres.index',compact('employees', 'nbre_employees','departments'));
    }

    // function employees($id){
    //     try {
    //         $duties = Duty::where('evolution', 'ON_GOING')
    //             ->where('job_id', $id)
    //             ->get()
    //             ->toArray();
    //     return response()->json(['message' => 'employe du job get avec succès.',
    //             'ok' => true,
    //             'data' => $duties], 200)
    //    ->header('Content-Type', 'application/json');


    //     } catch (\Throwable $th) {
    //         return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
    //     }
    // }
    function employees($id)
        {
            try {
                // Récupérer uniquement les noms et prénoms des employés liés aux devoirs
                $duties = Duty::where('evolution', 'ON_GOING')
                    ->where('job_id', $id)
                    ->with(['employee' => function ($query) {
                        $query->select('id', 'firstname', 'lastname');
                    }])
                    ->get()
                    ->map(function ($duty) {
                        return [
                            'id' => $duty->employee->id,
                            'firstname' => $duty->employee->firstname,
                            'lastname' => $duty->employee->lastname,
                        ];
                    });

                return response()->json([
                    'message' => 'Employés récupérés avec succès.',
                    'ok' => true,
                    'data' => []
                ], 200)->header('Content-Type', 'application/json');
            } catch (\Throwable $th) {
                return response()->json([
                    'ok' => false,
                    'message' => $th->getMessage()
                ], 500);
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
            // Validation des données d'entrée
            $validatedData = $request->validate([
                'first_name' => 'required|max:255|string',
                'last_name' => 'required|max:255|string',
                'email' => 'required|email|max:255',
                'phone_number' => 'required|string|max:255',
                'address1' => 'required|string|max:255',
                'gender' => 'required|string|max:255',
                'duration' => 'required|string|max:255',
                'begin_date' => 'required|date',
                'type' => 'required|string|max:255',
                'job_id' => 'required|exists:jobs,id',
                'department_id' => 'required|exists:departments,id',
            ]);
    
            // Récupération de la direction et du poste
            $dept = Department::find($validatedData['department_id']);
            $job = Job::find($validatedData['job_id']);
    
            if (!$dept || !$job) {
                return response()->json(['ok' => false, 'message' => 'Direction ou poste introuvable.'], 404);
            }
    
            // Vérification des conditions spécifiques à la direction
            if ($dept->name === 'DG' && $dept->director_id !== null && $job->title === 'DG') {
                return response()->json(['ok' => false, 'message' => 'La direction générale a déjà un directeur.'], 400);
            } elseif ($dept->director_id !== null && str_starts_with($job->title, 'Directeur.rice')) {
                return response()->json(['ok' => false, 'message' => 'Cette direction a déjà un directeur.'], 400);
            }
    
            // Création de l'employé
            $emp = Employee::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone_number' => $validatedData['phone_number'],
                'address1' => $validatedData['address1'],
                'gender' => $validatedData['gender'],
            ]);
    
            // Création du devoir (Duty)
            Duty::create([
                'job_id' => $validatedData['job_id'],
                'duration' => $validatedData['duration'],
                'begin_date' => $validatedData['begin_date'],
                'type' => $validatedData['type'],
                'employee_id' => $emp->id,
            ]);
    
            // Mise à jour du directeur de la direction si applicable
            if ($dept->name === 'DG' || str_starts_with($job->title, 'Directeur.rice')) {
                $dept->update(['director_id' => $emp->id]);
            }
    
            return response()->json(['message' => 'Employé créé avec succès.', 'ok' => true]);
    
        } catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
        return view('pages.admin.personnel.membres.show', compact('employee'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
