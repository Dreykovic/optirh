<?php

namespace App\Mail;

use App\Models\Absence;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbsenceRequestUpdated extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;
    private $receiver;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private readonly Absence $absence,
        private readonly string $url
    ) {
        $this->receiver = $this->absence->duty->employee;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->receiver->users->first()->email],
            subject: "Demande d'absence {$this->absence->absence_type->label}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $receiverTitle = $this->receiver->gender === 'MALE' ? 'Monsieur' : 'Madame';

        $receiverName = "{$receiverTitle} {$this->receiver->last_name} {$this->receiver->first_name}";
        $absence = $this->absence;
        $status = $this->absence->stage == 'APPROVED' ? 'approuvé' : 'refusé';
        $url = $this->url;

        return new Content(
            view: 'emails.absence_request_updated',
            with: compact('receiverName', 'absence', 'url', 'status')
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
