<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * List of public holidays in Togo.
     *
     * @var array
     */
    protected $holidays = [
        ['name' => 'Jour de l\'An', 'date' => '2024-01-01', 'is_public_holiday' => true, 'is_religious' => false, 'is_fixed' => true],
        ['name' => 'Fête de l\'Indépendance', 'date' => '2024-04-27', 'is_public_holiday' => true, 'is_religious' => false, 'is_fixed' => true],
        ['name' => 'Fête du Travail', 'date' => '2024-05-01', 'is_public_holiday' => true, 'is_religious' => false, 'is_fixed' => true],
        ['name' => 'Aïd el-Fitr', 'date' => '2024-04-10', 'is_public_holiday' => true, 'is_religious' => true, 'religion' => 'Islam', 'is_fixed' => false],
        ['name' => 'Aïd el-Kebir', 'date' => '2024-06-28', 'is_public_holiday' => true, 'is_religious' => true, 'religion' => 'Islam', 'is_fixed' => false],
        ['name' => 'Noël', 'date' => '2024-12-25', 'is_public_holiday' => true, 'is_religious' => true, 'religion' => 'Christianisme', 'is_fixed' => true],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->holidays as $holiday) {
            Holiday::updateOrCreate(
                ['name' => $holiday['name'], 'date' => $holiday['date']],
                $holiday
            );
        }
    }
}
