<?php

namespace Database\Factories;

use App\Models\Duty;
use App\Models\Job;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Duty>
 */
class DutyFactory extends Factory
{
    protected $model = Duty::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'duration' => $this->faker->randomElement(['3 months', '6 months', '1 year', '2 years']),
            'begin_date' => $this->faker->date(),
            'type' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contract', 'Internship']),
            'status' => $this->faker->randomElement(['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED']),
            'evolution' => $this->faker->randomElement(['ON_GOING', 'ENDED', 'CANCEL']),
            
            // Relations
            'job_id' => Job::inRandomOrder()->first()->id ?? Job::factory()->create()->id,
            'employee_id' => Employee::inRandomOrder()->first()->id ?? Employee::factory()->create()->id,
            
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
