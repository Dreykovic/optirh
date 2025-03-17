<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('duties:update-absence-balance')->yearlyOn(31, 12, '00:00');
        $schedule->command('appeals:update-day-count')->hourly()->between('08:00', '18:00');  
         // Planifier l'exécution de la commande une fois par jour à une heure spécifique (par exemple, minuit)
        $schedule->command('appeals:send-daily-reminder')->dailyAt('12:00');      
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        
        require base_path('routes/console.php');
    }
}
