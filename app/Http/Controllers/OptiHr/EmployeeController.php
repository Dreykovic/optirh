<?php

namespace App\Http\Controllers\OptiHr;

use App\Http\Controllers\Controller;
use App\Models\OptiHr\Department;
use App\Models\OptiHr\Duty;
use App\Models\OptiHr\Employee;
use App\Models\OptiHr\File;
use App\Models\OptiHr\Job;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// class EmployeeController extends Controller
// {
//     public function __construct()
//     {
//         $this->middleware(['permission:voir-un-employee|configurer-un-employee|voir-un-tout'], ['only' => ['index']]);
//         $this->middleware(['permission:créer-un-employee|créer-un-tout'], ['only' => ['store', 'update', 'create']]);
//         $this->middleware(['permission:configurer-un-employee|configurer-un-tout'], ['only' => ['show', 'updateEmployeeData']]);
//         $this->middleware(['permission:écrire-un-employee|écrire-un-tout'], ['only' => ['updatePres', 'updateBank']]);
//         $this->middleware(['permission:configurer-un-employee|écrire-un-tout'], ['only' => ['destroy']]);
//     }
//     protected $evolutions = ['ON_GOING', 'ENDED', 'CANCEL', 'SUSPENDED', 'RESIGNED', 'DISMISSED'];
//     protected $status = ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'];


//     /**
//      * Display a listing of the resource.
//      */
//     public function index(Request $request)
//     {
//         $search = $request->input('search', '');
//         $limit = $request->input('limit', 5);
//         $page = $request->input('page', 1);
//         $departmentId = $request->input('deptValue', null);

//         $search = $request->input('search', '');
//         $limit = $request->input('limit', 5);
//         $page = $request->input('page', 1);
//         $departmentId = $request->input('deptValue', null);

//         // Construire la requête
//         $query = DB::table('employees')
//             ->join('duties', 'employees.id', '=', 'duties.employee_id')
//             ->join('jobs', 'duties.job_id', '=', 'jobs.id')
//             ->select('employees.*')
//             ->where('duties.evolution', '=', $this->evolutions[0])
//             // ->where('employees.status', '=', $this->status[0])
//             ->where('duties.status', '=', $this->status[0])
//             ->orderBy('created_at', 'desc');


//         // Filtrer par département, si fourni
//         if (!is_null($departmentId)) {


//             $query->where('jobs.department_id', '=', $departmentId);
//         }
//         if ($search) {
//             $query->where(function ($q) use ($search) {
//                 $q->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($search) . '%'])
//                   ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($search) . '%'])
//                   ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($search) . '%'])
//                   ->orWhereRaw('LOWER(phone_number) LIKE ?', ['%' . strtolower($search) . '%'])
//                   ->orWhereRaw('LOWER(code) LIKE ?', ['%' . strtolower($search) . '%'])
//                   ->orWhereRaw('LOWER(address1) LIKE ?', ['%' . strtolower($search) . '%']);
//             });
//         }




//         // Ajouter la pagination
//         $employees = $query->paginate($limit);


//         // Retourner la réponse JSON
//         return response()->json($employees);
//     }
    





//     public function pages()
//     {
//         $departments = Department::orderBy('created_at', 'desc')->get();
//         $query = DB::table('employees')
//             ->join('duties', 'employees.id', '=', 'duties.employee_id')
//             ->join('jobs', 'duties.job_id', '=', 'jobs.id')
//             ->select('employees.*')
//             ->where('duties.evolution', '=', $this->evolutions[0])
//             // ->where('employees.status', '=', $this->status[0])
//             ->where('duties.status', '=', $this->status[0])
//             ->orderBy('created_at', 'desc');

//         $nbre_employees = $query->count();
//         return view('modules.opti-hr.pages.personnel.membres.index', compact('nbre_employees', 'departments'));


//     }

//     // function pay(){
//     //     $departments = Department::orderBy('created_at', 'desc')->get();
//     //     return view('modules.opti-hr.pages.personnel.membres.pay-form',compact('departments'));
//     // }

//     public function paycode()
//     {
//         $departments = Department::orderBy('created_at', 'desc')->get();
//         return view('modules.opti-hr.pages.personnel.membres.pay-form-code', compact('departments'));

//     }



//     // function employees($id){
//     //     try {
//     //         $duties = Duty::where('evolution', 'ON_GOING')
//     //             ->where('job_id', $id)
//     //             ->get()
//     //             ->toArray();
//     //     return response()->json(['message' => 'employe du job get avec succès.',
//     //             'ok' => true,
//     //             'data' => $duties], 200)
//     //    ->header('Content-Type', 'application/json');

//     //     } catch (\Throwable $th) {
//     //         return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
//     //     }
//     // }
//     public function jobEmployees($id)
//     {

