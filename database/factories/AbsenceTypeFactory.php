<?php

namespace Database\Factories;

use App\Models\AbsenceType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsenceTypeFactory extends Factory
{
    /**
     * Le nom du modèle correspondant à cette factory.
     *
     * @var string
     */
    protected $model = AbsenceType::class;

    /**
     * Définir l'état par défaut des attributs du modèle.
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
