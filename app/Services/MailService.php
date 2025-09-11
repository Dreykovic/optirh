<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Mailable;
use App\Jobs\SendEmailJob;
use Exception;
use Throwable;

class MailService
{
    /**
     * Nombre maximum de tentatives d'envoi
     */
    private const MAX_RETRY_ATTEMPTS = 3;
    
    /**
     * Délai entre les tentatives (en secondes)
     */
    private const RETRY_DELAY = 5;
    
    /**
     * Timeout pour l'envoi SMTP (en secondes)
     */
    private const SMTP_TIMEOUT = 30;

    /**
     * Envoyer un email avec système de retry et fallback
     *
     * @param Mailable $mailable
     * @param array $options Options supplémentaires (retry, fallback, etc.)
     * @return bool
     */
    public function send(Mailable $mailable, array $options = []): bool
    {
        $attempts = $options['attempts'] ?? self::MAX_RETRY_ATTEMPTS;
        $delay = $options['delay'] ?? self::RETRY_DELAY;
        $useFallback = $options['use_fallback'] ?? true;
        
        // Log du début de l'envoi
        $this->logMailAttempt($mailable, 'Tentative d\'envoi');
        
        // Tentatives d'envoi avec le mailer principal
        for ($i = 1; $i <= $attempts; $i++) {
            try {
                // Configuration du timeout pour SMTP
                $this->configureSmtpTimeout();
                
                // Tentative d'envoi
                Mail::send($mailable);
                
                // Succès - log et retour
                $this->logMailSuccess($mailable, $i);
                return true;
                
            } catch (Exception $e) {
                // Échec - log de l'erreur
                $this->logMailError($mailable, $e, $i, $attempts);
                
                // Si ce n'est pas la dernière tentative, attendre avant de réessayer
                if ($i < $attempts) {
                    sleep($delay);
                }
            }
        }
        
        // Si toutes les tentatives ont échoué et que le fallback est activé
        if ($useFallback) {
            return $this->sendWithFallback($mailable);
        }
        
        // Échec complet
        $this->logMailFailure($mailable);
        return false;
    }
    
