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
        $adminRole = Role::where(['name' => 'ADMIN'])->first();
        $hrRole = Role::where(['name' => 'GRH'])->first();
        $dgRole = Role::where(['name' => 'DG'])->first();
        $employeeRole = Role::where(['name' => 'EMPLOYEE'])->first();
        // Création d'un administrateur
        $adminEmployee = Employee::create([
            'matricule' => 'ADM001',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'phone_number' => '1234567890',
            'address1' => '1 Admin Street',
            'city' => 'Admin City',
            'state' => 'AC',
            'country' => 'FR',
            'birth_date' => '1980-01-01',
            'nationality' => 'French',
            'status' => 'ACTIVATED',
        ]);

        $adminUser = User::create([
            'username' => 'admin_user',
            'email' => 'admin@example.com',
            'profile' => 'ADMIN',
            'status' => 'ACTIVATED',
            'password' => bcrypt('admin_password'),
            'employee_id' => $adminEmployee->id,
        ]);
        $adminUser->syncRoles([$adminRole->id]);

        // Création d'un responsable RH
        $hrEmployee = Employee::create([
            'matricule' => 'RH001',
            'first_name' => 'HR',
            'last_name' => 'Manager',
            'email' => 'hr@example.com',
            'phone_number' => '1234567891',
            'address1' => '2 HR Lane',
            'city' => 'HR City',
            'state' => 'HC',
            'country' => 'FR',
            'birth_date' => '1985-02-15',
            'nationality' => 'French',
            'status' => 'ACTIVATED',
        ]);

        $hrUser = User::create([
            'username' => 'hr_manager',
            'email' => 'hr@example.com',
            'profile' => 'EMPLOYEE',
            'status' => 'ACTIVATED',
            'password' => bcrypt('hr_password'),
            'employee_id' => $hrEmployee->id,
        ]);
        $hrUser->syncRoles([$hrRole->id]);

        // Création d'un directeur général
        $directorEmployee = Employee::create([
            'matricule' => 'DG001',
            'first_name' => 'Director',
            'last_name' => 'General',
            'email' => 'dg@example.com',
            'phone_number' => '1234567892',
            'address1' => '3 Director Avenue',
            'city' => 'Director City',
            'state' => 'DC',
            'country' => 'FR',
            'birth_date' => '1975-05-20',
            'nationality' => 'French',
            'status' => 'ACTIVATED',
        ]);

        $directorUser = User::create([
            'username' => 'director_general',
            'email' => 'dg@example.com',
            'profile' => 'EMPLOYEE',
            'status' => 'ACTIVATED',
            'password' => bcrypt('dg_password'),
            'employee_id' => $directorEmployee->id,
        ]);
        $directorUser->syncRoles([$dgRole->id]);

        // Création d'un employé standard
        $employee = Employee::create([
            'matricule' => 'EMP001',
            'first_name' => 'Employee',
            'last_name' => 'Standard',
            'email' => 'employee@example.com',
            'phone_number' => '1234567893',
            'address1' => '4 Employee Road',
            'city' => 'Employee City',
            'state' => 'EC',
            'country' => 'FR',
            'birth_date' => '1990-03-25',
            'nationality' => 'French',
            'status' => 'ACTIVATED',
        ]);

        $employeeUser = User::create([
            'username' => 'employee_standard',
            'email' => 'employee@example.com',
            'profile' => 'EMPLOYEE',
            'status' => 'ACTIVATED',
            'password' => bcrypt('employee_password'),
            'employee_id' => $employee->id,
        ]);
        $employeeUser->syncRoles([$employeeRole->id]);

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
