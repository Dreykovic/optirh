<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DecisionSeeder extends Seeder
{
    public function run()
    {
        DB::table('decisions')->insert([
            ['decision' => 'FONDE', 'date' => now(), 'status' => 'ACTIVATED', 'created_at' => now(), 'updated_at' => now()],
            ['decision' => 'NON FONDE', 'date' => now(), 'status' => 'ACTIVATED', 'created_at' => now(), 'updated_at' => now()],
            ['decision' => 'INCOMPETENCE', 'date' => now(), 'status' => 'ACTIVATED', 'created_at' => now(), 'updated_at' => now()],
            ['decision' => 'FORCLUSION', 'date' => now(), 'status' => 'ACTIVATED', 'created_at' => now(), 'updated_at' => now()],
            ['decision' => 'DESISTEMENT', 'date' => now(), 'status' => 'ACTIVATED', 'created_at' => now(), 'updated_at' => now()],
            ['decision' => 'SUSPENDU', 'date' => now(), 'status' => 'ACTIVATED', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
