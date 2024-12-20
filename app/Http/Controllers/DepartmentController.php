<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $duties = DB::table('duties')
            ->join('employees', 'duties.employee_id', '=', 'employees.id')
            ->join('jobs', 'duties.job_id', '=', 'jobs.id') // Ajouter cette jointure pour accéder au job
            ->leftJoin('departments', 'employees.id', '=', 'departments.director_id')
            ->where('duties.evolution', 'ON_GOING')
            ->whereNull('departments.director_id') // S'assurer que l'employé n'est pas un directeur
            ->select('jobs.title', 'employees.first_name', 'employees.last_name', 'employees.id')
            ->get();
        

        $departments = Department::orderBy('created_at', 'desc')->get();
        return view('pages.personnel.directions', compact('departments', 'duties'));
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
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:departments,name|string|max:255',
                'description' => 'required|string|max:500',
                'director_id' => 'nullable|exists:employees,id'
            ]);
    
            // Create the job
            Department::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'director_id' => $validatedData['director_id']
            ]);
        return redirect()->back()->with('success', 'Department created successfully!');

        } catch (\Throwable $th) {
            //throw $th;
            dump($th);
        }
        

    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        $nbre_postes = $department->jobs->count();
        return view('pages.personnel.detail_direction', compact('department', 'nbre_postes'));
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        //
    }
}
