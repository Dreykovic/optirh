<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    protected $model = Job::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle, 
            'description' => $this->faker->sentence,
            'department_id' => Department::factory(),  
            'status' => $this->faker->randomElement(['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED']),
            'n_plus_one_job_id' => null, // Peut être peuplé séparément pour éviter des boucles circulaires
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Define a state with a N+1 job relation.
     */
    public function withNPlusOneJob(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'n_plus_one_job_id' => Job::factory(),
            ];
        });
    }
}