    /**
     * Envoyer un email de manière asynchrone via la queue
     *
     * @param Mailable $mailable
     * @param string $queue Nom de la queue à utiliser
     * @return bool
     */
    public function queue(Mailable $mailable, string $queue = 'emails'): bool
    {
        try {
            // Si la queue est configurée, utiliser notre job personnalisé
            if (config('queue.default') !== 'sync') {
                // Dispatcher le job avec des options
                SendEmailJob::dispatch($mailable)
                    ->onQueue($queue)
                    ->delay(now()->addSeconds(2)); // Petit délai pour éviter la surcharge
                
                $this->logMailQueued($mailable);
                return true;
            }
            
            // Sinon, envoyer directement avec retry
            return $this->send($mailable);
            
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise en queue de l\'email', [
                'mailable' => get_class($mailable),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback : envoyer directement
            return $this->send($mailable);
        }
    }
    
    /**
     * Envoyer avec le système de fallback
     *
     * @param Mailable $mailable
     * @return bool
     */
    protected function sendWithFallback(Mailable $mailable): bool
    {
        Log::info('Utilisation du système de fallback pour l\'envoi d\'email', [
            'mailable' => get_class($mailable)
        ]);
        
        try {
            // Utiliser le mailer de fallback configuré
            Mail::mailer('failover')->send($mailable);
            
            $this->logMailSuccess($mailable, 0, true);
            return true;
            
        } catch (Exception $e) {
            // Si même le fallback échoue, essayer le log
            return $this->sendToLog($mailable);
        }
    }
    
    /**
     * Envoyer l'email vers les logs (dernier recours)
     *
     * @param Mailable $mailable
     * @return bool
     */
    protected function sendToLog(Mailable $mailable): bool
    {
        try {
            // Utiliser le driver log pour sauvegarder l'email
            Mail::mailer('log')->send($mailable);
            
            Log::warning('Email envoyé vers les logs (fallback ultime)', [
                'mailable' => get_class($mailable),
                'to' => $this->getRecipients($mailable),
                'subject' => $this->getSubject($mailable)
            ]);
            
            return true;
            
        } catch (Exception $e) {
            Log::critical('Échec complet de l\'envoi d\'email', [
                'mailable' => get_class($mailable),
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
    
    /**
     * Configurer le timeout SMTP
     */
    protected function configureSmtpTimeout(): void
    {
        $timeout = self::SMTP_TIMEOUT;
        
        // Configurer le timeout dynamiquement
        config(['mail.mailers.smtp.timeout' => $timeout]);
        
        // Si Swift Mailer est utilisé (Laravel < 9)
        if (class_exists('Swift_Preferences')) {
            \Swift_Preferences::getInstance()->setConnectionTimeout($timeout);
        }
    }
    
    /**
     * Obtenir les destinataires du mail
     *
     * @param Mailable $mailable
     * @return array
     */
    protected function getRecipients(Mailable $mailable): array
    {
        try {
            $envelope = $mailable->envelope();
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
        } catch (Exception $e) {
            return ['unknown'];
        }
    }
    
    /**
     * Obtenir le sujet du mail
     *
     * @param Mailable $mailable
     * @return string
     */
    protected function getSubject(Mailable $mailable): string
    {
        try {
            $envelope = $mailable->envelope();
            if ($envelope && property_exists($envelope, 'subject')) {
                return $envelope->subject;
            }
            return 'No subject';
        } catch (Exception $e) {
            return 'Unknown subject';
        }
    }
    
    /**
     * Log de tentative d'envoi
     *
     * @param Mailable $mailable
     * @param string $message
     */
    protected function logMailAttempt(Mailable $mailable, string $message): void
    {
        Log::info($message, [
            'mailable' => get_class($mailable),
            'to' => $this->getRecipients($mailable),
            'subject' => $this->getSubject($mailable),
            'timestamp' => now()->toIso8601String()
        ]);
    }
    
    /**
     * Log de succès d'envoi
     *
     * @param Mailable $mailable
     * @param int $attempt
     * @param bool $isFallback
     */
    protected function logMailSuccess(Mailable $mailable, int $attempt, bool $isFallback = false): void
    {
        $message = $isFallback 
            ? 'Email envoyé avec succès via fallback'
            : "Email envoyé avec succès (tentative $attempt)";
            
        Log::info($message, [
            'mailable' => get_class($mailable),
            'to' => $this->getRecipients($mailable),
            'subject' => $this->getSubject($mailable),
            'attempt' => $attempt,
            'timestamp' => now()->toIso8601String()
        ]);
    }
    
    /**
     * Log d'erreur d'envoi
     *
     * @param Mailable $mailable
     * @param Throwable $error
     * @param int $attempt
     * @param int $maxAttempts
     */
    protected function logMailError(Mailable $mailable, Throwable $error, int $attempt, int $maxAttempts): void
    {
        Log::error("Échec de l'envoi d'email (tentative $attempt/$maxAttempts)", [
            'mailable' => get_class($mailable),
            'to' => $this->getRecipients($mailable),
            'subject' => $this->getSubject($mailable),
            'error' => $error->getMessage(),
            'error_code' => $error->getCode(),
            'timestamp' => now()->toIso8601String()
        ]);
    }
    
    /**
     * Log d'échec complet
     *
     * @param Mailable $mailable
     */
    protected function logMailFailure(Mailable $mailable): void
    {
        Log::critical('Échec définitif de l\'envoi d\'email après toutes les tentatives', [
            'mailable' => get_class($mailable),
            'to' => $this->getRecipients($mailable),
            'subject' => $this->getSubject($mailable),
            'timestamp' => now()->toIso8601String()
        ]);
    }
    
    /**
     * Log de mise en queue
     *
     * @param Mailable $mailable
     */
    protected function logMailQueued(Mailable $mailable): void
    {
        Log::info('Email mis en queue pour envoi asynchrone', [
            'mailable' => get_class($mailable),
            'to' => $this->getRecipients($mailable),
            'subject' => $this->getSubject($mailable),
            'timestamp' => now()->toIso8601String()
        ]);
    }
    
    /**
     * Vérifier la configuration mail
     *
     * @return array
     */
    public function checkConfiguration(): array
    {
        $issues = [];
        
        // Vérifier la configuration SMTP
        if (config('mail.default') === 'smtp') {
            if (empty(config('mail.mailers.smtp.host'))) {
                $issues[] = 'MAIL_HOST non configuré';
            }
            if (empty(config('mail.mailers.smtp.port'))) {
                $issues[] = 'MAIL_PORT non configuré';
            }
            if (config('mail.mailers.smtp.host') === 'mailpit' && env('APP_ENV') === 'production') {
                $issues[] = 'Mailpit configuré en production - utiliser un vrai serveur SMTP';
            }
        }
        
        // Vérifier l'adresse d'expédition
        if (config('mail.from.address') === 'hello@example.com') {
            $issues[] = 'MAIL_FROM_ADDRESS utilise la valeur par défaut';
        }
        
        // Vérifier la configuration de la queue
        if (config('queue.default') === 'sync' && env('APP_ENV') === 'production') {
            $issues[] = 'Queue synchrone en production - considérer l\'utilisation de database ou redis';
        }
        
        return [
            'is_valid' => empty($issues),
            'issues' => $issues,
            'config' => [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'from' => config('mail.from.address'),
                'queue' => config('queue.default')
            ]
        ];
    }
    
    /**
     * Tester la connexion SMTP
     *
     * @return bool
     */
    public function testConnection(): bool
    {
        try {
            $host = config('mail.mailers.smtp.host');
            $port = config('mail.mailers.smtp.port');
            
            if (empty($host) || empty($port)) {
                Log::error('Configuration SMTP manquante');
                return false;
            }
            
            // Tenter une connexion socket
            $timeout = 5;
            $socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
            
            if ($socket) {
                fclose($socket);
                Log::info('Connexion SMTP réussie', ['host' => $host, 'port' => $port]);
                return true;
            }
            
            Log::error('Connexion SMTP échouée', [
                'host' => $host,
                'port' => $port,
                'error' => $errstr,
                'errno' => $errno
            ]);
            return false;
            
        } catch (Exception $e) {
            Log::error('Erreur lors du test de connexion SMTP', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}