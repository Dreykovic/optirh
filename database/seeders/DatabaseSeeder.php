<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Department;
use App\Models\Duty;
use App\Models\Employee;
use App\Models\Job;
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
            DgSeeder::class,
            
            RoleSeeder::class,

            // EmployeeSeeder::class,
            // UserSeeder::class,
            // DepartmentSeeder::class,
            // JobSeeder::class,
            // DutySeeder::class,
          //  FileSeeder::class,
          
            AbsenceTypeSeeder::class,
            // AbsenceSeeder::class,
            HolidaySeeder::class,
        ]);
        /**
         * Employees.
         */
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
        $employeeChef = Employee::create([
            'matricule' => 'EMP001',
            'first_name' => 'Employee',
            'last_name' => 'Standard',
            'email' => 'employeeChef@example.com',
            'phone_number' => '1234567897',
            'address1' => '4 Employee Road',
            'city' => 'Employee City',
            'state' => 'EC',
            'country' => 'FR',
            'birth_date' => '1990-03-25',
            'nationality' => 'French',
            'status' => 'ACTIVATED',
        ]);

        $employeeChefUser = User::create([
            'username' => 'employee_chef',
            'email' => 'employeeChef@example.com',
            'profile' => 'EMPLOYEE',
            'status' => 'ACTIVATED',
            'password' => bcrypt('employeeChef_password'),
            'employee_id' => $employeeChef->id,
        ]);
        $employeeChefUser->syncRoles([$employeeRole->id]);
        // Création d'un employé standard
        $employee = Employee::create([
            'matricule' => 'EMP002',
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
        /**
         * Departements.
         */
        // Données de base pour les 6 départements fixes

        $dgDpt = Department::create([
            'name' => 'DG',
            'description' => 'Direction Général',
            'director_id' => $directorEmployee->id,
            'status' => 'ACTIVATED',
        ]);

        $dsafDpt = Department::create([
            'name' => 'DSAF',
            'description' => '',
            'director_id' => null,
            'status' => 'ACTIVATED',
        ]);
        /**
         * Job.
         */
        $dgJob = Job::create([
            'title' => 'Directeur Général',
            'description' => '..',
            'department_id' => $dgDpt->id,
            'status' => 'ACTIVATED',
            'n_plus_one_job_id' => null,
        ], );

        $hrfJob = Job::create([
            'title' => 'Grh',
            'description' => '...',
            'department_id' => $dsafDpt->id,
            'status' => 'ACTIVATED',
            'n_plus_one_job_id' => $dgJob->id,
        ], );
        $empChefJob = Job::create([
            'title' => 'Chef',
            'description' => '...',
            'department_id' => $dsafDpt->id,
            'status' => 'ACTIVATED',
            'n_plus_one_job_id' => $hrfJob->id,
        ], );
        $empJob = Job::create([
            'title' => 'Emp',
            'description' => '...',
            'department_id' => $dsafDpt->id,
            'status' => 'ACTIVATED',
            'n_plus_one_job_id' => $empChefJob->id,
        ], );
        /**
         * Duty.
         */
        $dgDuty = Duty::create([
            'duration' => '2 months',
            'begin_date' => '2023-11-20',
            'type' => 'Full-Time',
            'status' => 'ACTIVATED',
            'job_id' => $dgJob->id, // Associe au job avec l'ID 5
            'employee_id' => $directorEmployee->id, // Associe à l'employé avec l'ID 5
        ], );
        $rhDuty = Duty::create([
            'duration' => '2 months',
            'begin_date' => '2023-11-20',
            'type' => 'Full-Time',
            'status' => 'ACTIVATED',
            'job_id' => $hrfJob->id, // Associe au job avec l'ID 5
            'employee_id' => $hrEmployee->id, // Associe à l'employé avec l'ID 5
        ], );
        $empChefDuty = Duty::create([
            'duration' => '2 months',
            'begin_date' => '2023-11-20',
            'type' => 'Part-Time',
            'status' => 'ACTIVATED',
            'job_id' => $empChefJob->id, // Associe au job avec l'ID 5
            'employee_id' => $employeeChef->id, // Associe à l'employé avec l'ID 5
        ]);
        $empDuty = Duty::create([
            'duration' => '2 months',
            'begin_date' => '2023-11-20',
            'type' => 'Part-Time',
            'status' => 'ACTIVATED',
            'job_id' => $empJob->id, // Associe au job avec l'ID 5
            'employee_id' => $employee->id, // Associe à l'employé avec l'ID 5
        ]);
        // Données de base pour les jobs fixes
    }
}
