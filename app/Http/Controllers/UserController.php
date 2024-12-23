<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:voir-un-compte|écrire-un-compte|créer-un-compte|configurer-un-compte|voir-un-tout'], ['only' => ['index']]);
        $this->middleware(['permission:créer-un-compte|créer-un-tout'], ['only' => ['store']]);
        // $this->middleware(['permission:écrire-un-utilisateur|écrire-un-tout'], ['only' => ['destroy', 'destroyAll']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index($status = 'ALL')
    {
        try {
            // Liste des statuss valides
            $validStatus = ['ACTIVATED', 'DEACTIVATED', 'DELETED'];

            // Vérification de la validité du status
            if ($status !== 'ALL' && !in_array($status, $validStatus)) {
                return redirect()->back()->with('error', 'status invalide');
            }
            $query = User::where('profile', '!=', 'ADMIN')->with('employee');
            $query->where('status', '!=', 'DELETED');

            // Filtrer par status si le status n'est pas "ALL"
            $query->when($status !== 'ALL', function ($q) use ($status) {
                $q->where('status', $status);
            });

            $query = $query->with(['roles' => function ($q1) {
                $q1->where('name', '!=', 'ADMIN');
            }])
                ->whereHas('roles', function ($q2) {
                    $q2->where('name', '!=', 'ADMIN');
                })
                ->orderBy('username', 'ASC');

            $roles = Role::select('id', 'name')->where('name', '!=', 'ADMIN')->orderBy('id', 'ASC')->get();

            $users = $query->get();
            // $roles = Role::whereNotIn('name', ['client', 'main', 'admin'])->get();
            $employeesWithoutUser = Employee::doesntHave('users')->get();

            // session()->flash('success', "L'utilisateur  à été créé.");

            return view('pages.admin.users.credentials.index', compact('users', 'roles', 'status', 'employeesWithoutUser'));
        } catch (\Throwable $th) {
            dd($th->getMessage());
            abort(500);
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
            // Validation des données
            $this->validate($request, [
                'role' => 'required',
                'employee' => 'required|exists:employees,id',
            ]);

            // Récupération de l'employé
            $employee = Employee::findOrFail($request->input('employee'));

            $username = strtolower(substr($employee->first_name, 0, 1)).strtolower($employee->last_name).$employee->id;
            $username = utf8_encode($username);

            $randomString = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
            $pwd = strtolower(substr($employee->first_name, 0, 1)).ucfirst($employee->last_name).$randomString;
            $pwd = utf8_encode($pwd);

            // Création de l'utilisateur
            $user = User::create([
                'username' => $username,
                'email' => $employee->email,
                'password' => Hash::make($pwd),
                'employee_id' => $employee->id,
            ]);

            // Attribution des rôles
            $user->syncRoles([$request->input('role')]);

            // Notification à l'utilisateur actuel
            session()->flash('success', "L'utilisateur avec le nom *{$user->username}* et l'email *{$user->email}* a été créé. 
            Mot de passe *{$pwd}*. Retenez-le ou notez-le quelque part, il ne sera plus affiché.");

            return response()->json(['message' => "L'utilisateur avec le nom {$user->username} et l'email {$user->email} a été créé.et un lien de réinitialisation de mot de passe a été envoyé à l'utilisateur.",  'ok' => true]);
            // Envoi du lien de réinitialisation de mot de passe
            // $status = Password::sendResetLink(['email' => $employee->email]);
            // if ($status === Password::RESET_LINK_SENT) {
            //     return response()->json(['message' => "L'utilisateur avec le nom {$user->username} et l'email {$user->email} a été créé.et un lien de réinitialisation de mot de passe a été envoyé à l'utilisateur.",  'ok' => true]);
            // } else {
            //     return response()->json(['message' => __('Une erreur est survenue lors de l\'envoi du lien de réinitialisation.'), 'ok' => false]);
            // }
        } catch (ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Données introuvables. Veuillez vérifier les entrées.',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'ok' => false,
                'message' => 'Une erreur s’est produite. Veuillez réessayer.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }
}
