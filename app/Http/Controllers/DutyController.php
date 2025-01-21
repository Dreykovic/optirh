<?php

namespace App\Http\Controllers;

use App\Models\Duty;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DutyController extends Controller 
{
    protected $evolutions = ['ON_GOING', 'ENDED', 'CANCEL', 'SUSPENDED', 'RESIGNED', 'DISMISSED'];
    protected $status = ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'];

    /**
     * Display a listing of the resource.
     */
    public function add(Request $request)
    {
        try {
            // Validation des données d'entrée
    
            $validatedData = $request->validate([
               
                'duration' => 'sometimes',
                'begin_date' => 'required|date',
                'type' => 'required|string|max:255',
                'job_id' => 'required|exists:jobs,id',
                'department_id' => 'required|exists:departments,id',
                'employee_id' => 'required|exists:employees,id',
                'absence_balance' => 'required|numeric|min:0',
                // 'force_create' => 'sometimes|boolean',
            ]);
            
            // Récupération de la direction et du poste
            $dept = Department::find($validatedData['department_id']);
            $job = Job::find($validatedData['job_id']);
            $old_employee = Employee::find($validatedData['employee_id']);

            if (!$dept || !$job) {
                return response()->json(['ok' => false, 'message' => 'Direction ou poste introuvable.'], 404);
            }

            // Vérification des conditions spécifiques à la direction
            if ($dept->name === 'DG' && $dept->director_id !== null && $job->title === 'DG') {
                if (empty($request->input('force_create'))) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'La direction générale a déjà un directeur. Voulez-vous continuer ?',
                        'requires_confirmation' => true,
                    ], 400);
                }
            } elseif ($dept->director_id !== null && $job->n_plus_one_job != null && $job->n_plus_one_job->title == 'DG') {
                if (empty($request->input('force_create'))) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'La direction générale a déjà un directeur. Voulez-vous continuer ?',
                        'requires_confirmation' => true,
                    ], 400);
                }
            }

            // Création de l'employé
            if (empty($request->input('force_create'))) {

            // Création du devoir (Duty)
                Duty::create([
                    'job_id' => $validatedData['job_id'],
                    'duration' => $validatedData['duration'],
                    'begin_date' => $validatedData['begin_date'],
                    'type' => $validatedData['type'],
                    'employee_id' => $old_employee->id,
                    'absence_balance' => $validatedData['absence_balance']
                ]);

                // Mise à jour du directeur de la direction si applicable
                if ($dept->name === 'DG' || ($job->n_plus_one_job != null && $job->n_plus_one_job->title == 'DG')) {
                    $dept->update(['director_id' => $old_employee->id]);
                }
            }

            if ($dept->name === 'DG' && $dept->director_id !== null && $job->title === 'DG') {
                if ($request->input('force_create')==true) {
                    $old_header = Employee::find($dept->director_id);
                    $old_header->update(['status' => $this->status[1]]);

                    $old_header_duty = Duty::where('employee_id',$old_header->id)->where('evolution',$this->evolutions[0]);
                    $old_header_duty->update([
                        'evolution' => $this->evolutions[1],
                        'status' => $this->status[1]
                    ]);
                    // Création de l'employé

                    // Création du devoir (Duty)
                    Duty::create([
                        'job_id' => $validatedData['job_id'],
                        'duration' => $validatedData['duration'],
                        'begin_date' => $validatedData['begin_date'],
                        'type' => $validatedData['type'],
                        'employee_id' => $old_employee->id,
                        'absence_balance' => $validatedData['absence_balance']
                    ]);
                    $dept->update(['director_id' => $old_employee->id]);
                }
            } elseif ($dept->director_id !== null && $job->n_plus_one_job != null && $job->n_plus_one_job->title == 'DG') {
                if ($request->input('force_create')==true) {
                    $old_header = Employee::find($dept->director_id);
                    $old_header->update(['status' => $this->status[1]]);

                    $old_header_duty = Duty::where('employee_id',$old_header->id)->where('evolution', $this->evolutions[0]);
                    $old_header_duty->update([
                        'evolution' => $this->evolutions[1],
                        'status' => $this->status[1]
                    ]);
                    // Création de l'employé

                    // Création du devoir (Duty)
                    Duty::create([
                        'job_id' => $validatedData['job_id'],
                        'duration' => $validatedData['duration'],
                        'begin_date' => $validatedData['begin_date'],
                        'type' => $validatedData['type'],
                        'employee_id' => $old_employee->id,
                        'absence_balance' => $validatedData['absence_balance']
                    ]);
                    $dept->update(['director_id' => $emp->id]);
                }
            }


            return response()->json(['message' => 'Contrat créé avec succès.', 'ok' => true]);
        } catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
    }




    public function index()
    {
        $employees = DB::table('employees')
            ->leftJoin('duties', 'employees.id', '=', 'duties.employee_id')
            ->join('jobs', 'duties.job_id', '=', 'jobs.id') // Récupérer le job associé
            ->leftJoin('departments', 'employees.id', '=', 'departments.director_id') // Exclure les directeurs si nécessaire
            ->whereNotExists(function ($query) {
                // Vérifier qu'il n'existe pas de contrat EN COURS pour cet employé
                $query->select(DB::raw(1))
                    ->from('duties as d')
                    ->whereColumn('d.employee_id', 'employees.id')
                    ->where('d.evolution', $this->evolutions[0]); // Contrat en cours
            })
            ->whereExists(function ($query) {
                // Inclure seulement si l'employé a au moins un contrat qui n'est pas supprimé
                $query->select(DB::raw(1))
                    ->from('duties as d')
                    ->whereColumn('d.employee_id', 'employees.id')
                    ->where('d.status', '!=', $this->status[3]); // Pas tous supprimés
            })
            ->select('employees.id', 'employees.first_name', 'employees.last_name', 'jobs.title')
            ->distinct()
            ->get();

        
        // $employees = DB::table('duties')
        //     ->join('employees', 'duties.employee_id', '=', 'employees.id')
        //     ->join('jobs', 'duties.job_id', '=', 'jobs.id') // Ajouter cette jointure pour accéder au job
        //     ->leftJoin('departments', 'employees.id', '=', 'departments.director_id')
        //     ->whereNot('duties.evolution', $this->evolutions[0])
        //     ->whereNot('duties.status', $this->status[3])
        //     // ->whereNull('departments.director_id') // S'assurer que l'employé n'est pas un directeur
        //     ->select('jobs.title', 'employees.first_name', 'employees.last_name', 'employees.id')
        //     ->distinct()
        //     ->get();
        $departments = Department::orderBy('created_at', 'desc')->get();
        return view('pages.admin.personnel.contrats.index',compact('departments', 'employees'));
    }

    public function contrats(Request $request, string $ev){
        
        $search = $request->input('search', '');     
        $limit = $request->input('limit', 5);   
        $page = $request->input('page', 1);  
        $departmentId = $request->input('deptValue', null); 
    
        // Construire la requête
        //en cours
        if ($ev == $this->evolutions[0]) {
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
                ->where('duties.evolution', '=', $this->evolutions[0])
                // ->where('employees.status', '=', $this->status[0])
                ->where('duties.status', '=', $this->status[0])
                ->orderBy('duties.created_at', 'desc');
        }
        //suspendus
        if ($ev == $this->evolutions[3]) {
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
                ->where('duties.evolution', '=', $this->evolutions[3])
                // ->where('employees.status', '=', $this->status[1])
                ->where('duties.status', '=', $this->status[1])
                ->orderBy('duties.created_at', 'desc');
        }
        //terminés
        if ($ev == $this->evolutions[1]) {
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
                ->where('duties.evolution', '=', $this->evolutions[1])
                ->where('duties.status', '=', $this->status[1])
                ->orderBy('duties.created_at', 'desc');
        }
        //démissionés
        if ($ev == $this->evolutions[4]) {
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
                ->where('duties.evolution', '=', $this->evolutions[4])
                ->where('duties.status', '=', $this->status[1])
                // ->where('employees.status', '=', $this->status[1])
                ->orderBy('duties.created_at', 'desc');
        }
        //licencies
        if ($ev == $this->evolutions[5]) {
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
                ->where('duties.evolution', '=', $this->evolutions[5])
                ->where('duties.status', '=', $this->status[1])
                // ->where('employees.status', '=', $this->status[1])
                ->orderBy('duties.created_at', 'desc');
        }
        //supprimes
        if ($ev == $this->status[3]) {
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
                ->where('duties.status', '=', $this->status[3])
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
            // $emp->update([
            //     'status' => $this->status[1]
            // ]);
            $duty->update([
                'status' => $this->status[1],
                'evolution' => $this->evolutions[3]
            ]);
            return response()->json(['message' => 'Suspendu avec succès.', 'ok' => true]);

        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
        
    }

    public function ongoing(Request $request, int $id){
        try {
            $duty = Duty::find($id);
            $emp = Employee::find($duty->employee_id);
            $emp->update([
                'status' => $this->status[0]
            ]);
            $duty->update([
                'evolution' => $this->evolutions[0],
                'status' => $this->status[0]
            ]);
            return response()->json(['message' => 'Réintégré avec succès.', 'ok' => true]);

        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function resigned(Request $request, int $id){
        try {
            $duty = Duty::find($id);
            $emp = Employee::find($duty->employee_id);
            // $emp->update([
            //     'status' => $this->status[1]
            // ]);
            $duty->update([
                'evolution' => $this->evolutions[4],
                'status' => $this->status[1]
            ]);
            return response()->json(['message' => 'Démissioné avec succès.', 'ok' => true]);

        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function dismissed(Request $request, int $id){
        try {
            $duty = Duty::find($id);
            $emp = Employee::find($duty->employee_id);
            // $emp->update([
            //     'status' => $this->status[1]
            // ]);
            $duty->update([
                'evolution' => $this->evolutions[5],
                'status' => $this->status[1]
            ]);
            return response()->json(['message' => 'licencié avec succès.', 'ok' => true]);

        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
    }
    public function deleted(Request $request, int $id){
        try {
            $duty = Duty::find($id);            
            $duty->update([
                'status' => $this->status[3]
            ]);
            return response()->json(['message' => 'Supprimé avec succès.', 'ok' => true]);

        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }
    }
    public function ended(Request $request, int $id){
        try {
            $duty = Duty::find($id);            
            $duty->update([
                'evolution' => $this->evolutions[1],
                'status' => $this->status[1]
            ]);
            return response()->json(['message' => 'Terminé avec succès.', 'ok' => true]);

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
