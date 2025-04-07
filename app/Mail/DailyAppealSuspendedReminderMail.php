<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyAppealSuspendedReminderMail extends Mailable
{
    use SerializesModels;

    public $appeals;

    public function __construct($appeals)
    {
        $this->appeals = $appeals;
    }

    public function build()
    {
        return $this->subject('Rappel: Recours en suspension')
                    ->view('modules.recours.emails.daily-appeal-suspended-reminder')
                    ->with([
                        'appeals' => $this->appeals,
                    ]);
    }
}
