<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Exécute le seeder pour remplir la table users avec des données réalistes.
     */
    public function run(): void
    {
        $employeeRole = Role::where(['name' => 'EMPLOYEE'])->first();

        // Ensemble de données prédéfinies pour les utilisateurs
        $usersData = [
            [
                'username' => 'testUser1',
                'picture' => 'assets/images/profile_av.png',
                'profile' => 'EMPLOYEE',
                'status' => 'ACTIVATED',
                'email' => 'testUser1@example.com',
                'password' => Hash::make('TestUser11234!'),
            ],
            [
                'username' => 'testUser2',
                'picture' => 'assets/images/profile_av.png',
                'profile' => 'EMPLOYEE',
                'status' => 'ACTIVATED',
                'email' => 'testUser2@example.com',
                'password' => Hash::make('TestUser21234!'),
            ],
            [
                'username' => 'testUser3',
                'picture' => 'assets/images/profile_av.png',
                'profile' => 'EMPLOYEE',
                'status' => 'ACTIVATED',
                'email' => 'testUser3@example.com',
                'password' => Hash::make('TestUser31234!'),
            ],
        ];

        // Insérer les utilisateurs prédéfinis
        foreach ($usersData as $userData) {
            // Créer un employé lié à l'utilisateur
            $employee = Employee::factory()->create();

            // Insérer l'utilisateur en associant l'employee_id
            $user = User::create(array_merge($userData, ['employee_id' => $employee->id]));
            $user->syncRoles([$employeeRole->id]);
        }
    }
}
