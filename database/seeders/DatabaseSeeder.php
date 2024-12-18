<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            EmployeeSeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            JobSeeder::class,
            DutySeeder::class,
            AbsenceTypeSeeder::class,
            AbsenceSeeder::class
        ]);
    }
}
