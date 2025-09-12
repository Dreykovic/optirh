<?php

namespace App\Console\Commands;

use App\Services\MailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestMailSystem extends Command
{
    /**
     * Le nom et la signature de la commande console
     *
     * @var string
     */
    protected $signature = 'mail:test 
                            {email? : L\'adresse email de test} 
                            {--check : Vérifier uniquement la configuration}
                            {--connection : Tester la connexion SMTP}
                            {--send : Envoyer un email de test}
                            {--queue : Tester l\'envoi via la queue}';

    /**
     * La description de la commande console
     *
     * @var string
     */
    protected $description = 'Tester le système d\'envoi d\'emails';

    /**
     * Le service mail
     *
     * @var MailService
     */
    protected MailService $mailService;

    /**
     * Créer une nouvelle instance de commande
     *
     * @param MailService $mailService
     */
    public function __construct(MailService $mailService)
    {
        parent::__construct();
        $this->mailService = $mailService;
    }

    /**
     * Exécuter la commande console
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Test du système d\'envoi d\'emails OPTIRH');
        $this->newLine();

        // Si aucune option, afficher tout
        if (!$this->option('check') && !$this->option('connection') && !$this->option('send') && !$this->option('queue')) {
            $this->checkConfiguration();
            $this->testConnection();
            return Command::SUCCESS;
        }

        // Vérifier la configuration
        if ($this->option('check')) {
            $this->checkConfiguration();
        }

        // Tester la connexion
        if ($this->option('connection')) {
            $this->testConnection();
        }

        // Envoyer un email de test
        if ($this->option('send')) {
            $this->sendTestEmail();
        }

        // Tester la queue
        if ($this->option('queue')) {
            $this->testQueueEmail();
        }

        return Command::SUCCESS;
    }

    /**
     * Vérifier la configuration mail
     */
    protected function checkConfiguration(): void
    {
        $this->info('📋 Vérification de la configuration...');
        
        $check = $this->mailService->checkConfiguration();
        
        if ($check['is_valid']) {
            $this->info('✅ Configuration valide');
        } else {
            $this->error('❌ Problèmes de configuration détectés:');
            foreach ($check['issues'] as $issue) {
                $this->warn("  - $issue");
            }
        }

        $this->table(
            ['Paramètre', 'Valeur'],
            [
                ['Driver', $check['config']['driver']],
                ['Host', $check['config']['host'] ?? 'N/A'],
                ['Port', $check['config']['port'] ?? 'N/A'],
                ['From', $check['config']['from']],
                ['Queue', $check['config']['queue']],
            ]
        );

        $this->newLine();
    }

    /**
     * Tester la connexion SMTP
     */
    protected function testConnection(): void
    {
        $this->info('🔌 Test de connexion SMTP...');
        
        $connected = $this->mailService->testConnection();
        
        if ($connected) {
            $this->info('✅ Connexion SMTP réussie');
        } else {
            $this->error('❌ Connexion SMTP échouée');
            $this->warn('Vérifiez vos paramètres MAIL_HOST et MAIL_PORT dans le fichier .env');
        }

        $this->newLine();
    }

    /**
     * Envoyer un email de test
     */
    protected function sendTestEmail(): void
    {
        $email = $this->argument('email') ?? $this->ask('Entrez l\'adresse email de test');
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('❌ Adresse email invalide');
            return;
        }

        $this->info("📧 Envoi d'un email de test à $email...");

        try {
            // Créer un Mailable de test simple
            $testMailable = new \App\Mail\TestSystemMail($email);
            
            $sent = $this->mailService->send($testMailable);
            
            if ($sent) {
                $this->info('✅ Email envoyé avec succès');
                $this->info('Vérifiez la boîte de réception de ' . $email);
            } else {
                $this->error('❌ Échec de l\'envoi de l\'email');
                $this->warn('Consultez les logs pour plus de détails');
            }
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de l\'envoi: ' . $e->getMessage());
        }

        $this->newLine();
    }

    /**
     * Tester l'envoi via la queue
     */
    protected function testQueueEmail(): void
    {
        $email = $this->argument('email') ?? $this->ask('Entrez l\'adresse email de test');
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('❌ Adresse email invalide');
            return;
        }

        $this->info("📨 Test d'envoi via la queue à $email...");

        try {
            // Créer un Mailable de test
            $testMailable = new \App\Mail\TestSystemMail($email);
            
            $queued = $this->mailService->queue($testMailable);
            
            if ($queued) {
                if (config('queue.default') === 'sync') {
                    $this->warn('⚠️ La queue est configurée en mode "sync" - l\'email a été envoyé immédiatement');
                } else {
                    $this->info('✅ Email mis en queue avec succès');
                    $this->info('Assurez-vous que le worker de queue est en cours d\'exécution:');
                    $this->line('  php artisan queue:work');
                }
            } else {
                $this->error('❌ Échec de la mise en queue de l\'email');
            }
        } catch (\Exception $e) {
            $this->error('❌ Erreur: ' . $e->getMessage());
        }

        $this->newLine();
    }
}