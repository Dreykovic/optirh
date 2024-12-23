<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
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

            session()->flash('success', "L'utilisateur  à été créé.");

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
            $this->validate($request, [
                'role' => 'required',

                'employee' => 'required|exists:employees,id',
            ]);
            $employee = Employee::findOrFail($request->input('employee'));
            $user = User::create([
                'username' => $request->input('firstname'),
                'email' => $employee->email,
                'password' => Hash::make('uk2024@'),
            ]);

            $user->syncRoles([$request->input('role')]);
            $currentUser = Auth::user();

            // session()->flash('success', "L'utilisateur *{$user->name} {$user->firstname}* à été créé.");
            // Envoyer le lien de réinitialisation du mot de passe
            $status = Password::sendResetLink(
                $request->only('email')
            );
            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['message' => __("Le compte à été créé et un lien est envoyé à l'utilisateur pour qu'il change de mode de passe"), 'ok' => true]);
            } else {
                return response()->json(['message' => __('Une erreur est survenue'), 'ok' => false]);
            }
        } catch (ValidationException $e) {
            // Gestion des erreurs de validation
            return response()->json([
                'ok' => false,
                'message' => 'Les données fournies sont invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            // Gestion des cas où le modèle n'est pas trouvé
            return response()->json([
                'ok' => false,
                'message' => 'Données introuvables. Veuillez vérifier les entrées.',
            ], 404);
        } catch (\Throwable $th) {
            // Gestion générale des erreurs
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
