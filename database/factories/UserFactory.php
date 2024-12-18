<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),

            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),

            'username' => $this->faker->unique()->username(),

            'picture' => 'assets/images/profile_av.png', // Image par défaut
            'profile' => $this->faker->randomElement(['EMPLOYEE', 'ADMIN']),
            'status' => $this->faker->randomElement(['ACTIVATED', 'DEACTIVATED', 'DELETED']),

            'email_verified_at' => $this->faker->boolean(70) ? now() : null, // 70% de chance que l'email soit vérifié

            'employee_id' => Employee::factory(), // Associe un employé à l'utilisateur
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
