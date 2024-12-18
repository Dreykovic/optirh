<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    protected $model = File::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word . '.' . $this->faker->fileExtension(),
            'url' => $this->faker->url(),
            'mime_type' => $this->faker->randomElement(['application/pdf', 'image/jpeg', 'image/png', 'text/plain']),
            'description' => $this->faker->sentence(),
            'path' => 'uploads/' . $this->faker->uuid() . '.' . $this->faker->fileExtension(),
            'data' => null, // Si tu ne stockes pas réellement les fichiers
            'upload_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED']),

            // Relation avec Employee
            'employee_id' => Employee::inRandomOrder()->first()->id ?? Employee::factory()->create()->id,
        ];
    }
}
