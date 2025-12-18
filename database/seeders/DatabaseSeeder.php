<?php

namespace Database\Seeders;

use App\Models\OptiHr\Department;
use App\Models\OptiHr\Duty;
use App\Models\OptiHr\Employee;
use App\Models\OptiHr\Job;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AbsenceTypeSeeder::class,
            DocumentTypeSeeder::class,
            HolidaySeeder::class,
        ]);

        // Récupération des rôles
        $adminRole = Role::where(['name' => 'ADMIN'])->first();
        $hrRole = Role::where(['name' => 'GRH'])->first();
        $dgRole = Role::where(['name' => 'DG'])->first();
        $dsafRole = Role::where(['name' => 'DSAF'])->first();
        $employeeRole = Role::where(['name' => 'EMPLOYEE'])->first();

        // ============================================================
        // 1. ADMIN (webmaster) - Employé système
        // ============================================================
        $adminEmployee = Employee::create([
            'matricule' => 'ADMIN001',
            'first_name' => 'Administrateur',
            'last_name' => 'SYSTÈME',
            'gender' => 'MALE',
            'email' => 'dreybirewa@gmail.com',
            'phone_number' => '00000000',
            'address1' => 'Système',
            'city' => 'Lomé',
            'state' => 'Maritime',
            'country' => 'Togo',
            'birth_date' => '2000-01-01',
            'nationality' => 'Togolaise',
            'status' => 'ACTIVATED',
            'code' => 'ADMIN-SYS',
        ]);

        $adminUser = User::create([
            'username' => 'admin',
            'email' => 'dreybirewa@gmail.com',
            'profile' => 'ADMIN',
            'status' => 'ACTIVATED',
            'password' => bcrypt('Admin@2024'),
            'employee_id' => $adminEmployee->id,
        ]);
        $adminUser->syncRoles([$adminRole->id]);

        // ============================================================
        // 2. EMPLOYÉS avec noms fictifs
        // ============================================================

        // Directeur Général
        $dgEmployee = Employee::create([
            'matricule' => 'DG001',
            'first_name' => 'Jean',
            'last_name' => 'DUPONT',
            'gender' => 'MALE',
            'email' => 'codeurspassiones@gmail.com',
            'phone_number' => '90001001',
            'address1' => '1 Avenue de la Direction',
            'city' => 'Lomé',
            'state' => 'Maritime',
            'country' => 'Togo',
            'birth_date' => '1970-03-15',
            'nationality' => 'Togolaise',
            'status' => 'ACTIVATED',
            'code' => 'DG-01',
        ]);

        // Directeur DSAF
        $dsafEmployee = Employee::create([
            'matricule' => 'DSAF001',
            'first_name' => 'Marie',
            'last_name' => 'MARTIN',
            'gender' => 'FEMALE',
            'email' => 'amonaaudrey@hotmail.com',
            'phone_number' => '90002001',
            'address1' => '2 Rue des Finances',
            'city' => 'Lomé',
            'state' => 'Maritime',
            'country' => 'Togo',
            'birth_date' => '1975-07-22',
            'nationality' => 'Togolaise',
            'status' => 'ACTIVATED',
            'code' => 'DSAF-01',
        ]);

        // Chef RH
        $hrEmployee = Employee::create([
            'matricule' => 'RH001',
            'first_name' => 'Pierre',
            'last_name' => 'DURAND',
            'gender' => 'MALE',
            'email' => 'amonaaudrey16@gmail.com',
            'phone_number' => '90003001',
            'address1' => '3 Boulevard RH',
            'city' => 'Lomé',
            'state' => 'Maritime',
            'country' => 'Togo',
            'birth_date' => '1980-11-10',
            'nationality' => 'Togolaise',
            'status' => 'ACTIVATED',
            'code' => 'RH-01',
        ]);

        // Comptable
        $comptableEmployee = Employee::create([
            'matricule' => 'COMPTA001',
            'first_name' => 'François',
            'last_name' => 'LAURENT',
            'gender' => 'MALE',
            'email' => 'employee1@optirh.com',
            'phone_number' => '90004001',
            'address1' => '4 Rue de la Comptabilité',
            'city' => 'Lomé',
            'state' => 'Maritime',
            'country' => 'Togo',
            'birth_date' => '1988-04-05',
            'nationality' => 'Togolaise',
            'status' => 'ACTIVATED',
            'code' => 'COMPTA-01',
        ]);

        // Assistante DG
        $assistanteEmployee = Employee::create([
            'matricule' => 'ASST001',
            'first_name' => 'Isabelle',
            'last_name' => 'MICHEL',
            'gender' => 'FEMALE',
            'email' => 'employee2@optirh.com',
            'phone_number' => '90005001',
            'address1' => '5 Avenue du Cabinet',
            'city' => 'Lomé',
            'state' => 'Maritime',
            'country' => 'Togo',
            'birth_date' => '1990-09-18',
            'nationality' => 'Togolaise',
            'status' => 'ACTIVATED',
            'code' => 'ASST-01',
        ]);

        // ============================================================
        // 3. DÉPARTEMENTS (7)
        // ============================================================
        $dgDpt = Department::create([
            'name' => 'DG',
            'description' => 'Direction Générale',
            'director_id' => $dgEmployee->id,
            'status' => 'ACTIVATED',
        ]);

        $dsafDpt = Department::create([
            'name' => 'DSAF',
            'description' => 'Direction des Services Administratifs et Financiers',
            'director_id' => $dsafEmployee->id,
            'status' => 'ACTIVATED',
        ]);

        $dieDpt = Department::create([
            'name' => 'DIE',
            'description' => 'Direction des Investigations et Enquêtes',
            'director_id' => null,
            'status' => 'ACTIVATED',
        ]);

        $dfatDpt = Department::create([
            'name' => 'DFAT',
            'description' => 'Direction de la Formation et des Appuis Techniques',
            'director_id' => null,
            'status' => 'ACTIVATED',
        ]);

        $drajDpt = Department::create([
            'name' => 'DRAJ',
            'description' => 'Direction de la Réglementation et des Affaires Juridiques',
            'director_id' => null,
            'status' => 'ACTIVATED',
        ]);

        $dsdseDpt = Department::create([
            'name' => 'DSDSE',
            'description' => 'Direction des Statistiques et de la Documentation',
            'director_id' => null,
            'status' => 'ACTIVATED',
        ]);

        $dcrpDpt = Department::create([
            'name' => 'DCRP',
            'description' => 'Direction de la Communication et des Relations Publiques',
            'director_id' => null,
            'status' => 'ACTIVATED',
        ]);

        // ============================================================
        // 4. POSTES (JOBS) avec hiérarchie n+1
        // ============================================================

        // Poste DG (sommet de la hiérarchie)
        $dgJob = Job::create([
            'title' => 'Directeur Général',
            'description' => 'Directeur Général de l\'organisation',
            'department_id' => $dgDpt->id,
            'status' => 'ACTIVATED',
            'n_plus_one_job_id' => null,
        ]);

        // Assistante DG (n+1 = DG)
        $assistanteDgJob = Job::create([
            'title' => 'Assistante de Direction',
            'description' => 'Assistante du Directeur Général',
            'department_id' => $dgDpt->id,
            'status' => 'ACTIVATED',
            'n_plus_one_job_id' => $dgJob->id,
        ]);

        // Directeur DSAF (n+1 = DG)
        $dsafJob = Job::create([
            'title' => 'Directeur DSAF',
            'description' => 'Directeur des Services Administratifs et Financiers',
            'department_id' => $dsafDpt->id,
            'status' => 'ACTIVATED',
            'n_plus_one_job_id' => $dgJob->id,
        ]);

        // Chef RH (n+1 = DSAF)
        $hrJob = Job::create([
            'title' => 'Chef Service RH',
            'description' => 'Chef du Service des Ressources Humaines',
            'department_id' => $dsafDpt->id,
            'status' => 'ACTIVATED',
            'n_plus_one_job_id' => $dsafJob->id,
        ]);

        // Comptable (n+1 = Chef RH)
        $comptableJob = Job::create([
            'title' => 'Comptable',
            'description' => 'Agent comptable',
            'department_id' => $dsafDpt->id,
            'status' => 'ACTIVATED',
            'n_plus_one_job_id' => $hrJob->id,
        ]);

        // Directeurs des autres départements (n+1 = DG)
        $dieJob = Job::create([
            'title' => 'Directeur DIE',
            'description' => 'Directeur des Investigations et Enquêtes',
            'department_id' => $dieDpt->id,
            'status' => 'ACTIVATED',
            'n_plus_one_job_id' => $dgJob->id,
        ]);

        $dfatJob = Job::create([
            'title' => 'Directeur DFAT',
            'description' => 'Directeur de la Formation et des Appuis Techniques',
            'department_id' => $dfatDpt->id,
            'status' => 'ACTIVATED',
            'n_plus_one_job_id' => $dgJob->id,
        ]);

        $drajJob = Job::create([
            'title' => 'Directeur DRAJ',
            'description' => 'Directeur de la Réglementation et des Affaires Juridiques',
            'department_id' => $drajDpt->id,
            'status' => 'ACTIVATED',
            'n_plus_one_job_id' => $dgJob->id,
        ]);

        $dsdseJob = Job::create([
            'title' => 'Directeur DSDSE',
            'description' => 'Directeur des Statistiques et de la Documentation',
            'department_id' => $dsdseDpt->id,
            'status' => 'ACTIVATED',
            'n_plus_one_job_id' => $dgJob->id,
        ]);

        $dcrpJob = Job::create([
            'title' => 'Directeur DCRP',
            'description' => 'Directeur de la Communication et des Relations Publiques',
            'department_id' => $dcrpDpt->id,
            'status' => 'ACTIVATED',
            'n_plus_one_job_id' => $dgJob->id,
        ]);

        // ============================================================
        // 5. AFFECTATIONS (DUTIES)
        // ============================================================
        Duty::create([
            'duration' => 12,
            'begin_date' => '2023-01-01',
            'type' => 'Full-Time',
            'status' => 'ACTIVATED',
            'evolution' => 'ON_GOING',
            'absence_balance' => 30,
            'job_id' => $dgJob->id,
            'employee_id' => $dgEmployee->id,
        ]);

        Duty::create([
            'duration' => 12,
            'begin_date' => '2023-01-01',
            'type' => 'Full-Time',
            'status' => 'ACTIVATED',
            'evolution' => 'ON_GOING',
            'absence_balance' => 30,
            'job_id' => $dsafJob->id,
            'employee_id' => $dsafEmployee->id,
        ]);

        Duty::create([
            'duration' => 12,
            'begin_date' => '2023-01-01',
            'type' => 'Full-Time',
            'status' => 'ACTIVATED',
            'evolution' => 'ON_GOING',
            'absence_balance' => 30,
            'job_id' => $hrJob->id,
            'employee_id' => $hrEmployee->id,
        ]);

        Duty::create([
            'duration' => 12,
            'begin_date' => '2023-01-01',
            'type' => 'Full-Time',
            'status' => 'ACTIVATED',
            'evolution' => 'ON_GOING',
            'absence_balance' => 30,
            'job_id' => $comptableJob->id,
            'employee_id' => $comptableEmployee->id,
        ]);

        Duty::create([
            'duration' => 12,
            'begin_date' => '2023-01-01',
            'type' => 'Full-Time',
            'status' => 'ACTIVATED',
            'evolution' => 'ON_GOING',
            'absence_balance' => 30,
            'job_id' => $assistanteDgJob->id,
            'employee_id' => $assistanteEmployee->id,
        ]);

        // ============================================================
        // 6. UTILISATEURS avec employés associés
        // ============================================================

        // DG User
        $dgUser = User::create([
            'username' => 'director_general',
            'email' => 'codeurspassiones@gmail.com',
            'profile' => 'EMPLOYEE',
            'status' => 'ACTIVATED',
            'password' => bcrypt('Dg@2024'),
            'employee_id' => $dgEmployee->id,
        ]);
        $dgUser->syncRoles([$dgRole->id]);

        // DSAF User
        $dsafUser = User::create([
            'username' => 'finance_director',
            'email' => 'amonaaudrey@hotmail.com',
            'profile' => 'EMPLOYEE',
            'status' => 'ACTIVATED',
            'password' => bcrypt('Dsaf@2024'),
            'employee_id' => $dsafEmployee->id,
        ]);
        $dsafUser->syncRoles([$dsafRole->id]);

        // HR User
        $hrUser = User::create([
            'username' => 'hr_manager',
            'email' => 'amonaaudrey16@gmail.com',
            'profile' => 'EMPLOYEE',
            'status' => 'ACTIVATED',
            'password' => bcrypt('Grh@2024'),
            'employee_id' => $hrEmployee->id,
        ]);
        $hrUser->syncRoles([$hrRole->id]);

        // Employee 1 (Comptable)
        $employee1User = User::create([
            'username' => 'employee1',
            'email' => 'employee1@optirh.com',
            'profile' => 'EMPLOYEE',
            'status' => 'ACTIVATED',
            'password' => bcrypt('Employee@2024'),
            'employee_id' => $comptableEmployee->id,
        ]);
        $employee1User->syncRoles([$employeeRole->id]);

        // Employee 2 (Assistante DG)
        $employee2User = User::create([
            'username' => 'employee2',
            'email' => 'employee2@optirh.com',
            'profile' => 'EMPLOYEE',
            'status' => 'ACTIVATED',
            'password' => bcrypt('Employee@2024'),
            'employee_id' => $assistanteEmployee->id,
        ]);
        $employee2User->syncRoles([$employeeRole->id]);
    }
}