//         // Récupérer uniquement les noms et prénoms des employés liés aux devoirs
//         $duties = Duty::where('evolution', $this->evolutions[0])
//             ->where('job_id', $id)
//             ->with(['employee:id,first_name,last_name,gender']) // Charge les employés avec seulement les champs nécessaires
//             ->get()
//             ->map(function ($duty) {
//                 return [
//                     'id' => $duty->employee->id,
//                     'first_name' => $duty->employee->first_name,
//                     'last_name' => $duty->employee->last_name,
//                     'gender' => $duty->employee->gender,
//                 ];
//             });
//         return response()->json([
//             'data' => $duties,
//         ], 200);

//         // return response()->json($duties, 200);

//     }


//     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(Request $request)
//     {

//         // Validation des données d'entrée

//         $validatedData = $request->validate([
//             'first_name' => 'required|max:255|string',
//             'last_name' => 'required|max:255|string',
//             'email' => 'required|email|max:255|unique:employees,email',
//             'phone_number' => 'required|string|max:255|unique:employees,phone_number',
//             'address1' => 'required|string|max:255',
//             'gender' => 'required|in:MALE,FEMALE',
//             'duration' => 'sometimes',
//             'begin_date' => 'required|date',
//             'type' => 'required|string|max:255',
//             'job_id' => 'required|exists:jobs,id',
//             'department_id' => 'required|exists:departments,id',
//             'absence_balance' => 'required|numeric|min:0',
//             // 'force_create' => 'sometimes|boolean',
//         ]);


//         // Validation des données d'entrée

//         $validatedData = $request->validate([
//             'first_name' => 'required|max:255|string',
//             'last_name' => 'required|max:255|string',
//             'email' => 'required|email|max:255|unique:employees,email',
//             'phone_number' => 'required|string|max:255|unique:employees,phone_number',
//             'address1' => 'required|string|max:255',
//             'gender' => 'required|in:MALE,FEMALE',
//             'duration' => 'sometimes',
//             'begin_date' => 'required|date',
//             'type' => 'required|string|max:255',
//             'job_id' => 'required|exists:jobs,id',
//             'department_id' => 'required|exists:departments,id',
//             'absence_balance' => 'required|numeric|min:0',
//             // 'force_create' => 'sometimes|boolean',
//         ]);


//         // Récupération de la direction et du poste
//         $dept = Department::find($validatedData['department_id']);
//         $job = Job::find($validatedData['job_id']);
//         // Récupération de la direction et du poste
//         $dept = Department::find($validatedData['department_id']);
//         $job = Job::find($validatedData['job_id']);

//         if (!$dept || !$job) {
//             return response()->json(['ok' => false, 'message' => 'Direction ou poste introuvable.'], 404);
//         }
//         if (!$dept || !$job) {
//             return response()->json(['ok' => false, 'message' => 'Direction ou poste introuvable.'], 404);
//         }

//         // Vérification des conditions spécifiques à la direction
//         if ($dept->name === 'DG' && $dept->director_id !== null && $job->title === 'DG') {
//             if (empty($request->input('force_create'))) {
//                 return response()->json([
//                     'ok' => false,
//                     'message' => 'La direction générale a déjà un directeur. Voulez-vous continuer ?',
//                     'requires_confirmation' => true,
//                 ], 400);
//             }
//         } elseif ($dept->director_id !== null && $job->n_plus_one_job != null && $job->n_plus_one_job->title == 'DG') {
//             if (empty($request->input('force_create'))) {
//                 return response()->json([
//                     'ok' => false,
//                     'message' => 'La direction générale a déjà un directeur. Voulez-vous continuer ?',
//                     'requires_confirmation' => true,
//                 ], 400);
//             }
//         }
//         // Vérification des conditions spécifiques à la direction
//         if ($dept->name === 'DG' && $dept->director_id !== null && $job->title === 'DG') {
//             if (empty($request->input('force_create'))) {
//                 return response()->json([
//                     'ok' => false,
//                     'message' => 'La direction générale a déjà un directeur. Voulez-vous continuer ?',
//                     'requires_confirmation' => true,
//                 ], 400);
//             }
//         } elseif ($dept->director_id !== null && $job->n_plus_one_job != null && $job->n_plus_one_job->title == 'DG') {
//             if (empty($request->input('force_create'))) {
//                 return response()->json([
//                     'ok' => false,
//                     'message' => 'La direction générale a déjà un directeur. Voulez-vous continuer ?',
//                     'requires_confirmation' => true,
//                 ], 400);
//             }
//         }

