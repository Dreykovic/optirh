<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Exécute le seeder.
     */
    public function run(): void
    {
        // Données de base pour les 6 départements fixes
        $departmentsData = [
            [
                'name' => 'Human Resources',
                'description' => 'Handles recruitment, onboarding, and employee welfare',
                'director_id' => null, // Peut être rempli après la création des employés
                'status' => 'ACTIVATED',
            ],
            [
                'name' => 'Sales',
                'description' => 'Focuses on selling products and generating revenue',
                'director_id' => null,
                'status' => 'ACTIVATED',
            ],
            [
                'name' => 'Marketing',
                'description' => 'Responsible for promoting the brand and increasing visibility',
                'director_id' => null,
                'status' => 'ACTIVATED',
            ],
            [
                'name' => 'Finance',
                'description' => 'Manages financial planning, analysis, and reporting',
                'director_id' => null,
                'status' => 'ACTIVATED',
            ],
            [
                'name' => 'IT Support',
                'description' => 'Provides technical support and maintains IT infrastructure',
                'director_id' => null,
                'status' => 'ACTIVATED',
            ],
            [
                'name' => 'Operations',
                'description' => 'Oversees daily activities to ensure business efficiency',
                'director_id' => null,
                'status' => 'ACTIVATED',
            ],
        ];

        // Création des 6 départements
        foreach ($departmentsData as $departmentData) {
            // Tenter d'associer un directeur au département
            $director = Employee::inRandomOrder()->first();
            $departmentData['director_id'] = $director ? $director->id : null;

            // Crée le département avec les données
            Department::create($departmentData);
        }

        // Génération aléatoire de départements supplémentaires si besoin
        Department::factory()->count(5)->create();
    }
}
