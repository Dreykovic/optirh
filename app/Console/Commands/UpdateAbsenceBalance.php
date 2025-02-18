<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Duty;

class UpdateAbsenceBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duties:update-absence-balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Augmente le solde d\'absence de chaque employé de 30 au 1er janvier';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $updatedCount = Duty::where('evolution','ON_GOING')->update([
            'absence_balance' => \DB::raw('absence_balance + 30'),
        ]);

        $this->info("Solde d'absence mis à jour pour $updatedCount employés.");
    }
}
