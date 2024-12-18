<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Job;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Exécute le seeder.
     */
    public function run(): void
    {
        // Données de base pour les jobs fixes
        $jobsData = [
            [
                'title' => 'HR Manager',
                'description' => 'Responsible for overseeing the HR department and ensuring employee satisfaction.',
                'department_id' => Department::where('name', 'Human Resources')->value('id'),
                'status' => 'ACTIVATED',
            ],
            [
                'title' => 'Software Engineer',
                'description' => 'Designs, develops, and maintains software applications.',
                'department_id' => Department::where('name', 'IT Support')->value('id'),
                'status' => 'ACTIVATED',
            ],
            [
                'title' => 'Sales Executive',
                'description' => 'Handles sales operations and customer relationships.',
                'department_id' => Department::where('name', 'Sales')->value('id'),
                'status' => 'ACTIVATED',
            ],
            [
                'title' => 'Marketing Specialist',
                'description' => 'Develops marketing campaigns and manages brand visibility.',
                'department_id' => Department::where('name', 'Marketing')->value('id'),
                'status' => 'ACTIVATED',
            ],
            [
                'title' => 'Finance Analyst',
                'description' => 'Analyzes financial data and produces financial reports.',
                'department_id' => Department::where('name', 'Finance')->value('id'),
                'status' => 'ACTIVATED',
            ],
            [
                'title' => 'Operations Supervisor',
                'description' => 'Ensures the smooth operation of daily activities within the company.',
                'department_id' => Department::where('name', 'Operations')->value('id'),
                'status' => 'ACTIVATED',
            ],
        ];

        // Création des 6 jobs de base
        foreach ($jobsData as $jobData) {
            $job = Job::create($jobData);

            // Ajout d'un "N+1" pour chaque job
            $n_plus_one_job = Job::inRandomOrder()->where('id', '!=', $job->id)->first();
            $job->n_plus_one_job_id = $n_plus_one_job ? $n_plus_one_job->id : null;
            $job->save();
        }

        // Génération de 10 autres jobs aléatoires
        Job::factory()->count(10)->create();
    }
}
