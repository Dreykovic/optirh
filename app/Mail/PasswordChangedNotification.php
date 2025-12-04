<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordChangedNotification extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  User  $user  L'utilisateur concerné
     * @param  string  $changedBy  Le contexte du changement ('self', 'admin', 'reset')
     */
    public function __construct(
        public readonly User $user,
        public readonly string $changedBy = 'self'
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->user->email],
            subject: 'Modification de votre mot de passe OptiRh',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $contextMessage = match ($this->changedBy) {
            'admin' => 'Votre mot de passe a été modifié par un administrateur.',
            'reset' => 'Votre mot de passe a été réinitialisé avec succès.',
            default => 'Vous avez modifié votre mot de passe.',
        };

        return new Content(
            view: 'modules.opti-hr.emails.password-changed-notification',
            with: [
                'userName' => $this->user->employee?->first_name ?? $this->user->username,
                'contextMessage' => $contextMessage,
                'changedAt' => now()->format('d/m/Y à H:i'),
            ]
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