//         // Création de l'employé
//         if (empty($request->input('force_create'))) {
//             $emp = Employee::create([
//                 'first_name' => $validatedData['first_name'],
//                 'last_name' => $validatedData['last_name'],
//                 'email' => $validatedData['email'],
//                 'phone_number' => $validatedData['phone_number'],
//                 'address1' => $validatedData['address1'],
//                 'gender' => $validatedData['gender'],
//             ]);
//             // Création de l'employé
//             if (empty($request->input('force_create'))) {
//                 $emp = Employee::create([
//                     'first_name' => $validatedData['first_name'],
//                     'last_name' => $validatedData['last_name'],
//                     'email' => $validatedData['email'],
//                     'phone_number' => $validatedData['phone_number'],
//                     'address1' => $validatedData['address1'],
//                     'gender' => $validatedData['gender'],
//                 ]);

//                 // Création du devoir (Duty)
//                 Duty::create([
//                     'job_id' => $validatedData['job_id'],
//                     'duration' => $validatedData['duration'],
//                     'begin_date' => $validatedData['begin_date'],
//                     'type' => $validatedData['type'],
//                     'employee_id' => $emp->id,
//                     'absence_balance' => $validatedData['absence_balance']
//                 ]);
//                 Duty::create([
//                     'job_id' => $validatedData['job_id'],
//                     'duration' => $validatedData['duration'],
//                     'begin_date' => $validatedData['begin_date'],
//                     'type' => $validatedData['type'],
//                     'employee_id' => $emp->id,
//                     'absence_balance' => $validatedData['absence_balance']
//                 ]);

//                 // Mise à jour du directeur de la direction si applicable
//                 if ($dept->name === 'DG' || ($job->n_plus_one_job != null && $job->n_plus_one_job->title == 'DG')) {
//                     $dept->update(['director_id' => $emp->id]);
//                 }
//             }
//             // Mise à jour du directeur de la direction si applicable
//             if ($dept->name === 'DG' || ($job->n_plus_one_job != null && $job->n_plus_one_job->title == 'DG')) {
//                 $dept->update(['director_id' => $emp->id]);
//             }
//         }

//         if ($dept->name === 'DG' && $dept->director_id !== null && $job->title === 'DG') {
//             if ($request->input('force_create') == true) {
//                 $old_header = Employee::find($dept->director_id);
//                 $old_header->update(['status' => $this->status[3]]);
//                 if ($dept->name === 'DG' && $dept->director_id !== null && $job->title === 'DG') {
//                     if ($request->input('force_create') == true) {
//                         $old_header = Employee::find($dept->director_id);
//                         $old_header->update(['status' => $this->status[3]]);

//                         $old_header_duty = Duty::where('employee_id', $old_header->id)->where('evolution', 'ON_GOING');
//                         $old_header_duty->update(['evolution' => $this->evolutions[1]]);
//                         // Création de l'employé
//                         $emp = Employee::create([
//                             'first_name' => $validatedData['first_name'],
//                             'last_name' => $validatedData['last_name'],
//                             'email' => $validatedData['email'],
//                             'phone_number' => $validatedData['phone_number'],
//                             'address1' => $validatedData['address1'],
//                             'gender' => $validatedData['gender'],
//                         ]);
//                         $old_header_duty = Duty::where('employee_id', $old_header->id)->where('evolution', 'ON_GOING');
//                         $old_header_duty->update(['evolution' => $this->evolutions[1]]);
//                         // Création de l'employé
//                         $emp = Employee::create([
//                             'first_name' => $validatedData['first_name'],
//                             'last_name' => $validatedData['last_name'],
//                             'email' => $validatedData['email'],
//                             'phone_number' => $validatedData['phone_number'],
//                             'address1' => $validatedData['address1'],
//                             'gender' => $validatedData['gender'],
//                         ]);

//                         // Création du devoir (Duty)
//                         Duty::create([
//                             'job_id' => $validatedData['job_id'],
//                             'duration' => $validatedData['duration'],
//                             'begin_date' => $validatedData['begin_date'],
//                             'type' => $validatedData['type'],
//                             'employee_id' => $emp->id,
//                             'absence_balance' => $validatedData['absence_balance']
//                         ]);
//                         $dept->update(['director_id' => $emp->id]);
//                     }
//                 } elseif ($dept->director_id !== null && $job->n_plus_one_job != null && $job->n_plus_one_job->title == 'DG') {
//                     if ($request->input('force_create') == true) {
//                         $old_header = Employee::find($dept->director_id);
//                         $old_header->update(['status' => 'DELETED']);
//                         // Création du devoir (Duty)
//                         Duty::create([
//                             'job_id' => $validatedData['job_id'],
//                             'duration' => $validatedData['duration'],
//                             'begin_date' => $validatedData['begin_date'],
//                             'type' => $validatedData['type'],
//                             'employee_id' => $emp->id,
//                             'absence_balance' => $validatedData['absence_balance']
//                         ]);
//                         $dept->update(['director_id' => $emp->id]);
//                     }
//                 } elseif ($dept->director_id !== null && $job->n_plus_one_job != null && $job->n_plus_one_job->title == 'DG') {
//                     if ($request->input('force_create') == true) {
//                         $old_header = Employee::find($dept->director_id);
//                         $old_header->update(['status' => 'DELETED']);

