<?php

namespace Modules\Recours\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Recours\Database\Seeders\AppealSeeder;
use Modules\Recours\Database\Seeders\ApplicantSeeder;
use Modules\Recours\Database\Seeders\DacSeeder;
use Modules\Recours\Database\Seeders\DecisionSeeder;

class RecoursDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        $this->call([
            DacSeeder::class,
            DecisionSeeder::class,
            ApplicantSeeder::class,
            AppealSeeder::class,
        ]);
    }
}
