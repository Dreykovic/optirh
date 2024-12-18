<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    /**
     * Définition du modèle associé à la factory.
     *
     * @var string
     */
    protected $model = \App\Models\Department::class;

    /**
     * Définit le modèle de données pour chaque département.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word().' Department',
            'description' => $this->faker->unique()->sentence(),
            'director_id' => Employee::inRandomOrder()->value('id'), // Associer un directeur aléatoire existant
            'status' => $this->faker->randomElement(['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED']),
        ];
    }
}
