<?php

namespace App\Jobs;

use App\Services\MailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Le nombre de fois que le job peut être tenté
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Le nombre de secondes avant timeout
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Le délai entre les tentatives (en secondes)
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * L'instance Mailable à envoyer
     *
     * @var Mailable
     */
    protected Mailable $mailable;

    /**
     * Options supplémentaires pour l'envoi
     *
     * @var array
     */
    protected array $options;

    /**
     * Créer une nouvelle instance du job
     *
     * @param Mailable $mailable
     * @param array $options
     */
    public function __construct(Mailable $mailable, array $options = [])
    {
        $this->mailable = $mailable;
        $this->options = $options;
    }

    /**
     * Exécuter le job
     *
     * @param MailService $mailService
     * @return void
     */
    public function handle(MailService $mailService)
    {
        try {
            Log::info('Traitement du job d\'envoi d\'email', [
                'mailable' => get_class($this->mailable),
                'attempt' => $this->attempts(),
                'job_id' => $this->job->getJobId()
            ]);

            // Utiliser le service mail pour envoyer avec retry
            $sent = $mailService->send($this->mailable, array_merge([
                'attempts' => 2, // Moins de tentatives car le job lui-même peut être retenté
                'delay' => 3,
                'use_fallback' => true
            ], $this->options));

            if (!$sent) {
                // Si l'envoi échoue, lancer une exception pour que le job soit retenté
                throw new \Exception('L\'envoi de l\'email a échoué après toutes les tentatives');
            }

            Log::info('Email envoyé avec succès via job', [
                'mailable' => get_class($this->mailable),
                'job_id' => $this->job->getJobId()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du job d\'email', [
                'mailable' => get_class($this->mailable),
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
                'job_id' => $this->job->getJobId()
            ]);

            // Si c'est la dernière tentative, enregistrer l'échec définitif
            if ($this->attempts() >= $this->tries) {
                $this->handleFinalFailure($e);
            }

            // Relancer l'exception pour que Laravel puisse retenter le job
            throw $e;
        }
    }

    /**
     * Gérer l'échec définitif du job
     *
     * @param \Exception $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        Log::critical('Job d\'envoi d\'email définitivement échoué', [
            'mailable' => get_class($this->mailable),
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts(),
            'job_id' => $this->job->getJobId() ?? 'unknown'
        ]);

        // Envoyer une notification aux administrateurs si configuré
        $this->notifyAdministrators($exception);
    }

    /**
     * Gérer l'échec final (avant la méthode failed)
     *
     * @param \Exception $exception
     * @return void
     */
    protected function handleFinalFailure(\Exception $exception)
    {
        // Enregistrer dans les logs l'échec définitif avec plus de détails
        Log::critical('Échec définitif de l\'envoi d\'email après toutes les tentatives', [
            'mailable' => get_class($this->mailable),
            'recipients' => $this->getRecipients(),
            'subject' => $this->getSubject(),
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        // Optionnel : sauvegarder l'email dans un fichier pour analyse ultérieure
        $this->saveFailedEmailToFile();
    }

    /**
     * Notifier les administrateurs d'un échec d'email
     *
     * @param \Exception $exception
     * @return void
     */
    protected function notifyAdministrators(\Exception $exception)
    {
        try {
            // Si un email d'administrateur est configuré
            $adminEmail = config('mail.admin_notification_email');
            
            if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                // Log uniquement car on ne peut pas envoyer d'email si le système d'email est en panne
                Log::alert('Email critique non envoyé - Notification admin requise', [
                    'admin_email' => $adminEmail,
                    'failed_mailable' => get_class($this->mailable),
                    'error' => $exception->getMessage()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Impossible de notifier les administrateurs', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Sauvegarder l'email échoué dans un fichier
     *
     * @return void
     */
    protected function saveFailedEmailToFile()
    {
        try {
            $failedMailsPath = storage_path('logs/failed_mails');
            
            // Créer le dossier s'il n'existe pas
            if (!file_exists($failedMailsPath)) {
                mkdir($failedMailsPath, 0755, true);
            }

            $filename = sprintf(
                '%s/%s_%s.json',
                $failedMailsPath,
                date('Y-m-d_H-i-s'),
                uniqid()
            );

            $data = [
                'mailable_class' => get_class($this->mailable),
                'recipients' => $this->getRecipients(),
                'subject' => $this->getSubject(),
                'timestamp' => now()->toIso8601String(),
                'attempts' => $this->attempts(),
                'job_id' => $this->job->getJobId() ?? 'unknown'
            ];

            file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));

            Log::info('Email échoué sauvegardé', ['file' => $filename]);

        } catch (\Exception $e) {
            Log::error('Impossible de sauvegarder l\'email échoué', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtenir les destinataires de l'email
     *
     * @return array
     */
    protected function getRecipients(): array
    {
        try {
            $envelope = $this->mailable->envelope();
            $recipients = [];
            
            if ($envelope && property_exists($envelope, 'to')) {
                $to = $envelope->to;
                if (is_array($to)) {
                    foreach ($to as $recipient) {
                        if (is_object($recipient) && property_exists($recipient, 'address')) {
                            $recipients[] = $recipient->address;
                        } elseif (is_string($recipient)) {
                            $recipients[] = $recipient;
                        }
                    }
                }
            }
            
            return $recipients;
        } catch (\Exception $e) {
            return ['unknown'];
        }
    }

    /**
     * Obtenir le sujet de l'email
     *
     * @return string
     */
    protected function getSubject(): string
    {
        try {
            $envelope = $this->mailable->envelope();
            if ($envelope && property_exists($envelope, 'subject')) {
                return $envelope->subject;
            }
            return 'No subject';
        } catch (\Exception $e) {
            return 'Unknown subject';
        }
    }

    /**
     * Déterminer le délai avant la prochaine tentative
     *
     * @return array
     */
    public function backoff(): array
    {
        // Délai exponentiel : 60s, 120s, 240s
        return [60, 120, 240];
    }

    /**
     * Obtenir les tags pour ce job (pour Horizon si utilisé)
     *
     * @return array
     */
    public function tags(): array
    {
        return [
            'email',
            'mailable:' . class_basename($this->mailable)
        ];
    }
}