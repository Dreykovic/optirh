<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DacSeeder extends Seeder
{
    public function run()
    {
        DB::table('dacs')->insert([
            [
                'reference' => 'DAC-001',
                'object' => 'Objet du DAC 1',
                'ac' => 'AC-001',
                'status' => 'ACTIVATED',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'reference' => 'DAC-002',
                'object' => 'Objet du DAC 2',
                'ac' => 'AC-002',
                'status' => 'ACTIVATED',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
