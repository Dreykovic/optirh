<?php

namespace Database\Seeders;

use App\Models\Duty;
use App\Models\Duty;
use Illuminate\Database\Seeder;

class DutySeeder extends Seeder
{
    /**
     * Exécute le seeder.
     */
    public function run(): void
    {
        // Données de base pour les tâches (duties) prédéfinies
        $dutiesData = [
            [
                'duration' => '6 months',
                'begin_date' => '2024-01-01',
                'type' => 'Full-Time',
                'status' => 'ACTIVATED',
                'job_id' => 1, // Associe au job avec l'ID 1
                'employee_id' => 1, // Associe à l'employé avec l'ID 1
            ],
            [
                'duration' => '3 months',
                'begin_date' => '2023-10-15',
                'type' => 'Part-Time',
                'status' => 'ACTIVATED',
                'job_id' => 2, // Associe au job avec l'ID 2
                'employee_id' => 2, // Associe à l'employé avec l'ID 2
            ],
            [
                'duration' => '12 months',
                'begin_date' => '2023-09-01',
                'type' => 'Internship',
                'status' => 'PENDING',
                'job_id' => 3, // Associe au job avec l'ID 3
                'employee_id' => 3, // Associe à l'employé avec l'ID 3
            ],
            [
                'duration' => '9 months',
                'begin_date' => '2023-05-10',
                'type' => 'Consultant',
                'status' => 'ACTIVATED',
                'job_id' => 4, // Associe au job avec l'ID 4
                'employee_id' => 4, // Associe à l'employé avec l'ID 4
            ],
            [
                'duration' => '2 months',
                'begin_date' => '2023-11-20',
                'type' => 'Part-Time',
                'status' => 'DEACTIVATED',
                'job_id' => 5, // Associe au job avec l'ID 5
                'employee_id' => 5, // Associe à l'employé avec l'ID 5
            ],
            [
                'duration' => '1 month',
                'begin_date' => '2023-12-01',
                'type' => 'Consultant',
                'status' => 'PENDING',
                'job_id' => 6, // Associe au job avec l'ID 6
                'employee_id' => 6, // Associe à l'employé avec l'ID 6
            ],
        ];

        // Création des 6 tâches prédéfinies
        foreach ($dutiesData as $dutyData) {
            Duty::create($dutyData);
        }

        // Génération de 10 autres tâches aléatoires
        Duty::factory()->count(10)->create();
    }
}
