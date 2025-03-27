<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return view('modules.opti-hr.pages.users.permissions.index', compact('permissions'));

    }

    public function index(Request $request)
    {

        $roles = Role::with('permissions')->where('name', '!=', 'ADMIN')->orderBy('id', 'ASC')->get();
        // $permissions = $this->trierPermissionsParCategory(Permission::orderBy('name', 'ASC')->get());
        $permissions = Permission::orderBy('name', 'ASC')->get();
        $currentUser = Auth::user();
        if ($currentUser->hasRole('ADMIN')) {
            $roles = Role::with('permissions')->orderBy('id', 'ASC')->get();
            // $permissions = $this->trierPermissionsParCategory(Permission::orderBy('name', 'ASC')->get());

        }

        return view('modules.opti-hr.pages.users.roles.index', compact('roles', 'permissions'));

    }

    public function show($id)
    {

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

        return view('modules.opti-hr.pages.users.roles.details.index', compact('role', 'permissions'));

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
}