//                         $old_header_duty = Duty::where('employee_id', $old_header->id)->where('evolution', 'ON_GOING');
//                         $old_header_duty->update(['evolution' => $this->status[1]]);
//                         // Création de l'employé
//                         $emp = Employee::create([
//                             'first_name' => $validatedData['first_name'],
//                             'last_name' => $validatedData['last_name'],
//                             'email' => $validatedData['email'],
//                             'phone_number' => $validatedData['phone_number'],
//                             'address1' => $validatedData['address1'],
//                             'gender' => $validatedData['gender'],
//                         ]);
//                         $old_header_duty = Duty::where('employee_id', $old_header->id)->where('evolution', 'ON_GOING');
//                         $old_header_duty->update(['evolution' => $this->status[1]]);
//                         // Création de l'employé
//                         $emp = Employee::create([
//                             'first_name' => $validatedData['first_name'],
//                             'last_name' => $validatedData['last_name'],
//                             'email' => $validatedData['email'],
//                             'phone_number' => $validatedData['phone_number'],
//                             'address1' => $validatedData['address1'],
//                             'gender' => $validatedData['gender'],
//                         ]);

//                         // Création du devoir (Duty)
//                         Duty::create([
//                             'job_id' => $validatedData['job_id'],
//                             'duration' => $validatedData['duration'],
//                             'begin_date' => $validatedData['begin_date'],
//                             'type' => $validatedData['type'],
//                             'employee_id' => $emp->id,
//                             'absence_balance' => $validatedData['absence_balance']
//                         ]);
//                         $dept->update(['director_id' => $emp->id]);
//                     }
//                 }
//                 // Création du devoir (Duty)
//                 Duty::create([
//                     'job_id' => $validatedData['job_id'],
//                     'duration' => $validatedData['duration'],
//                     'begin_date' => $validatedData['begin_date'],
//                     'type' => $validatedData['type'],
//                     'employee_id' => $emp->id,
//                     'absence_balance' => $validatedData['absence_balance']
//                 ]);
//                 $dept->update(['director_id' => $emp->id]);
//             }
//         }


//         return response()->json(['message' => 'Employé créé avec succès.', 'ok' => true]);

//         return response()->json(['message' => 'Employé créé avec succès.', 'ok' => true]);

//     }


//     /**
//      * Display the specified resource.
//      */

//     public function mesFactures(Employee $employee)
//     {
//         $files = File::where('employee_id', $employee->id)->get();


//         return view('modules.opti-hr.pages.personnel.membres.employee-pay', compact('employee', 'files'));
//     }

//     public function show(Employee $employee)
//     {
//         $files = File::where('employee_id', $employee->id)->get();
//         $duty = Duty::where('evolution', $this->evolutions[0])
//                     ->where('employee_id', $employee->id)
//                     ->first();

//         return view('modules.opti-hr.pages.personnel.membres.show', compact('employee', 'duty', 'files'));
//     }


//     /**
//      * Update the specified resource in storage. updatePresIdentity
//      */
//     public function updatePres(Request $request, $id)
//     {
//         $employee = Employee::findOrFail($id);


//         $validatedData = $request->validate([
//             'nationality' => 'max:255|sometimes',
//             'religion' => 'max:255|sometimes',
//             'marital_status' => 'max:255|sometimes',
//             'emergency_contact' => 'max:255|sometimes',
//             'city' => 'max:255|sometimes',
//             'state' => 'max:255|sometimes',
//         ]);
//         $employee->update([
//             'nationality' => $validatedData['nationality'],
//             'religion' => $validatedData['religion'],
//             'marital_status' => $validatedData['marital_status'],
//             'emergency_contact' => $validatedData['emergency_contact'],
//             'city' => $validatedData['city'],
//             'state' => $validatedData['state'],
//         ]);

//         $validatedData = $request->validate([
//             'nationality' => 'max:255|sometimes',
//             'religion' => 'max:255|sometimes',
//             'marital_status' => 'max:255|sometimes',
//             'emergency_contact' => 'max:255|sometimes',
//             'city' => 'max:255|sometimes',
//             'state' => 'max:255|sometimes',
//         ]);
//         $employee->update([
//             'nationality' => $validatedData['nationality'],
//             'religion' => $validatedData['religion'],
//             'marital_status' => $validatedData['marital_status'],
//             'emergency_contact' => $validatedData['emergency_contact'],
//             'city' => $validatedData['city'],
//             'state' => $validatedData['state'],
//         ]);

