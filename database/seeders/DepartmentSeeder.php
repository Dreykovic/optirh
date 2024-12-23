<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Exécute le seeder.
     */
    public function run(): void
    {
        // Génération aléatoire de départements supplémentaires si besoin
        Department::factory()->count(5)->create();
    }
}
