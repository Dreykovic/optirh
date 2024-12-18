<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Duty>
 */
class DutyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $beginDate = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'duration' => $this->faker->numberBetween(1, 12).' months', // Durée de la tâche (en mois)
            'begin_date' => $beginDate->format('Y-m-d'), // Date de début entre l'année dernière et aujourd'hui
            'type' => $this->faker->randomElement(['Full-Time', 'Part-Time', 'Internship', 'Consultant']), // Type de la tâche
            'status' => $this->faker->randomElement(['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED']), // Statut aléatoire
            'job_id' => Job::inRandomOrder()->value('id'), // Sélectionne un job aléatoire
            'employee_id' => Employee::inRandomOrder()->value('id'), // Sélectionne un employé aléatoire
        ];
    }
}
