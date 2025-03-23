<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Recours\Appeal;
use Carbon\Carbon;
use App\Mail\DailyAppealReminderMail;
use Illuminate\Support\Facades\Mail;

class SendDailyAppealReminderEmail extends Command
{
    protected $signature = 'appeals:send-daily-reminder';
    protected $description = 'Envoie un email quotidien avec les recours ayant un day_count supérieur ou égal à 3';

    public function handle()
    {

        $appeals = Appeal::where('day_count', '>=', 3)//7
            ->where(function ($query) {
                $query->where('analyse_status', 'EN_COURS')
                      ->orWhereHas('decision', function ($query) {
                          $query->where('decision', 'EN COURS');
                      });
            })
            ->orderBy('day_count', 'desc')
            ->get();


        // Vérifier s'il y a des recours à inclure dans l'email
        if ($appeals->isEmpty()) {
            $this->info('Aucun recours à rappeler aujourd\'hui.');
            return;
        }

        // Envoyer un seul email avec tous les recours
        $this->sendAppealReminderEmail($appeals);

        $this->info('Email de rappel envoyé.');
    }

    private function sendAppealReminderEmail($appeals)
    {
        // Remplacer par l'adresse email de la personne à avertir
        $emailRecipient = 'fayssologbone@gmail.com'; // Remplacer par l'adresse de la personne concernée

        // Envoi de l'email avec la liste des recours
        Mail::to($emailRecipient)->send(new DailyAppealReminderMail($appeals));
    }
}
