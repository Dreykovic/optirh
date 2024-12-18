<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Department;
use App\Models\Job;
use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    protected $model = Job::class;

    /**
     * Définit le modèle associé à cette factory.
     *
     * @var string
     */
    protected $model = Job::class;

    /**
     * Définit les champs du modèle Job.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(),
            'department_id' => Department::inRandomOrder()->value('id'),
            'status' => $this->faker->randomElement(['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED']),
            'n_plus_one_job_id' => Job::inRandomOrder()->value('id'),
        ];
    }
}
