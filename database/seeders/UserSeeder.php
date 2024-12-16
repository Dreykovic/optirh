<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accountant = User::create([
            'username' => 'Accountant',
            'email' => 'accountant@micro.com',
            'profile' => 'EMPLOYEE',
            'password' => Hash::make('accountant'),
        ]);
        $cashier = User::create([
            'username' => 'Cashier',
            'profile' => 'EMPLOYEE',

            'email' => 'cashier@micro.com',
            'password' => Hash::make('cashier'),
        ]);

        $admin = User::create([
            'username' => 'Admin',
            'profile' => 'EMPLOYEE',
            'email' => 'admin@micro.com',
            'password' => Hash::make('admin'),
        ]);

        $boss = User::create([
            'profile' => 'EMPLOYEE',
            'username' => 'Boss',

            'email' => 'boss@micro.com',
            'password' => Hash::make('boss'),
        ]);
        $assistant = User::create([
            'profile' => 'ASSISTANT',
            'username' => 'assistant',

            'email' => 'assistant@micro.com',
            'password' => Hash::make('assistant'),
        ]);

        $admin_role = Role::where('name', 'admin')->first();
        $boss_role = Role::where('name', 'boss')->first();
        $cashier_role = Role::where('name', 'cashier')->first();
        $accountant_role = Role::where('name', 'accountant')->first();
        $client_role = Role::where('name', 'client')->first();
        $admin->assignRole([$admin_role->id]);
        $boss->assignRole([$boss_role->id]);
        $cashier->assignRole([$cashier_role->id]);
        $accountant->assignRole([$accountant_role->id]);
    }
}
