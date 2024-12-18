<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Exécute le seeder pour remplir la table employees avec des données fictives.
     */
    public function run(): void
    {
        // Crée 50 employés fictifs
        Employee::factory()->count(5)->create();
    }
}
