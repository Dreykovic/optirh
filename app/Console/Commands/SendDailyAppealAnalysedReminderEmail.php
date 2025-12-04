<?php

namespace App\Console\Commands;

use App\Mail\DailyAppealAnalysedReminderMail;
use App\Models\Recours\Appeal;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyAppealAnalysedReminderEmail extends Command
{
    protected $signature = 'appeals:send-daily-analysed-reminder';

    protected $description = 'Envoie un email quotidien avec les recours en cours d analyse ayant un day_count supérieur ou égal à 5';

    public function handle()
    {
        $emails = User::where('status', '!=', 'DELETED')
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['DG', 'DRAJ']);
            })
            ->whereHas('employee') // S'assure que l'utilisateur a un employé lié
            ->with('employee') // Charge les données employé
            ->get()
            ->pluck('employee.email')
            ->filter(); // Filtre les valeurs null ou vides

        $appeals = Appeal::where('day_count', '>=', 5) // analyse delay 7
            ->where(function ($query) {
                $query->where('analyse_status', 'EN_COURS');
            })
            ->orderBy('day_count', 'desc')
            ->get();

        // Vérifier s'il y a des recours à inclure dans l'email
        if ($appeals->isEmpty()) {
            $this->info('Aucun recours à rappeler aujourd\'hui.');

            return;
        }

        // Envoyer les emails
        $this->sendAppealReminderEmail($appeals, $emails);

        $this->info('Email de rappel envoyé.');
    }

    private function sendAppealReminderEmail($appeals, $emails)
    {
        // Envoi à une adresse spécifique (si nécessaire)
        Mail::to('fayssologbone@gmail.com')->send(new DailyAppealAnalysedReminderMail($appeals));

        // Envoi de l'email à tous les destinataires concernés
        foreach ($emails as $emailRecipient) {
            Mail::to($emailRecipient)->send(new DailyAppealAnalysedReminderMail($appeals));
        }
    }
}
