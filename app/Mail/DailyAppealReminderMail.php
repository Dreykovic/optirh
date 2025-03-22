<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Modules\Recours\App\Models\OptiHr\Appeal;

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
                    ->view('recours::pages.recours.mails')
                    ->with([
                        'appeals' => $this->appeals,
                    ]);
    }
}