//         return response()->json(['message' => 'Employé editer avec succès.', 'ok' => true]);

//         return response()->json(['message' => 'Employé editer avec succès.', 'ok' => true]);

//     }

//     public function updatePresIdentity(Request $request, $id)
//     {
//         $employee = Employee::findOrFail($id);


//         $validatedData = $request->validate([
//             'first_name' => 'max:255|sometimes',
//             'last_name' => 'max:255|sometimes',
//             'phone_number' => 'max:255|sometimes',
//             'address1' => 'max:255|sometimes',
//             'birth_date' => 'max:255|sometimes',
//             'email' => 'max:255|sometimes',
//         ]);
//         $employee->update([
//             'first_name' => $validatedData['first_name'],
//             'last_name' => $validatedData['last_name'],
//             'phone_number' => $validatedData['phone_number'],
//             'address1' => $validatedData['address1'],
//             'birth_date' => $validatedData['birth_date'],
//             'email' => $validatedData['email'],
//         ]);

//         $validatedData = $request->validate([
//             'first_name' => 'max:255|sometimes',
//             'last_name' => 'max:255|sometimes',
//             'phone_number' => 'max:255|sometimes',
//             'address1' => 'max:255|sometimes',
//             'birth_date' => 'max:255|sometimes',
//             'email' => 'max:255|sometimes',
//         ]);
//         $employee->update([
//             'first_name' => $validatedData['first_name'],
//             'last_name' => $validatedData['last_name'],
//             'phone_number' => $validatedData['phone_number'],
//             'address1' => $validatedData['address1'],
//             'birth_date' => $validatedData['birth_date'],
//             'email' => $validatedData['email'],
//         ]);

//         return response()->json(['message' => 'Employé editer avec succès.', 'ok' => true]);

//         return response()->json(['message' => 'Employé editer avec succès.', 'ok' => true]);

//     }



//     public function updateBank(Request $request, Employee $employee)
//     {

//         $validatedData = $request->validate([
//             'bank_name' => 'max:255|sometimes',
//             'rib' => 'max:255|sometimes',
//             'code_bank' => 'max:255|sometimes',
//             'code_guichet' => 'max:255|sometimes',
//             'iban' => 'max:255|sometimes',
//             'swift' => 'max:255|sometimes',
//             'cle_rib' => 'max:255|sometimes',
//         ]);
//         $employee->update([
//             'bank_name' => $validatedData['bank_name'],
//             'rib' => $validatedData['rib'],
//             'code_bank' => $validatedData['code_bank'],
//             'code_guichet' => $validatedData['code_guichet'],
//             'iban' => $validatedData['iban'],
//             'swift' => $validatedData['swift'],
//             'cle_rib' => $validatedData['cle_rib'],
//         ]);

//         $validatedData = $request->validate([
//             'bank_name' => 'max:255|sometimes',
//             'rib' => 'max:255|sometimes',
//             'code_bank' => 'max:255|sometimes',
//             'code_guichet' => 'max:255|sometimes',
//             'iban' => 'max:255|sometimes',
//             'swift' => 'max:255|sometimes',
//             'cle_rib' => 'max:255|sometimes',
//         ]);
//         $employee->update([
//             'bank_name' => $validatedData['bank_name'],
//             'rib' => $validatedData['rib'],
//             'code_bank' => $validatedData['code_bank'],
//             'code_guichet' => $validatedData['code_guichet'],
//             'iban' => $validatedData['iban'],
//             'swift' => $validatedData['swift'],
//             'cle_rib' => $validatedData['cle_rib'],
//         ]);

//         return response()->json(['message' => 'Employé editer avec succès.', 'ok' => true]);

//     }


//     public function editEmployeeData()
//     {
//         $user = Auth::user();
//         $employee = $user->employee;
//         return view('modules.opti-hr.pages.personnel.membres.edits.index', compact('employee'));
//     }
//     public function updateEmployeeData(Request $request, $id)
//     {
//         $employee = Employee::findOrFail($id);



//         $validatedData = $request->validate([
//             'first_name' => 'max:255|string|required',
//             'last_name' => 'max:255|string|required',
//             'phone_number' => 'max:255|string|required',
//             'email' => 'max:255|string|required',
//             'gender' => 'max:255|string|required',
//             'address1' => 'max:255|string|sometimes',
//             'birth_date' => 'sometimes',

