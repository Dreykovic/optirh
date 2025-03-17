<?php

namespace Modules\Recours\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicantSeeder extends Seeder
{
    public function run()
    {
        DB::table('applicants')->insert([
            [
                'name' => 'Entreprise A',
                'address' => '123 Rue des Entrepreneurs',
                'nif' => '123456780',
                'phone_number' => '987654321',
                'status' => 'ACTIVATED',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Entreprise B',
                'address' => '456 Avenue du Commerce',
                'nif' => '6565656565',
                'phone_number' => '123456789',
                'status' => 'ACTIVATED',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
