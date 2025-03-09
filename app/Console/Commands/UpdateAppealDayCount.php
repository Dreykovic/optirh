<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Recours\App\Models\Appeal; 
use Carbon\Carbon;

class UpdateAppealDayCount extends Command
{
    protected $signature = 'appeals:update-day-count';
    protected $description = 'Met à jour le nombre de jours pour les recours en cours';

    public function handle()
    {
        $appeals = Appeal::where('analyse_status', 'EN_COURS')
            ->orWhereHas('decision', function ($query) {
                $query->where('decision', 'EN COURS');
            })
            ->get();

        foreach ($appeals as $appeal) {
            $depositDateTime = Carbon::parse($appeal->deposit_date . ' ' . $appeal->deposit_hour);
            $appeal->day_count = $depositDateTime->diffInDays(Carbon::now());
            $appeal->save();
        }

        $this->info('Mise à jour des délais terminée.');
    }
}

