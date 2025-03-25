<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ActivityLogService;

class CleanupActivityLogs extends Command
{
    /**
     * Le nom et la signature de la commande console.
     *
     * @var string
     */
    protected $signature = 'activity-logs:cleanup {--days=90 : Nombre de jours de logs à conserver}';

    /**
     * La description de la commande console.
     *
     * @var string
     */
    protected $description = "Nettoie les logs d'activité plus anciens qu'un nombre de jours spécifié";

    /**
     * Le service de gestion des logs d'activité.
     * 
     * @var ActivityLogService
     */
    protected $activityLogService;

    /**
     * Création d'une nouvelle instance de commande.
     *
     * @param ActivityLogService $activityLogService
     * @return void
     */
    public function __construct(ActivityLogService $activityLogService)
    {
        parent::__construct();
        $this->activityLogService = $activityLogService;
    }

    /**
     * Exécuter la commande console.
     *
     * @return int
     */
    public function handle()
    {
        $days = $this->option('days');
        
        $this->info("Nettoyage des logs d'activité plus anciens que {$days} jours...");
        
        $result = $this->activityLogService->cleanup($days);
        
        if ($result) {
            $this->info('Nettoyage terminé avec succès.');
        } else {
            $this->error('Une erreur est survenue lors du nettoyage.');
        }
        
        return 0;
    }
}