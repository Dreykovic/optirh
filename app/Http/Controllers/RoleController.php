<?php

namespace App\Http\Controllers;

use App\BladeDirectives\CustomDirectives;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:voir-un-role|écrire-un-role|créer-un-role|voir-un-tout'], ['only' => ['index', 'store', 'get_permissions', 'show']]);
        // $this->middleware(['permission:créer-un-tout'], ['only' => ['store']]);
        // $this->middleware(['permission:écrire-un-role|écrire-un-tout'], ['only' => ['update']]);
        // $this->middleware(['permission:écrire-un-tout'], ['only' => ['destroy']]);
    }

    public function get_permissions(Request $request)
    {
        try {
            $permissions = Permission::with([
                'roles' => function ($query) {
                    $query->where('name', '!=', 'ADMIN');
                },
            ])
                ->whereHas('roles', function ($query) {
                    $query->where('name', '!=', 'ADMIN');
                })
                ->orderBy('id', 'ASC')
                ->get();

            return view('pages.admin.users.permissions.index', compact('permissions'));
        } catch (\Exception $e) {
            // Gérez l'erreur ici, vous pouvez la logger ou retourner une réponse adaptée à l'erreur.
            return back()->with(['error' => 'Une erreur s\'est produite. Veuillez réessayer.']);

            // return back()->with(['error' => $e->getMessage()]);
        }
    }

    public function index(Request $request)
    {
        try {
            $roles = Role::with('permissions')->where('name', '!=', 'ADMIN')->orderBy('id', 'ASC')->get();
            $permissions = $this->trierPermissionsParCategory(Permission::orderBy('name', 'ASC')->get());

            $currentUser = Auth::user();
            if ($currentUser->hasRole('ADMIN')) {
                $roles = Role::with('permissions')->orderBy('id', 'ASC')->get();
                $permissions = $this->trierPermissionsParCategory(Permission::orderBy('name', 'ASC')->get());
            }

            return view('pages.admin.users.roles.index', compact('roles', 'permissions'));
        } catch (\Exception $e) {
            // Gérez l'erreur ici, vous pouvez la logger ou retourner une réponse adaptée à l'erreur.
            // return back()->with(['error' => 'Une erreur s\'est produite. Veuillez réessayer.']);

            return back()->with(['error' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'role_name' => 'required|unique:roles,name',
                'permissions' => 'required',
            ]);

            $role = Role::create(['name' => $request->input('role_name')]);
            $role->syncPermissions($request->input('permissions'));
            // Construct permission names string
            $currentUser = Auth::user();
            if (!$currentUser->hasRole('ADMIN')) {
                $permissionNames = '';
                foreach ($role->permissions as $key => $item) {
                    $permissionNames .= $item->name.', ';
                }
                $description = "Attribution des permissions : {$permissionNames}";

                Journal::create([
                    'titre' => "Création du rôle **{$role->name}**",
                    'description' => $description,
                    'user_id' => $currentUser->id,
                ]);
            }
            session()->flash('success', "Le role **{$role->name}**  à été créé.");

            return response()->json(['ok' => true, 'message' => 'Le role a été créé avec succès']);
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'message' => 'Une erreur s\'est produite. Veuillez réessayer.']);
        }
    }

    public function show($id)
    {
        try {
            $role = Role::with(['users', 'permissions'])->findOrFail($id);
            // dd($role);

            $all_permissions = Permission::with([
                'roles',
            ])
                ->whereHas('roles')
                ->orderBy('id', 'ASC')
                ->get();
            $currentUser = Auth::user();

            if (!$currentUser->hasRole('ADMIN')) {
                $role = Role::with('users')->with('permissions')->where('name', '!=', 'ADMIN')->findOrFail($id);
                // dd($role);

                $all_permissions = Permission::with([
                    'roles' => function ($query) {
                        $query->where('name', '!=', 'ADMIN');
                    },
                ])
                    ->whereHas('roles', function ($query) {
                        $query->where('name', '!=', 'ADMIN');
                    })
                    ->orderBy('id', 'ASC')
                    ->get();
            }

            // dd($permissions);

            $permissions = $this->trierPermissionsParCategory($all_permissions);

            return view('pages.admin.users.roles.details.index', compact('role', 'permissions'));
        } catch (\Exception $e) {
            // Gérez l'erreur ici, vous pouvez la logger ou retourner une réponse adaptée à l'erreur.
            return back()->with(['error' => $e->getMessage()]);

            return back()->with(['error' => 'Une erreur s\'est produite. Veuillez réessayer.']);
        }
    }

    public function trierPermissionsParCategory($permissions)
    {
        $sortie = [];

        foreach ($permissions as $permission) {
            $nomPermission = $permission->name; // Supposons que le nom de la permission est dans la propriété "nom"
            $categorie = explode('-', $nomPermission, 3)[2]; // Récupère la catégorie de la permission

            if (!array_key_exists($categorie, $sortie)) {
                $sortie[$categorie] = [];
            }

            $sortie[$categorie][] = $permission; // Ajoute la permission à sa catégorie correspondante
        }

        return $sortie;
    }

    public function update(Request $request, $encryptId)
    {
        try {
            $this->validate($request, [
                'role_name' => 'required',
                'permissions' => 'required|array', // Ensure 'permissions' is an array
            ]);

            $id = CustomDirectives::decryptId($encryptId);
            $webMaster = Role::where('name', 'ADMIN')->first();
            $currentUser = Auth::user();

            if ($webMaster->id != $id || $currentUser->hasRole('ADMIN') && $webMaster->id == $id) {
                $role = Role::find($id);
                $role->name = $request->input('role_name');
                $role->save();

                $role->syncPermissions($request->input('permissions'));

                if (!$currentUser->hasRole('ADMIN')) {
                    // Construct permission names string
                    $permissionNames = '';
                    foreach ($role->permissions as $key => $item) {
                        $permissionNames .= $item->name.', ';
                    }

                    $description = " Attribution des permissions : {$permissionNames}";

                    Journal::create([
                        'titre' => "Mis à jour du rôle **{$role->name}**",
                        'description' => $description,
                        'user_id' => $currentUser->id,
                    ]);
                }
                session()->flash('success', "Le rôle *{$role->name}* a été mis à jour.");

                return response()->json(['ok' => true, 'message' => 'Le rôle a été mis à jour avec succès']);
            } else {
                return response()->json(['ok' => false, 'message' => 'Permission non accordée.']);
            }
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'message' => 'Une erreur s\'est produite. Veuillez réessayer.']);
        }
    }

    public function destroy($encryptId)
    {
        try {
            $id = CustomDirectives::decryptId($encryptId);

            $webMaster = Role::where('name', 'ADMIN')->first();

            if ($webMaster->id != $id) {
                $role = Role::select('name')->find($id);
                DB::table('roles')->where('id', $id)->delete();
                $currentUser = Auth::user();
                if (!$currentUser->hasRole('ADMIN')) {
                    Journal::create([
                        'titre' => "Supression du role **{$role->name}**",
                        'user_id' => $currentUser->id,
                    ]);
                }

                return response()->json(['ok' => true, 'message' => 'le role à été supprimé avec succès.']);
            } else {
                return response()->json(['ok' => false, 'message' => 'Permission non accordée.']);
            }
        } catch (\Exception $e) {
            return response()->json(['ok' => false, 'message' => 'Une erreur s\'est produite. Veuillez réessayer.']);
        }
    }
}
