<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
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

        // Liste des statuss valides
        $validStatus = ['ACTIVATED', 'DEACTIVATED', 'DELETED'];

        // Vérification de la validité du status
        if ($status !== 'ALL' && !in_array($status, $validStatus)) {
            return redirect()->back()->with('error', 'status invalide');
        }
        $query = User::where('profile', '!=', 'ADMIN')->with('employee');
        $query->where('status', '!=', 'DELETED');
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
        $query = $query->with(['roles' => function ($q1) {
            $q1->where('name', '!=', 'ADMIN');
        }])
            ->whereHas('roles', function ($q2) {
                $q2->where('name', '!=', 'ADMIN');
            })
            ->orderBy('username', 'ASC');

        $roles = Role::select('id', 'name')->where('name', '!=', 'ADMIN')->orderBy('id', 'ASC')->get();
        $roles = Role::select('id', 'name')->where('name', '!=', 'ADMIN')->orderBy('id', 'ASC')->get();

        $users = $query->get();
        // $roles = Role::whereNotIn('name', ['client', 'main', 'admin'])->get();
        $employeesWithoutUser = Employee::doesntHave('users')->get();
        $users = $query->get();
        // $roles = Role::whereNotIn('name', ['client', 'main', 'admin'])->get();
        $employeesWithoutUser = Employee::doesntHave('users')->get();

        // session()->flash('success', "L'utilisateur  à été créé.");
        // session()->flash('success', "L'utilisateur  à été créé.");

        return view('pages.admin.users.credentials.index', compact('users', 'roles', 'status', 'employeesWithoutUser'));

    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validation des données
        $this->validate($request, [
            'role' => 'required',
            'employee' => 'required|exists:employees,id',
        ]);

        // Validation des données
        $this->validate($request, [
            'role' => 'required',
            'employee' => 'required|exists:employees,id',
        ]);

        // Récupération de l'employé
        $employee = Employee::findOrFail($request->input('employee'));
        // Récupération de l'employé
        $employee = Employee::findOrFail($request->input('employee'));

        $username = strtolower(substr($employee->first_name, 0, 1)).strtolower($employee->last_name).$employee->id;
        $username = utf8_encode($username);
        $username = strtolower(substr($employee->first_name, 0, 1)).strtolower($employee->last_name).$employee->id;
        $username = utf8_encode($username);

        $randomString = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
        $pwd = strtolower(substr($employee->first_name, 0, 1)).ucfirst($employee->last_name).$randomString;
        $pwd = utf8_encode($pwd);
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
        // Création de l'utilisateur
        $user = User::create([
            'username' => $username,
            'email' => $employee->email,
            'password' => Hash::make($pwd),
            'employee_id' => $employee->id,
        ]);

        // Attribution des rôles
        $user->syncRoles([$request->input('role')]);
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
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        $user->status = $request->input('status');

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
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        $user->status = $request->input('status');

        $user->save();
        $user->save();

        session()->flash('success', 'Les détails ont été mis à jour.');
        session()->flash('success', 'Les détails ont été mis à jour.');

        return response()->json(['ok' => true, 'message' => 'Les détails de l\'utilisateur ont été mis à jour avec succès']);

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

        // Valider les fichiers et l'image
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password|confirmed',
        ]);
        $user = User::find($id);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['ok' => true, 'message' => 'Mot de passe actuel incorrect.'], 401);
        }
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['ok' => true, 'message' => 'Mot de passe actuel incorrect.'], 401);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        session()->flash('success', 'Le mot de passe à été mis à jour.');
        session()->flash('success', 'Le mot de passe à été mis à jour.');

        return response()->json(['ok' => true, 'message' => 'Mot de passe mis à jour avec succès !'], 200);

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

        // Valider les fichiers et l'image
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);
        $user = User::find($id);
        $user->password = Hash::make($request->input('password'));

        $user->save();
        session()->flash('success', 'Le mot de passe à été mis à jour.');
        $user->save();
        session()->flash('success', 'Le mot de passe à été mis à jour.');

        return response()->json(['message' => __(' Votre mot de passe a été mis à jour avec succès .'), 'ok' => true]);

        return response()->json(['message' => __(' Votre mot de passe a été mis à jour avec succès .'), 'ok' => true]);

    }

    public function updateRole(Request $request, string $id)
    {

        // Valider les fichiers et l'image
        $request->validate([
            'role' => 'required',
        ]);
        $user = User::find($id);
        $user->syncRoles([$request->input('role')]);

        // Valider les fichiers et l'image
        $request->validate([
            'role' => 'required',
        ]);
        $user = User::find($id);
        $user->syncRoles([$request->input('role')]);

        session()->flash('success', 'Le role à été mis à jour.');
        session()->flash('success', 'Le role à été mis à jour.');

        return response()->json(['ok' => true, 'message' => 'Le role de l\'utilisateur a été mis à jour avec succès']);

        return response()->json(['ok' => true, 'message' => 'Le role de l\'utilisateur a été mis à jour avec succès']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $currentUser = auth()->user();

        $currentUser = auth()->user();

        // Vérifie si l'utilisateur actuel correspond à l'ID à supprimer
        if ($currentUser->id == $id) {
            return response()->json(['ok' => false, 'message' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }
        // Vérifie si l'utilisateur actuel correspond à l'ID à supprimer
        if ($currentUser->id == $id) {
            return response()->json(['ok' => false, 'message' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        // Utiliser le modèle Eloquent pour déclencher l'événement deleted
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['ok' => true, 'message' => 'L\'utilisateur a été supprimé avec succès.']);

    }
}
