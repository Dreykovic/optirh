<?php

namespace App\Traits;

use App\Services\MailService;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

trait SendsEmails
{
    /**
     * Instance du service mail
     */
    protected ?MailService $mailService = null;

    /**
     * Obtenir l'instance du service mail
     */
    protected function getMailService(): MailService
    {
        if (! $this->mailService) {
            $this->mailService = app(MailService::class);
        }

        return $this->mailService;
    }

    /**
     * Envoyer un email de manière sécurisée
     *
     * @param  bool  $queue  Utiliser la queue si disponible
     */
    protected function sendEmail(Mailable $mailable, bool $queue = true): bool
    {
        try {
            $mailService = $this->getMailService();

            // Vérifier la configuration avant l'envoi
            $configCheck = $mailService->checkConfiguration();
            if (! $configCheck['is_valid']) {
                Log::warning('Configuration mail incomplète', $configCheck);
                // Continuer quand même avec les valeurs par défaut
            }

            // Envoyer via queue si demandé et disponible
            if ($queue && config('queue.default') !== 'sync') {
                return $mailService->queue($mailable);
            }

            // Si queue demandée mais driver sync : utiliser dispatch()->afterResponse()
            // Cela envoie l'email après la réponse HTTP (async sans queue)
            if ($queue && config('queue.default') === 'sync') {
                dispatch(function () use ($mailService, $mailable) {
                    $mailService->send($mailable);
                })->afterResponse();

                return true;
            }

            // Sinon envoyer directement avec retry
            return $mailService->send($mailable);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi d\'email via trait', [
                'mailable' => get_class($mailable),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Tenter un envoi basique comme dernier recours
            try {
                return $mailService->send($mailable, [
                    'attempts' => 1,
                    'use_fallback' => true,
                ]);
            } catch (\Exception $fallbackError) {
                Log::critical('Échec complet de l\'envoi d\'email', [
                    'error' => $fallbackError->getMessage(),
                ]);

                return false;
            }
        }
    }

    /**
     * Envoyer plusieurs emails de manière sécurisée
     *
     * @param  array  $mailables  Tableau de Mailable
     * @param  bool  $queue  Utiliser la queue si disponible
     * @return array Résultats de l'envoi [succès, échecs]
     */
    protected function sendMultipleEmails(array $mailables, bool $queue = true): array
    {
        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($mailables as $key => $mailable) {
            if (! $mailable instanceof Mailable) {
                Log::warning('Objet non-Mailable ignoré', ['key' => $key]);
                $results['failed'][] = $key;

                continue;
            }

            $sent = $this->sendEmail($mailable, $queue);

            if ($sent) {
                $results['success'][] = $key;
            } else {
                $results['failed'][] = $key;
            }
        }

        // Log du résumé
        Log::info('Résumé d\'envoi multiple d\'emails', [
            'total' => count($mailables),
            'success' => count($results['success']),
            'failed' => count($results['failed']),
        ]);

        return $results;
    }

    /**
     * Envoyer un email avec validation préalable
     *
     * @param  array  $recipients  Destinataires à valider
     */
    protected function sendValidatedEmail(Mailable $mailable, array $recipients, bool $queue = true): bool
    {
        // Valider les adresses email
        $validRecipients = $this->validateEmailAddresses($recipients);

        if (empty($validRecipients)) {
            Log::error('Aucun destinataire valide pour l\'email', [
                'mailable' => get_class($mailable),
                'invalid_recipients' => $recipients,
            ]);

            return false;
        }

        // Log des destinataires invalides s'il y en a
        $invalidRecipients = array_diff($recipients, $validRecipients);
        if (! empty($invalidRecipients)) {
            Log::warning('Certains destinataires sont invalides', [
                'invalid' => $invalidRecipients,
            ]);
        }

        return $this->sendEmail($mailable, $queue);
    }

    /**
     * Valider des adresses email
     *
     * @return array Emails valides
     */
    protected function validateEmailAddresses(array $emails): array
    {
        $valid = [];

        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $valid[] = $email;
            } else {
                Log::warning('Adresse email invalide', ['email' => $email]);
            }
        }

        return $valid;
    }

    /**
     * Tester la connexion mail
     */
    protected function testMailConnection(): bool
    {
        try {
            return $this->getMailService()->testConnection();
        } catch (\Exception $e) {
            Log::error('Erreur lors du test de connexion mail', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
