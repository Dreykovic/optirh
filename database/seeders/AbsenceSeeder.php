<?php

namespace Database\Seeders;

use App\Models\Absence;
use App\Models\AbsenceType;
use App\Models\Duty;
use Illuminate\Database\Seeder;

class AbsenceSeeder extends Seeder
{
    public function run(): void
    {
        // Crée 5 types d'absence (Congés, Maladie, Formation, etc.)
        // $absenceTypes = AbsenceType::factory()->count(5)->create();

        // Crée 10 postes (duties)
        $duties = Duty::factory()->count(10)->create();

        // Crée 50 absences
        Absence::factory()
            ->count(50)
            ->create();
    }
}