//             'nationality' => 'max:255|sometimes',
//             'religion' => 'max:255|sometimes',
//             'marital_status' => 'max:255|sometimes',
//             'emergency_contact' => 'max:255|sometimes',
//             'city' => 'max:255|sometimes',
//             'state' => 'max:255|sometimes',
//             'nationality' => 'max:255|sometimes',
//             'religion' => 'max:255|sometimes',
//             'marital_status' => 'max:255|sometimes',
//             'emergency_contact' => 'max:255|sometimes',
//             'city' => 'max:255|sometimes',
//             'state' => 'max:255|sometimes',

//             'bank_name' => 'max:255|sometimes',
//             'rib' => 'max:255|sometimes',
//             'code_bank' => 'max:255|sometimes',
//             'code_guichet' => 'max:255|sometimes',
//             'iban' => 'max:255|sometimes',
//             'swift' => 'max:255|sometimes',
//             'cle_rib' => 'max:255|sometimes',
//         ]);

//         $employee->update([
//             'nationality' => $validatedData['nationality'],
//             'religion' => $validatedData['religion'],
//             'marital_status' => $validatedData['marital_status'],
//             'emergency_contact' => $validatedData['emergency_contact'],
//             'city' => $validatedData['city'],
//             'state' => $validatedData['state'],

//             'bank_name' => $validatedData['bank_name'],
//             'rib' => $validatedData['rib'],
//             'code_bank' => $validatedData['code_bank'],
//             'code_guichet' => $validatedData['code_guichet'],
//             'iban' => $validatedData['iban'],
//             'swift' => $validatedData['swift'],
//             'cle_rib' => $validatedData['cle_rib'],
//             'bank_name' => $validatedData['bank_name'],
//             'rib' => $validatedData['rib'],
//             'code_bank' => $validatedData['code_bank'],
//             'code_guichet' => $validatedData['code_guichet'],
//             'iban' => $validatedData['iban'],
//             'swift' => $validatedData['swift'],
//             'cle_rib' => $validatedData['cle_rib'],

//             'first_name' => $validatedData['first_name'],
//             'last_name' => $validatedData['last_name'],
//             'phone_number' => $validatedData['phone_number'],
//             'email' => $validatedData['email'],
//             'gender' => $validatedData['gender'],
//             'address1' => $validatedData['address1'],
//             'birth_date' => $validatedData['birth_date'],
//             'first_name' => $validatedData['first_name'],
//             'last_name' => $validatedData['last_name'],
//             'phone_number' => $validatedData['phone_number'],
//             'email' => $validatedData['email'],
//             'gender' => $validatedData['gender'],
//             'address1' => $validatedData['address1'],
//             'birth_date' => $validatedData['birth_date'],

//         ]);

//         return response()->json(['message' => 'Employé editer avec succès.', 'ok' => true]);


//     }



