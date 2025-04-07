<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyAppealAnalysedReminderMail extends Mailable
{
    use SerializesModels;

    public $appeals;

    public function __construct($appeals)
    {
        $this->appeals = $appeals;
    }

    public function build()
    {
        return $this->subject("Rappel: Recours en cours d'analyse")
                    ->view('modules.recours.emails.daily-appeal-analysed-reminder')
                    ->with([
                        'appeals' => $this->appeals,
                    ]);
    }
}
