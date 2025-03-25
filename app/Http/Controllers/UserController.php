<?php

namespace App\Http\Controllers;

use App\Models\OptiHr\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Services\ActivityLogService;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct(app(ActivityLogService::class)); // Injection automatique

        $this->middleware(['permission:voir-un-credentials|écrire-un-credentials|créer-un-credentials|configurer-un-credentials|voir-un-tout'], ['only' => ['index']]);
        $this->middleware(['permission:créer-un-credentials|créer-un-tout'], ['only' => ['store']]);
        // $this->middleware(['permission:écrire-un-utilisateur|écrire-un-tout'], ['only' => ['destroy', 'destroyAll']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index($status = 'ALL')
    {
        // Liste des statuss valides
        $validStatus = ['ACTIVATED', 'DEACTIVATED', 'DELETED'];

        // Vérification de la validité du status
        if ($status !== 'ALL' && !in_array($status, $validStatus)) {
            $this->activityLogger->log(
                'error',
                "Tentative d'accès à la liste des utilisateurs avec un statut invalide: {$status}"
            );
            return redirect()->back()->with('error', 'status invalide');
        }
        $query = User::where('profile', '!=', 'ADMIN')->with('employee');
        $query->where('status', '!=', 'DELETED');

        // Filtrer par status si le status n'est pas "ALL"
        $query->when($status !== 'ALL', function ($q) use ($status) {
            $q->where('status', $status);
        });

        $query = $query->with([
            'roles' => function ($q1) {
                $q1->where('name', '!=', 'ADMIN');
            }
        ])
            ->whereHas('roles', function ($q2) {
                $q2->where('name', '!=', 'ADMIN');
            })
            ->orderBy('username', 'ASC');

        $roles = Role::select('id', 'name')->where('name', '!=', 'ADMIN')->orderBy('id', 'ASC')->get();

        $users = $query->get();
        $employeesWithoutUser = Employee::doesntHave('users')->get();

        $this->activityLogger->log(
            'view',
            "Consultation de la liste des utilisateurs - Statut: {$status}"
        );

        return view('modules.opti-hr.pages.users.credentials.index', compact('users', 'roles', 'status', 'employeesWithoutUser'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données avec permission optionnelle
        $this->validate($request, [
            'role' => 'required',
            'employee' => 'required|exists:employees,id',
            'permission' => 'nullable|exists:permissions,name', // Permission optionnelle
        ]);

        // Récupération de l'employé
        $employee = Employee::findOrFail($request->input('employee'));

        $username = strtolower(substr($employee->first_name, 0, 1)) . strtolower($employee->last_name) . $employee->id;
        $username = utf8_encode($username);

        $randomString = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
        $pwd = strtolower(substr($employee->first_name, 0, 1)) . ucfirst($employee->last_name) . $randomString;
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

        // Attribution de la permission supplémentaire si présente
        if ($request->has('permission') && $request->input('permission')) {
            $user->givePermissionTo($request->input('permission'));
        }

        Mail::send('emails.user-credentials', [
            'email' => $user->email,
            'password' => $pwd,
            'loginLink' => route('login')
        ], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Vos identifiants OptiRh');
        });

        // Envoi du lien de réinitialisation de mot de passe
        $status = Password::sendResetLink(['email' => $employee->email]);

        // Journalisation de la création d'utilisateur
        $this->activityLogger->log(
            'created',
            "Création d'un utilisateur avec nom: {$user->username} et email: {$user->email}",
            $user,
            [
                'role' => $request->input('role'),
                'permission' => $request->input('permission'),
                'reset_link_sent' => ($status === Password::RESET_LINK_SENT)
            ]
        );

        // Notification à l'utilisateur actuel
        session()->flash('success', "L'utilisateur avec le nom *{$user->username}* et l'email *{$user->email}* a été créé. 
            Mot de passe *{$pwd}*. Retenez-le ou notez-le quelque part, il ne sera plus affiché.");

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => "L'utilisateur avec le nom {$user->username} et l'email {$user->email} a été créé.et un lien de réinitialisation de mot de passe a été envoyé à l'utilisateur.", 'ok' => true]);
        } else {
            return response()->json(['message' => "L'utilisateur avec le nom {$user->username} et l'email {$user->email} a été créé", 'ok' => true]);
        }
    }

    public function updateDetails(Request $request, string $id)
    {
        // Valider les fichiers et l'image
        $request->validate([
            'email' => [
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'username' => [
                'string',
                Rule::unique('users', 'username')->ignore($id),
            ],
            'status' => 'required|in:ACTIVATED,DEACTIVATED',
        ]);

        $user = User::find($id);
        $oldEmail = $user->email;
        $oldUsername = $user->username;
        $oldStatus = $user->status;

        $user->email = $request->input('email');
        $user->username = $request->input('username');
        $user->status = $request->input('status');

        $user->save();

        // Journalisation de la mise à jour des détails
        $this->activityLogger->log(
            'updated',
            "Mise à jour des détails de l'utilisateur {$user->username}",
            $user,
            [
                'old_email' => $oldEmail,
                'new_email' => $user->email,
                'old_username' => $oldUsername,
                'new_username' => $user->username,
                'old_status' => $oldStatus,
                'new_status' => $user->status
            ]
        );

        session()->flash('success', 'Les détails ont été mis à jour.');

        return response()->json(['ok' => true, 'message' => 'Les détails de l\'utilisateur ont été mis à jour avec succès']);
    }

    public function updatePassword(Request $request, string $id)
    {
        // Valider les fichiers et l'image
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password|confirmed',
        ]);

        $user = User::find($id);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            $this->activityLogger->log(
                'denied',
                "Tentative de modification de mot de passe échouée pour l'utilisateur {$user->username} - Mot de passe actuel incorrect",
                $user
            );
            return response()->json(['ok' => true, 'message' => 'Mot de passe actuel incorrect.'], 401);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        $this->activityLogger->log(
            'updated',
            "Modification du mot de passe de l'utilisateur {$user->username}",
            $user
        );

        session()->flash('success', 'Le mot de passe à été mis à jour.');

        return response()->json(['ok' => true, 'message' => 'Mot de passe mis à jour avec succès !'], 200);
    }

    public function changePassword(Request $request, string $id)
    {
        // Valider les fichiers et l'image
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::find($id);
        $user->password = Hash::make($request->input('password'));

        $user->save();

        $this->activityLogger->log(
            'updated',
            "Changement de mot de passe pour l'utilisateur {$user->username}",
            $user
        );

        session()->flash('success', 'Le mot de passe à été mis à jour.');

        return response()->json(['message' => __(' Votre mot de passe a été mis à jour avec succès .'), 'ok' => true]);
    }

    public function updateRole(Request $request, string $id)
    {
        // Valider les fichiers et l'image
        $request->validate([
            'role' => 'required',
        ]);

        $user = User::find($id);
        $oldRoles = $user->roles->pluck('name')->toArray();

        $user->syncRoles([$request->input('role')]);

        $this->activityLogger->log(
            'updated',
            "Mise à jour du rôle de l'utilisateur {$user->username}",
            $user,
            [
                'old_roles' => $oldRoles,
                'new_role' => $request->input('role')
            ]
        );

        session()->flash('success', 'Le role à été mis à jour.');

        return response()->json(['ok' => true, 'message' => 'Le role de l\'utilisateur a été mis à jour avec succès']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $currentUser = auth()->user();

        // Vérifie si l'utilisateur actuel correspond à l'ID à supprimer
        if ($currentUser->id == $id) {
            $this->activityLogger->log(
                'denied',
                "Tentative de suppression de son propre compte utilisateur",
                $currentUser
            );
            return response()->json(['ok' => false, 'message' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        // Utiliser le modèle Eloquent pour déclencher l'événement deleted
        $user = User::findOrFail($id);
        $username = $user->username;

        $user->delete();

        $this->activityLogger->log(
            'deleted',
            "Suppression de l'utilisateur {$username}",
            null,
            [
                'deleted_user_id' => $id,
                'deleted_user_name' => $username
            ]
        );

        return response()->json(['ok' => true, 'message' => 'L\'utilisateur a été supprimé avec succès.']);
    }
}