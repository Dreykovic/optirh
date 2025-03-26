<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyAppealReminderMail extends Mailable
{
    use SerializesModels;

    public $appeals;

    public function __construct($appeals)
    {
        $this->appeals = $appeals;
    }

    public function build()
    {
        return $this->subject('Rappel: Recours en cours depuis plus de 7 jours')
                    ->view('modules.recours.emails.daily-appeal-reminder')
                    ->with([
                        'appeals' => $this->appeals,
                    ]);
    }
}
