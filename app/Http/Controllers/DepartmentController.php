<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Duty;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $evolutions = ['ON_GOING', 'ENDED', 'CANCEL', 'SUSPENDED', 'RESIGNED', 'DISMISSED'];
    protected $status = ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'];

    public function index()
    {
        $employees = DB::table('duties')
            ->join('employees', 'duties.employee_id', '=', 'employees.id')
            ->join('jobs', 'duties.job_id', '=', 'jobs.id') // Ajouter cette jointure pour accéder au job
            ->leftJoin('departments', 'employees.id', '=', 'departments.director_id')
            ->whereNot('duties.status', $this->status[3])
            // ->orWhere('duties.evolution', $this->evolutions[1])
            // ->orWhere('duties.evolution', $this->evolutions[3])
            ->whereNull('departments.director_id') // S'assurer que l'employé n'est pas un directeur
            ->select('jobs.title', 'employees.first_name', 'employees.last_name', 'employees.id')
            ->get();

        $departments = Department::orderBy('created_at', 'desc')->get();

        return view('pages.admin.personnel.directions.index', compact('departments', 'employees'));
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
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:departments,name|string|max:255',
                'description' => 'required|string|max:500',
                'director_id' => 'nullable|exists:employees,id',
            ]);

            $validatedData['director_id'] = $validatedData['director_id'] ?? null;
            $job_superior = Job::where('title', 'DG')->firstOrFail();
            // Créer le département
            $dept = Department::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'director_id' => $validatedData['director_id'],
            ]);

            $job = Job::create([
                'title' => 'Directeur·trice '.$dept->name,
                'description' => 'Directeur·trice '.$dept->description,
                'n_plus_one_job_id' => $job_superior->id,
                'department_id' => $dept->id,
            ]);

            if ($validatedData['director_id'] != null) {
                $duty = Duty::where('evolution', $this->evolutions[0])
                ->where('employee_id', $validatedData['director_id'])
                ->first();

                if ($duty) {
                    $duty->update([
                        'evolution' => $this->evolutions[1],
                        'status' => $this->status[1],
                    ]);
                    Duty::create([
                        'job_id' => $job->id,
                        'employee_id' => $validatedData['director_id'],
                        'begin_date' => Carbon::now(),
                    ]);
                }
            }

            return response()->json(['message' => 'Department créé avec succès.', 'ok' => true]);
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
    public function show(Department $department)
    {
        $nbre_postes = $department->jobs->count();
        $nbreduty = Duty::where('evolution', 'ON_GOING')
        ->whereHas('job', function ($query) use ($department) {
            $query->where('department_id', $department->id);
        })
        ->count();

        return view('pages.admin.personnel.directions.show', compact('department', 'nbre_postes', 'nbreduty'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dump("je yas");
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:departments,name,'.$id.'|string|max:255',
                'description' => 'required|string|max:500',
            ]);

            // Récupérer le département
            $department = Department::findOrFail($id);

            // Mettre à jour les informations du département
            $department->update([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
            ]);

            // Mettre à jour le job "Directeur·rice" associé au département
            // $job = Job::where('title', 'Directeur·rice ' . $department->name)->first();

            // if ($job) {
            //     $job->update([
            //         'title' => 'Directeur·rice ' . $department->name,
            //         'description' => 'Directeur·rice ' . $department->description,
            //     ]);
            // }

            return response()->json(['message' => 'Department mis à jour avec succès.', 'ok' => true]);
        } catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
    }
}
