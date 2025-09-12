<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestSystemMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * L'adresse email de test
     *
     * @var string
     */
    private string $testEmail;

    /**
     * Créer une nouvelle instance du message
     *
     * @param string $testEmail
     */
    public function __construct(string $testEmail)
    {
        $this->testEmail = $testEmail;
    }

    /**
     * Obtenir l'enveloppe du message
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->testEmail],
            subject: 'Test du Système d\'Email OPTIRH',
        );
    }

    /**
     * Obtenir la définition du contenu du message
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        $timestamp = now()->format('d/m/Y H:i:s');
        $config = [
            'driver' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'from' => config('mail.from.address'),
            'queue' => config('queue.default')
        ];

        return new Content(
            html: 'emails.test-system',
            with: [
                'timestamp' => $timestamp,
                'config' => $config,
                'testEmail' => $this->testEmail
            ]
        );
    }

    /**
     * Obtenir les pièces jointes du message
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}