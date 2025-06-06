<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppealSeeder extends Seeder
{
    public function run()
    {
        DB::table('appeals')->insert([
            [
                'type' => 'PROCESS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours concernant un appel d\'offre',
                'day_count' => 5,
                'analyse_status' => 'RECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 1,
                'decision_id' => 1,
                'applicant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'RESULTS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours sur les résultats',
                'day_count' => 3,
                'analyse_status' => 'IRRECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 2,
                'decision_id' => 2,
                'applicant_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'PROCESS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours concernant un appel d\'offre',
                'day_count' => 5,
                'analyse_status' => 'RECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 1,
                'decision_id' => 1,
                'applicant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'RESULTS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours sur les résultats',
                'day_count' => 3,
                'analyse_status' => 'IRRECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 2,
                'decision_id' => 2,
                'applicant_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'PROCESS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours concernant un appel d\'offre',
                'day_count' => 5,
                'analyse_status' => 'RECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 1,
                'decision_id' => 1,
                'applicant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'RESULTS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours sur les résultats',
                'day_count' => 3,
                'analyse_status' => 'IRRECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 2,
                'decision_id' => 2,
                'applicant_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'PROCESS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours concernant un appel d\'offre',
                'day_count' => 5,
                'analyse_status' => 'IRRECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 1,
                'decision_id' => 2,
                'applicant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'RESULTS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours sur les résultats',
                'day_count' => 3,
                'analyse_status' => 'IRRECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 2,
                'decision_id' => 2,
                'applicant_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'PROCESS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours concernant un appel d\'offre',
                'day_count' => 5,
                'analyse_status' => 'IRRECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 1,
                'decision_id' => 3,
                'applicant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'RESULTS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours sur les résultats',
                'day_count' => 3,
                'analyse_status' => 'IRRECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 2,
                'decision_id' => 4,
                'applicant_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'PROCESS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours concernant un appel d\'offre',
                'day_count' => 5,
                'analyse_status' => 'IRRECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 1,
                'decision_id' => 5,
                'applicant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'RESULTS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours sur les résultats',
                'day_count' => 3,
                'analyse_status' => 'IRRECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 2,
                'decision_id' => 5,
                'applicant_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'PROCESS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours concernant un appel d\'offre',
                'day_count' => 5,
                'analyse_status' => 'IRRECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 1,
                'decision_id' => 6,
                'applicant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'RESULTS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours sur les résultats',
                'day_count' => 3,
                'analyse_status' => 'IRRECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 2,
                'decision_id' => 3,
                'applicant_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'PROCESS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours concernant un appel d\'offre',
                'day_count' => 5,
                'analyse_status' => 'IRRECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 1,
                'decision_id' => 6,
                'applicant_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'RESULTS',
                'deposit_date' => now(),
                'deposit_hour' => now()->format('H:i:s'),
                'object' => 'Recours sur les résultats',
                'day_count' => 3,
                'analyse_status' => 'IRRECEVABLE',
                'status' => 'ACTIVATED',
                'dac_id' => 2,
                'decision_id' => 6,
                'applicant_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
