<?php

namespace Database\Factories;

use App\Models\Absence;
use App\Models\Duty;
use App\Models\AbsenceType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AbsenceFactory extends Factory
{
    protected $model = Absence::class;

    public function definition(): array
    {
        $startDate = Carbon::createFromTimestamp($this->faker->dateTimeThisYear()->getTimestamp());
        $endDate = (clone $startDate)->addDays($this->faker->numberBetween(1, 15));

        return [
            'requested_days' => $startDate->diffInDays($endDate),
            'level' => $this->faker->randomElement(['ZERO', 'ONE', 'TWO']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'address' => $this->faker->address,
            'date_of_application' => $this->faker->date(),
            'status' => $this->faker->randomElement(['ACTIVATED', 'DEACTIVATED', 'PENDING', 'DELETED', 'ARCHIVED']),
            'date_of_approval' => $this->faker->optional()->date(),
            'stage' => $this->faker->randomElement(['PENDING', 'APPROVED', 'REJECTED', 'CANCELLED', 'IN_PROGRESS', 'COMPLETED']),
            'reasons' => $this->faker->paragraph,
            'proof' => $this->faker->optional()->imageUrl(),
            'comment' => $this->faker->optional()->sentence(),
            'duty_id' => Duty::factory(),
            'absence_type_id' => AbsenceType::factory(),
        ];
    }
}
