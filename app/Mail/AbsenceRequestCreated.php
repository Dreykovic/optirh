<?php

namespace App\Mail;

use App\Models\OptiHr\Absence;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbsenceRequestCreated extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private readonly User $receiver,
        private readonly Absence $absence,
        private readonly string $url
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->receiver->email],
            subject: "Demande d'absence {$this->absence->absence_type->label}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Gérer le cas où le destinataire n'a pas d'employé associé
        if ($this->receiver->hasEmployee()) {
            $receiverTitle = $this->receiver->employee->gender === 'MALE' ? 'Monsieur' : 'Madame';
            $receiverName = "{$receiverTitle} {$this->receiver->employee->last_name} {$this->receiver->employee->first_name}";
        } else {
            $receiverName = $this->receiver->username;
        }

        $employee = $this->absence->duty->employee;
        $job = $this->absence->duty->job;

        $employeeTitle = $employee->gender === 'MALE' ? 'Monsieur' : 'Madame';
        $employeeFullName = "{$employee->last_name} {$employee->first_name}";
        $jobTitle = $job->title;
        $department = $job->department->description;

        $text = "{$employeeTitle} {$employeeFullName}, {$jobTitle} à la {$department} de l'ARCOP";
        $url = $this->url;

        return new Content(
            view: 'modules.opti-hr.emails.absence-request-created',
            with: compact('receiverName', 'text', 'url')
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
