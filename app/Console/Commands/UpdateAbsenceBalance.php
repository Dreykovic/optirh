<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OptiHr\Duty;
use Illuminate\Support\Facades\Log;

class UpdateAbsenceBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duties:update-absence-balance {--dry-run : Simuler l\'opération sans appliquer les changements}';

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
        $query = Duty::where('evolution', 'ON_GOING');

        if ($this->option('dry-run')) {
            $count = $query->count();
            $this->info("Mode simulation : $count employés seraient mis à jour.");
            return;
        }

        $updatedCount = $query->update([
            'absence_balance' => \DB::raw('absence_balance + 30'),
        ]);
        Log::info("Mise à jour annuelle des soldes d'absence effectuée pour $updatedCount employés.");

        $this->info("Solde d'absence mis à jour pour $updatedCount employés.");
    }
}
