<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class MonitorFailedMails extends Command
{
    /**
     * Le nom et la signature de la commande console
     *
     * @var string
     */
    protected $signature = 'mail:monitor 
                            {--days=7 : Nombre de jours à analyser}
                            {--clean : Nettoyer les anciens fichiers de logs}';

    /**
     * La description de la commande console
     *
     * @var string
     */
    protected $description = 'Monitorer et analyser les emails échoués';

    /**
     * Exécuter la commande console
     *
     * @return int
     */
    public function handle()
    {
        $this->info('📊 Monitoring des Emails - OPTIRH');
        $this->newLine();

        $failedMailsPath = storage_path('logs/failed_mails');

        // Vérifier si le dossier existe
        if (!File::exists($failedMailsPath)) {
            $this->info('✅ Aucun email échoué trouvé.');
            $this->info('Le système fonctionne parfaitement !');
            return Command::SUCCESS;
        }

        // Récupérer tous les fichiers JSON
        $files = File::glob($failedMailsPath . '/*.json');
        
        if (empty($files)) {
            $this->info('✅ Aucun email échoué trouvé.');
            return Command::SUCCESS;
        }

        // Analyser les emails échoués
        $this->analyzeFailedMails($files);

        // Nettoyer les anciens fichiers si demandé
        if ($this->option('clean')) {
            $this->cleanOldFiles($files);
        }

        return Command::SUCCESS;
    }

    /**
     * Analyser les emails échoués
     *
     * @param array $files
     */
    protected function analyzeFailedMails(array $files): void
    {
        $days = (int) $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);
        
        $failedMails = [];
        $stats = [
            'total' => 0,
            'by_class' => [],
            'by_date' => [],
            'recent' => 0
        ];

        foreach ($files as $file) {
            $content = json_decode(File::get($file), true);
            
            if (!$content) continue;

            $timestamp = Carbon::parse($content['timestamp']);
            
            // Compter tous les emails échoués
            $stats['total']++;
            
            // Grouper par classe de Mailable
            $class = basename($content['mailable_class']);
            $stats['by_class'][$class] = ($stats['by_class'][$class] ?? 0) + 1;
            
            // Grouper par date
            $date = $timestamp->format('Y-m-d');
            $stats['by_date'][$date] = ($stats['by_date'][$date] ?? 0) + 1;
            
            // Compter les récents
            if ($timestamp->isAfter($cutoffDate)) {
                $stats['recent']++;
                $failedMails[] = $content;
            }
        }

        // Afficher les statistiques
        $this->displayStatistics($stats, $days);
        
        // Afficher les détails des emails récents
        if (!empty($failedMails)) {
            $this->displayRecentFailures($failedMails);
        }
    }

    /**
     * Afficher les statistiques
     *
     * @param array $stats
     * @param int $days
     */
    protected function displayStatistics(array $stats, int $days): void
    {
        $this->info("📈 Statistiques des Emails Échoués");
        $this->newLine();
        
        // Statistiques générales
        $this->table(
            ['Métrique', 'Valeur'],
            [
                ['Total des échecs', $stats['total']],
                ["Échecs récents ($days derniers jours)", $stats['recent']],
                ['Types d\'emails différents', count($stats['by_class'])],
            ]
        );

        // Top des types d'emails échoués
        if (!empty($stats['by_class'])) {
            $this->newLine();
            $this->info("📧 Types d'Emails les Plus Échoués:");
            
            arsort($stats['by_class']);
            $topClasses = array_slice($stats['by_class'], 0, 5, true);
            
            foreach ($topClasses as $class => $count) {
                $percentage = round(($count / $stats['total']) * 100, 1);
                $this->line("  • $class: $count échecs ($percentage%)");
            }
        }

        // Tendance par jour
        if (!empty($stats['by_date'])) {
            $this->newLine();
            $this->info("📅 Tendance des Échecs (7 derniers jours):");
            
            krsort($stats['by_date']);
            $recentDates = array_slice($stats['by_date'], 0, 7, true);
            
            foreach ($recentDates as $date => $count) {
                $formattedDate = Carbon::parse($date)->format('d/m/Y');
                $bar = str_repeat('█', min($count, 20));
                $this->line("  $formattedDate: $bar $count");
            }
        }
    }

    /**
     * Afficher les échecs récents
     *
     * @param array $failedMails
     */
    protected function displayRecentFailures(array $failedMails): void
    {
        $this->newLine();
        $this->warn("⚠️ Emails Échoués Récents:");
        
        // Limiter à 10 emails récents
        $recentMails = array_slice($failedMails, -10);
        
        foreach ($recentMails as $mail) {
            $timestamp = Carbon::parse($mail['timestamp'])->format('d/m/Y H:i');
            $recipients = implode(', ', $mail['recipients'] ?? ['unknown']);
            $subject = $mail['subject'] ?? 'No subject';
            
            $this->line("  [$timestamp] $subject");
            $this->line("    → Destinataire(s): $recipients");
            $this->line("    → Tentatives: {$mail['attempts']}");
            $this->newLine();
        }
    }

    /**
     * Nettoyer les anciens fichiers
     *
     * @param array $files
     */
    protected function cleanOldFiles(array $files): void
    {
        $this->newLine();
        $this->info("🧹 Nettoyage des anciens logs...");
        
        $cutoffDate = Carbon::now()->subDays(30); // Garder 30 jours
        $deleted = 0;
        
        foreach ($files as $file) {
            $content = json_decode(File::get($file), true);
            
            if (!$content) continue;
            
            $timestamp = Carbon::parse($content['timestamp']);
            
            if ($timestamp->isBefore($cutoffDate)) {
                File::delete($file);
                $deleted++;
            }
        }
        
        if ($deleted > 0) {
            $this->info("✅ $deleted ancien(s) fichier(s) supprimé(s)");
        } else {
            $this->info("✅ Aucun fichier à nettoyer");
        }
    }
}