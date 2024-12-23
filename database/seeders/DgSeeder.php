<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Job;
use Illuminate\Database\Seeder;

class DgSeeder extends Seeder
{
    /**
     * Exécute le seeder.
     */
    public function run(): void
    {
        // Vérifie si le département "DG" existe, sinon le créer
        $department = Department::firstOrCreate(
            ['name' => 'DG'], // Critère de recherche
            [
                'description' => 'Direction Générale',
                'status' => 'ACTIVATED',
                'director_id' => null // Pas de directeur associé pour le moment
            ]
        );

        // Vérifie si le job "DG" existe pour ce département, sinon le créer
        Job::firstOrCreate(
            ['title' => 'DG'], // Critère de recherche
            [
                'description' => 'Directeur·rice Général·e',
                'status' => 'ACTIVATED',
                'department_id' => $department->id, // Associer au département "DG"
                'n_plus_one_job_id' => null // Pas de job supérieur hiérarchique
            ]
        );
    }
}
