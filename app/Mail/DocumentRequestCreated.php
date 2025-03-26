<?php

namespace App\Mail;

use App\Models\OptiHr\DocumentRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentRequestCreated extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private readonly User $receiver,
        private readonly DocumentRequest $documentRequest,
        private readonly string $url
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->receiver->email],
            subject: "Demande de document {$this->documentRequest->document_type->name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $receiverTitle = $this->receiver->employee->gender === 'MALE' ? 'Monsieur' : 'Madame';

        $receiverName = "{$receiverTitle} {$this->receiver->employee->last_name} {$this->receiver->employee->first_name}";
        $employee = $this->documentRequest->duty->employee;
        $job = $this->documentRequest->duty->job;

        $employeeTitle = $employee->gender === 'MALE' ? 'Monsieur' : 'Madame';
        $employeeFullName = "{$employee->last_name} {$employee->first_name}";
        $jobTitle = $job->title;
        $department = $job->department->description;

        $text = "{$employeeTitle} {$employeeFullName}, {$jobTitle} à la {$department} de l'ARCOP";
        $url = $this->url;

        return new Content(
            view: 'modules.opti-hr.emails.document-request-created',
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