// }
class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:voir-un-employee|configurer-un-employee|voir-un-tout'], ['only' => ['index']]);
        $this->middleware(['permission:créer-un-employee|créer-un-tout'], ['only' => ['store', 'update', 'create']]);
        $this->middleware(['permission:configurer-un-employee|configurer-un-tout'], ['only' => ['show', 'updateEmployeeData']]);
        $this->middleware(['permission:écrire-un-employee|écrire-un-tout'], ['only' => ['updatePres', 'updateBank']]);
        $this->middleware(['permission:configurer-un-employee|écrire-un-tout'], ['only' => ['destroy']]);
    }
    protected $evolutions = ['ON_GOING', 'ENDED', 'CANCEL', 'SUSPENDED', 'RESIGNED', 'DISMISSED'];
    protected $status = ['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED'];


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');     
        $limit = $request->input('limit', 5);   
        $page = $request->input('page', 1);  
        $departmentId = $request->input('deptValue', null); 
    
        // Construire la requête
        $query = DB::table('employees')
            ->join('duties', 'employees.id', '=', 'duties.employee_id')
            ->join('jobs', 'duties.job_id', '=', 'jobs.id')
            ->select('employees.*')
            ->where('duties.evolution', '=', $this->evolutions[0])
            // ->where('employees.status', '=', $this->status[0])
            ->where('duties.status', '=', $this->status[0])
            ->orderBy('created_at', 'desc');
        
        // Filtrer par département, si fourni
        if (!is_null($departmentId)) {
            
            $query->where('jobs.department_id', '=', $departmentId);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(phone_number) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(code) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(address1) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }
        
    
        // Ajouter la pagination
        $employees = $query->paginate($limit);
    
        // Retourner la réponse JSON
        return response()->json($employees);
    }
    
    
    function pages(){
        $departments = Department::orderBy('created_at', 'desc')->get();
        $query = DB::table('employees')
            ->join('duties', 'employees.id', '=', 'duties.employee_id')
            ->join('jobs', 'duties.job_id', '=', 'jobs.id')
            ->select('employees.*')
            ->where('duties.evolution', '=', $this->evolutions[0])
            // ->where('employees.status', '=', $this->status[0])
            ->where('duties.status', '=', $this->status[0])
            ->orderBy('created_at', 'desc');

        $nbre_employees = $query->count();
        return view('modules.opti-hr.pages.personnel.membres.index', compact('nbre_employees', 'departments'));
        
    }

    // function pay(){
    //     $departments = Department::orderBy('created_at', 'desc')->get();
    //     return view('pages.admin.personnel.membres.pay-form',compact('departments'));
    // }
    function paycode(){
        $departments = Department::orderBy('created_at', 'desc')->get();
        return view('modules.opti-hr.pages.personnel.membres.pay-form-code',compact('departments'));
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
    public function jobEmployees($id)
    {
        try {
            // Récupérer uniquement les noms et prénoms des employés liés aux devoirs
            $duties = Duty::where('evolution', $this->evolutions[0])
                ->where('job_id', $id)
                ->with(['employee:id,first_name,last_name,gender']) // Charge les employés avec seulement les champs nécessaires
                ->get()
                ->map(function ($duty) {
                    return [
                        'id' => $duty->employee->id,
                        'first_name' => $duty->employee->first_name,
                        'last_name' => $duty->employee->last_name,
                        'gender' => $duty->employee->gender,
                    ];
                });
                return response()->json([
                    'data' => $duties,
                ], 200);
                
            // return response()->json($duties, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'ok' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
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
            // Validation des données d'entrée
    
            $validatedData = $request->validate([
                'first_name' => 'required|max:255|string',
                'last_name' => 'required|max:255|string',
                'email' => 'required|email|max:255|unique:employees,email',
                'phone_number' => 'required|string|max:255|unique:employees,phone_number',
                'address1' => 'required|string|max:255',
                'gender' => 'required|in:MALE,FEMALE',
                'duration' => 'sometimes',
                'begin_date' => 'required|date',
                'type' => 'required|string|max:255',
                'job_id' => 'required|exists:jobs,id',
                'department_id' => 'required|exists:departments,id',
                'absence_balance' => 'required|numeric|min:0',
                // 'force_create' => 'sometimes|boolean',
            ]);
            

            // Récupération de la direction et du poste
            $dept = Department::find($validatedData['department_id']);
            $job = Job::find($validatedData['job_id']);

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
                    'absence_balance' => $validatedData['absence_balance']
                ]);

                // Mise à jour du directeur de la direction si applicable
                if ($dept->name === 'DG' || ($job->n_plus_one_job != null && $job->n_plus_one_job->title == 'DG')) {
                    $dept->update(['director_id' => $emp->id]);
                }
            }

            if ($dept->name === 'DG' && $dept->director_id !== null && $job->title === 'DG') {
                if ($request->input('force_create')==true) {
                    $old_header = Employee::find($dept->director_id);
                    $old_header->update(['status' => $this->status[3]]);

                    $old_header_duty = Duty::where('employee_id',$old_header->id)->where('evolution','ON_GOING');
                    $old_header_duty->update(['evolution' => $this->evolutions[1]]);
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
                        'absence_balance' => $validatedData['absence_balance']
                    ]);
                    $dept->update(['director_id' => $emp->id]);
                }
            } elseif ($dept->director_id !== null && $job->n_plus_one_job != null && $job->n_plus_one_job->title == 'DG') {
                if ($request->input('force_create')==true) {
                    $old_header = Employee::find($dept->director_id);
                    $old_header->update(['status' => 'DELETED']);

                    $old_header_duty = Duty::where('employee_id',$old_header->id)->where('evolution','ON_GOING');
                    $old_header_duty->update(['evolution' => $this->status[1]]);
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
                        'absence_balance' => $validatedData['absence_balance']
                    ]);
                    $dept->update(['director_id' => $emp->id]);
                }
            }


            return response()->json(['message' => 'Employé créé avec succès.', 'ok' => true]);
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


    /**
     * Display the specified resource.
     */
    public function mesFactures(Employee $employee){
        $files = File::where('employee_id', $employee->id)->get();
       
        return view('modules.opti-hr.pages.personnel.membres.employee-pay', compact('employee', 'files'));
    }

    public function show(Employee $employee)
    {
        $files = File::where('employee_id', $employee->id)->get();
        $duty = Duty::where('evolution', $this->evolutions[0])
                    ->where('employee_id', $employee->id)
                    ->first();

        return view('modules.opti-hr.pages.personnel.membres.show', compact('employee', 'duty', 'files'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
    }

    /**
     * Update the specified resource in storage. updatePresIdentity
     */
    public function updatePres(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        try {
            $validatedData = $request->validate([
                'nationality' => 'max:255|sometimes',
                'religion' => 'max:255|sometimes',
                'marital_status' => 'max:255|sometimes',
                'emergency_contact' => 'max:255|sometimes',
                'city' => 'max:255|sometimes',
                'state' => 'max:255|sometimes',
            ]);
            $employee->update([
                'nationality' => $validatedData['nationality'],
                'religion' => $validatedData['religion'],
                'marital_status' => $validatedData['marital_status'],
                'emergency_contact' => $validatedData['emergency_contact'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
            ]);

            return response()->json(['message' => 'Employé editer avec succès.', 'ok' => true]);
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

    public function updatePresIdentity(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        try {
            $validatedData = $request->validate([
                'first_name' => 'max:255|sometimes',
                'last_name' => 'max:255|sometimes',
                'phone_number' => 'max:255|sometimes',
                'address1' => 'max:255|sometimes',
                'birth_date' => 'max:255|sometimes',
                'email' => 'max:255|sometimes',
            ]);
            $employee->update([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'phone_number' => $validatedData['phone_number'],
                'address1' => $validatedData['address1'],
                'birth_date' => $validatedData['birth_date'],
                'email' => $validatedData['email'],
            ]);

            return response()->json(['message' => 'Employé editer avec succès.', 'ok' => true]);
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



    public function updateBank(Request $request, Employee $employee)
    {
        try {
            $validatedData = $request->validate([
                'bank_name' => 'max:255|sometimes',
                'rib' => 'max:255|sometimes',
                'code_bank' => 'max:255|sometimes',
                'code_guichet' => 'max:255|sometimes',
                'iban' => 'max:255|sometimes',
                'swift' => 'max:255|sometimes',
                'cle_rib' => 'max:255|sometimes',
            ]);
            $employee->update([
                'bank_name' => $validatedData['bank_name'],
                'rib' => $validatedData['rib'],
                'code_bank' => $validatedData['code_bank'],
                'code_guichet' => $validatedData['code_guichet'],
                'iban' => $validatedData['iban'],
                'swift' => $validatedData['swift'],
                'cle_rib' => $validatedData['cle_rib'],
            ]);

            return response()->json(['message' => 'Employé editer avec succès.', 'ok' => true]);
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
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
    }

    public function editEmployeeData()
        {
            $user = Auth::user();
            $employee = $user->employee;
            return view('modules.opti-hr.pages.personnel.membres.edits.index',compact('employee'));
        }

    public function updateEmployeeData(Request $request, $id){
        $employee = Employee::findOrFail($id);
        try {
            $validatedData = $request->validate([
                'first_name' => 'max:255|string|required',
                'last_name' => 'max:255|string|required',
                'phone_number' => 'max:255|string|required',
                'email' => 'max:255|string|required',
                'gender' => 'max:255|string|required',
                'address1' => 'max:255|string|sometimes',
                'birth_date' => 'sometimes',

                'nationality' => 'max:255|sometimes',
                'religion' => 'max:255|sometimes',
                'marital_status' => 'max:255|sometimes',
                'emergency_contact' => 'max:255|sometimes',
                'city' => 'max:255|sometimes',
                'state' => 'max:255|sometimes',

                'bank_name' => 'max:255|sometimes',
                'rib' => 'max:255|sometimes',
                'code_bank' => 'max:255|sometimes',
                'code_guichet' => 'max:255|sometimes',
                'iban' => 'max:255|sometimes',
                'swift' => 'max:255|sometimes',
                'cle_rib' => 'max:255|sometimes',
            ]);
            $employee->update([
                'nationality' => $validatedData['nationality'],
                'religion' => $validatedData['religion'],
                'marital_status' => $validatedData['marital_status'],
                'emergency_contact' => $validatedData['emergency_contact'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],

                'bank_name' => $validatedData['bank_name'],
                'rib' => $validatedData['rib'],
                'code_bank' => $validatedData['code_bank'],
                'code_guichet' => $validatedData['code_guichet'],
                'iban' => $validatedData['iban'],
                'swift' => $validatedData['swift'],
                'cle_rib' => $validatedData['cle_rib'],

                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'phone_number' => $validatedData['phone_number'],
                'email' => $validatedData['email'],
                'gender' => $validatedData['gender'],
                'address1' => $validatedData['address1'],
                'birth_date' => $validatedData['birth_date'],

            ]);

            return response()->json(['message' => 'Employé editer avec succès.', 'ok' => true]);
        }catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(), // Contient tous les messages d'erreur de validation
            ], 422);
        } catch (\Throwable $th) {
            return response()->json(['ok' => false, 'message' => $th->getMessage()], 500);
        }

    }    


}