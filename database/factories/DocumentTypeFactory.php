<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DocumentType>
 */
class DocumentTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => $this->faker->unique()->words(2, true), // Deux mots aléatoires
            'description' => $this->faker->sentence(), // Une phrase aléatoire
            'status' => $this->faker->randomElement(['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED']),
        ];
    }
}
