<?php

namespace App\Console\Commands;

use App\Mail\DailyAppealAnalysedReminderMail;
use Illuminate\Console\Command;
use App\Models\Recours\Appeal;
use Carbon\Carbon;
use App\Mail\DailyAppealSuspendedReminderMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendDailyAppealSuspendedReminderEmail extends Command
{
    protected $signature = 'appeals:send-daily-suspended-reminder';
    protected $description = 'Envoie un email quotidien avec les recours  en suspension ayant un day_count supérieur ou égal à 13';

   
    public function handle()
    {
        $emails = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['DG', 'DRAJ']);
        })
        ->whereHas('employee') // S'assure que l'utilisateur a un employé lié
        ->with('employee') // Charge les données employé
        ->get()
        ->pluck('employee.email'); // Récupère uniquement les emails des employés
    

        $appeals = Appeal::where('day_count', '>=', 13) // decision delay 15
        ->where(function ($query) {
            $query->whereHas('suspended', function ($query) {
                $query->where('decision', 'SUSPENDU');
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
        $this->sendAppealReminderEmail($appeals,$emails);

        $this->info('Email de rappel envoyé.');
    }

    private function sendAppealReminderEmail($appeals,$emails)
    {
        // Remplacer par l'adresse email de la personne à avertir
        // $emailRecipient = 'fayssologbone@gmail.com'; // Remplacer par l'adresse de la personne concernée
        $recipient = 'fayssologbone@gmail.com'; // Remplacer par l'adresse de la personne concernée
        Mail::to($recipient)->send(new DailyAppealAnalysedReminderMail($appeals));

        // Envoi de l'email avec la liste des recours
        foreach($emails as $emailRecipient){
            Mail::to($emailRecipient)->send(new DailyAppealSuspendedReminderMail($appeals));
        }
    }
}
