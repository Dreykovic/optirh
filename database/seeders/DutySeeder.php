<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Duty;

class DutySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crée 30 enregistrements pour les "duties"
        Duty::factory()->count(30)->create();
    }
}
