<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $employees = User::where('profile', '!=', 'CLIENT')->get();
            $roles = Role::whereNotIn('name', ['client', 'main', 'admin'])->get();

            return view('pages.admin.employee.index', compact('employees', 'roles'));
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

                'username' => 'required',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // validation pour l'image

                'email' => 'email|unique:users,email',
                'password' => 'required|min:8|confirmed']);

            $user = User::create([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'profile' => 'EMPLOYEE',
                'password' => Hash::make($request->input('password')),
            ]);
            // Upload de la nouvelle image si fournie
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $image_name = time().'.'.$photo->getClientOriginalExtension();
                $image_path = $photo->storeAs('images/employees/photos', $image_name, 'public'); // Stocker l'image
                $user->profile_picture = Storage::url($image_path);
            }
            $user->save();

            $user->syncRoles([$request->input('role')]);

            return response()->json(['ok' => true, 'message' => 'Utilisateur créé avec succès !'], 200);
        } catch (\Exception $e) {
            // return response()->json(['ok' => false, 'message' => 'Une erreur s\'est produite. Veuillez réessayer.']);
            return response()->json(['ok' => false, 'message' => $e->getMessage()]);
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
