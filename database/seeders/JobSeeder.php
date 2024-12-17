<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer 10 jobs de manière standard
        Job::factory()
            ->count(5)
            ->create();

        // Créer 5 jobs avec une relation N+1 job
        Job::factory()
            ->count(10)
            ->withNPlusOneJob()
            ->create();
    }
}
