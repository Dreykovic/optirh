<?php

namespace App\Http\Controllers;

use App\Models\Duty;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DutyController extends Controller
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
            ->whereNot('duties.evolution', 'ON_GOING')
            // ->whereNull('departments.director_id') // S'assurer que l'employé n'est pas un directeur
            ->select('jobs.title', 'employees.first_name', 'employees.last_name', 'employees.id')
            ->distinct()
            ->get();
        $departments = Department::orderBy('created_at', 'desc')->get();
        return view('pages.admin.personnel.contrats.index',compact('departments', 'duties'));
    }

    public function enCours(Request $request, string $ev){
        $evolutions = ['ON_GOING', 'ENDED', 'CANCEL','SUSPENDED','RESIGNED', 'DISMISSED'];
        $status = ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'];

        $search = $request->input('search', '');     
        $limit = $request->input('limit', 5);   
        $page = $request->input('page', 1);  
        $departmentId = $request->input('deptValue', null); 
    
        // Construire la requête
        //en cours
        if ($ev == $evolutions[0]) {
            $query = DB::table('duties')
                ->join('employees', 'duties.employee_id', '=', 'employees.id')
                ->join('jobs', 'duties.job_id', '=', 'jobs.id')
                ->join('departments', 'jobs.department_id', '=', 'departments.id')
                ->select(
                    'duties.id as duty_id',
                    'duties.begin_date',
                    'duties.absence_balance',
                    'duties.type',
                    'employees.first_name',
                    'employees.last_name',
                    'employees.gender',
                    'jobs.title as job_title',
                    'departments.name as department_name'
                )
                ->where('duties.evolution', '=', $evolutions[0])
                ->where('employees.status', '=', $status[0])
                ->orderBy('duties.created_at', 'desc');
        }
        //suspendus
        if ($ev == $evolutions[3]) {
            $query = DB::table('duties')
                ->join('employees', 'duties.employee_id', '=', 'employees.id')
                ->join('jobs', 'duties.job_id', '=', 'jobs.id')
                ->join('departments', 'jobs.department_id', '=', 'departments.id')
                ->select(
                    'duties.id as duty_id',
                    'duties.begin_date',
                    'duties.absence_balance',
                    'duties.type',
                    'employees.first_name',
                    'employees.last_name',
                    'employees.gender',
                    'jobs.title as job_title',
                    'departments.name as department_name'
                )
                ->where('duties.evolution', '=', $evolutions[0])
                ->where('employees.status', '=', $status[1])
                ->orderBy('duties.created_at', 'desc');
        }
        //terminés
        if ($ev == $evolutions[1]) {
            $query = DB::table('duties')
                ->join('employees', 'duties.employee_id', '=', 'employees.id')
                ->join('jobs', 'duties.job_id', '=', 'jobs.id')
                ->join('departments', 'jobs.department_id', '=', 'departments.id')
                ->select(
                    'duties.id as duty_id',
                    'duties.begin_date',
                    'duties.absence_balance',
                    'duties.type',
                    'employees.first_name',
                    'employees.last_name',
                    'employees.gender',
                    'jobs.title as job_title',
                    'departments.name as department_name'
                )
                ->where('duties.evolution', '=', $evolutions[1])
                ->orderBy('duties.created_at', 'desc');
        }
        //démissionés
        if ($ev == $evolutions[4]) {
            $query = DB::table('duties')
                ->join('employees', 'duties.employee_id', '=', 'employees.id')
                ->join('jobs', 'duties.job_id', '=', 'jobs.id')
                ->join('departments', 'jobs.department_id', '=', 'departments.id')
                ->select(
                    'duties.id as duty_id',
                    'duties.begin_date',
                    'duties.absence_balance',
                    'duties.type',
                    'employees.first_name',
                    'employees.last_name',
                    'employees.gender',
                    'jobs.title as job_title',
                    'departments.name as department_name'
                )
                ->where('duties.evolution', '=', $evolutions[4])
                ->where('employees.status', '=', $status[1])
                ->orderBy('duties.created_at', 'desc');
        }
        //licencies
        if ($ev == $evolutions[5]) {
            $query = DB::table('duties')
                ->join('employees', 'duties.employee_id', '=', 'employees.id')
                ->join('jobs', 'duties.job_id', '=', 'jobs.id')
                ->join('departments', 'jobs.department_id', '=', 'departments.id')
                ->select(
                    'duties.id as duty_id',
                    'duties.begin_date',
                    'duties.absence_balance',
                    'duties.type',
                    'employees.first_name',
                    'employees.last_name',
                    'employees.gender',
                    'jobs.title as job_title',
                    'departments.name as department_name'
                )
                ->where('duties.evolution', '=', $evolutions[5])
                ->where('employees.status', '=', $status[1])
                ->orderBy('duties.created_at', 'desc');
        }
        //supprimes
        if ($ev == $status[3]) {
            $query = DB::table('duties')
                ->join('employees', 'duties.employee_id', '=', 'employees.id')
                ->join('jobs', 'duties.job_id', '=', 'jobs.id')
                ->join('departments', 'jobs.department_id', '=', 'departments.id')
                ->select(
                    'duties.id as duty_id',
                    'duties.begin_date',
                    'duties.absence_balance',
                    'duties.type',
                    'employees.first_name',
                    'employees.last_name',
                    'employees.gender',
                    'jobs.title as job_title',
                    'departments.name as department_name'
                )
                ->where('duties.status', '=', $status[3])
                ->orderBy('duties.created_at', 'desc');
        }
        
        
        // Filtrer par département, si fourni
        if (!is_null($departmentId)) {
            
            $query->where('jobs.department_id', '=', $departmentId);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(employees.first_name) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(employees.last_name) LIKE ?', ['%' . strtolower($search) . '%'])
                //   ->orWhereRaw('duties.begin_date LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('CAST(duties.absence_balance AS TEXT) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(duties.type) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(departments.name) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(jobs.title) LIKE ?', ['%' . strtolower($search) . '%']);
            });
          
        }
        
    
        // Ajouter la pagination
        $employees = $query->paginate($limit);
    
        // Retourner la réponse JSON
        return response()->json($employees);
    }

    public function suspended(Request $request, int $id){
        try {
            $duty = Duty::find($id);
            $emp = Employee::find($duty->employee_id);
            $emp->update([
                'status' => 'DEACTIVATED'
            ]);
            return response()->json(['message' => 'Suspendu avec succès.', 'ok' => true]);

        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
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
    public function show(Duty $duty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Duty $duty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Duty $duty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Duty $duty)
    {
        //
    }
}
