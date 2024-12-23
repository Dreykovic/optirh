<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Exécute le seeder pour remplir la table users avec des données réalistes.
     */
    public function run(): void
    {
        // Liste des profils possibles pour les utilisateurs
        $profiles = ['EMPLOYEE', 'ADMIN'];

        // Liste des statuts possibles pour les utilisateurs
        $statuses = ['ACTIVATED', 'DEACTIVATED', 'DELETED'];

        // Ensemble de données prédéfinies pour les utilisateurs
        $usersData = [
            [
                'username' => 'admin.master',
                'picture' => 'assets/images/profile_av.png',
                'profile' => 'ADMIN',
                'status' => 'ACTIVATED',
                'email' => 'admin@example.com',
                'password' => Hash::make('Admin1234!'),
            ],
            [
                'username' => 'employee.johndoe',
                'picture' => 'assets/images/profile_av.png',
                'profile' => 'EMPLOYEE',
                'status' => 'ACTIVATED',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('JohnDoe2024!'),
            ],
            [
                'username' => 'employee.janedoe',
                'picture' => 'assets/images/profile_av.png',
                'profile' => 'EMPLOYEE',
                'status' => 'ACTIVATED',
                'email' => 'jane.doe@example.com',
                'password' => Hash::make('JaneDoe2024!'),
            ],
            [
                'username' => 'manager.robert',
                'picture' => 'assets/images/profile_av.png',
                'profile' => 'ADMIN',
                'status' => 'DEACTIVATED',
                'email' => 'robert.manager@example.com',
                'password' => Hash::make('Manager2024!'),
            ],
            [
                'username' => 'employee.alice',
                'picture' => 'assets/images/profile_av.png',
                'profile' => 'EMPLOYEE',
                'status' => 'ACTIVATED',
                'email' => 'alice.smith@example.com',
                'password' => Hash::make('AliceSmith2024!'),
            ],
        ];

        // Insérer les utilisateurs prédéfinis
        foreach ($usersData as $userData) {
            // Créer un employé lié à l'utilisateur
            $employee = Employee::factory()->create();

            // Insérer l'utilisateur en associant l'employee_id
            User::create(array_merge($userData, ['employee_id' => $employee->id]));
        }

        // Générer 20 utilisateurs supplémentaires de manière aléatoire
        User::factory()
            ->count(20)
            ->create();
    }
}
