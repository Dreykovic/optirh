<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeeFactory extends Factory
{
    /**
     * Le nom du modèle associé à la factory.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Définition des valeurs par défaut pour le modèle Employee.
     */
    public function definition(): array
    {
        return [
            'matricule' => 'EMP-'.strtoupper(Str::random(6)), // Génération d'un matricule unique
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->unique()->numerify('+33#########'), // Numéro au format français
            'address1' => $this->faker->streetAddress(),
            'address2' => $this->faker->optional()->secondaryAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(),
            'country' => $this->faker->countryCode(),
            'bank_name' => $this->faker->optional()->company(),
            'code_bank' => $this->faker->optional()->numerify('#####'),
            'code_guichet' => $this->faker->optional()->numerify('#####'),
            'rib' => $this->faker->optional()->numerify('#######################'),
            'cle_rib' => $this->faker->optional()->numerify('##'),
            'iban' => $this->faker->optional()->iban(),
            'swift' => $this->faker->optional()->regexify('[A-Z]{6}[A-Z2-9][A-NP-Z0-9]([A-Z0-9]{3})?'), // Code SWIFT
            'birth_date' => $this->faker->optional()->date('Y-m-d', '2000-01-01'),
            'nationality' => $this->faker->optional()->country(),
            'religion' => $this->faker->optional()->randomElement(['Christian', 'Muslim', 'Jewish', 'Hindu', 'Buddhist', 'None', 'Other']),
            'marital_status' => $this->faker->optional()->randomElement(['Single', 'Married', 'Divorced', 'Widowed']),
            'emergency_contact' => $this->faker->optional()->numerify('+33#########'),
            'status' => $this->faker->randomElement(['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED']),
        ];
    }
}
