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
            // RoleSeeder::class,
            // UserSeeder::class,
            // AccountTypeSeeder::class,
            // TransactionTypeSeeder::class,
            // ClientSeeder::class,
            // AccountSeeder::class,
            // AccountSeeder::class,
            EmployeeSeeder::class,
            FileSeeder::class,
            DutySeeder::class,
            DepartmentSeeder::class,
            JobSeeder::class,

        ]);
    }
}
