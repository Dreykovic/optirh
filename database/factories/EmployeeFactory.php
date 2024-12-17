<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Identité
            'matricule' => strtoupper(Str::random(8)), // Matricule unique
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => $this->faker->unique()->phoneNumber,

            // Adresse
            'address1' => $this->faker->streetAddress,
            'address2' => $this->faker->secondaryAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->city,
            'country' => $this->faker->countryCode,

            // Informations bancaires
            'bank_name' => $this->faker->company,
            'code_bank' => $this->faker->numerify('####'),
            'code_guichet' => $this->faker->numerify('####'),
            'rib' => $this->faker->numerify('#####################'),
            'cle_rib' => $this->faker->numerify('##'),
            'iban' => $this->faker->iban(null),
            'swift' => $this->faker->swiftBicNumber,

            // Informations personnelles
            'birth_date' => $this->faker->date,
            'nationality' => $this->faker->country,
            'religion' => $this->faker->randomElement(['Christian', 'Muslim', 'Jewish', 'Hindu', 'Buddhist', 'None', 'Other']),
            'marital_status' => $this->faker->randomElement(['Single', 'Married', 'Divorced', 'Widowed']),
            'emergency_contact' => $this->faker->phoneNumber,

            // Statut
            'status' => $this->faker->randomElement(['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED']),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
