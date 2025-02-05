<?php

namespace Database\Factories;

use App\Models\DocumentType;
use App\Models\Duty;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DocumentRequest>
 */
class DocumentRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::createFromTimestamp($this->faker->dateTimeThisYear()->getTimestamp());
        $endDate = (clone $startDate)->addDays($this->faker->numberBetween(1, 15));

        return [
            'level' => $this->faker->randomElement(['ZERO', 'ONE', 'TWO']),
            'start_date' => $startDate,
            'end_date' => $endDate,

            'date_of_application' => $this->faker->date(),
            'status' => $this->faker->randomElement(['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED']),
            'date_of_approval' => $this->faker->optional()->date(),
            'stage' => $this->faker->randomElement(['PENDING', 'APPROVED', 'REJECTED', 'CANCELLED', 'IN_PROGRESS', 'COMPLETED']),
            'reasons' => $this->faker->paragraph,
            'proof' => $this->faker->optional()->imageUrl(),
            'comment' => $this->faker->optional()->sentence(),
            'duty_id' => Duty::factory(),
            // Utiliser un type d'absence existant dans la base de données
            'document_type_id' => DocumentType::inRandomOrder()->first()->id,
        ];
    }
}
