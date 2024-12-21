<?php

namespace App\Http\Controllers;

use App\Models\Employee;
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
        //
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
