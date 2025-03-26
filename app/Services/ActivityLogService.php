<?php

namespace App\Services;

use App\Config\ActivityLogActions;
use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Journalise une action utilisateur
     *
     * @param string $action Le code de l'action (doit être défini dans ActivityLogActions::ACTIONS)
     * @param string $description Description de l'action
     * @param Model|null $model Le modèle concerné par l'action (optionnel)
     * @param array $additionalData Données additionnelles à enregistrer (optionnel)
     * @return ActivityLog
     * @throws \InvalidArgumentException Si le code d'action n'est pas valide
     */
    public function log($action, $description, ?Model $model = null, array $additionalData = [])
    {
        // Vérifier que l'action est valide
        if (!in_array($action, ActivityLogActions::getAllActionCodes())) {
            // Enregistrer quand même mais avec un avertissement
            \Illuminate\Support\Facades\Log::warning("Code d'action de log non standard utilisé: {$action}");
        }
        $user = auth()->user();
        $logData = [
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'additional_data' => json_encode($additionalData),
        ];

        if ($model) {
            $logData['model_type'] = get_class($model);
            $logData['model_id'] = $model->id;
        }

        return ActivityLog::create($logData);
    }

    /**
     * Journalise une action de consultation
     *
     * @param string $description
     * @param Model|null $model
     * @param array $additionalData
     * @return ActivityLog
     */
    public function logView($description, ?Model $model = null, array $additionalData = [])
    {
        return $this->log('view', $description, $model, $additionalData);
    }

    /**
     * Journalise une action de création
     *
     * @param string $description
     * @param Model|null $model
     * @param array $additionalData
     * @return ActivityLog
     */
    public function logCreation($description, ?Model $model = null, array $additionalData = [])
    {
        return $this->log('created', $description, $model, $additionalData);
    }

    /**
     * Journalise une action de modification
     *
     * @param string $description
     * @param Model|null $model
     * @param array $additionalData
     * @return ActivityLog
     */
    public function logUpdate($description, ?Model $model = null, array $additionalData = [])
    {
        return $this->log('updated', $description, $model, $additionalData);
    }

    /**
     * Journalise une action de suppression
     *
     * @param string $description
     * @param Model|null $model
     * @param array $additionalData
     * @return ActivityLog
     */
    public function logDeletion($description, ?Model $model = null, array $additionalData = [])
    {
        return $this->log('deleted', $description, $model, $additionalData);
    }

    /**
     * Journalise une action d'approbation
     *
     * @param string $description
     * @param Model|null $model
     * @param array $additionalData
     * @return ActivityLog
     */
    public function logApproval($description, ?Model $model = null, array $additionalData = [])
    {
        return $this->log('approved', $description, $model, $additionalData);
    }

    /**
     * Journalise une action de rejet
     *
     * @param string $description
     * @param Model|null $model
     * @param array $additionalData
     * @return ActivityLog
     */
    public function logRejection($description, ?Model $model = null, array $additionalData = [])
    {
        return $this->log('rejected', $description, $model, $additionalData);
    }

    /**
     * Journalise une erreur
     *
     * @param string $description
     * @param Model|null $model
     * @param array $additionalData
     * @return ActivityLog
     */
    public function logError($description, ?Model $model = null, array $additionalData = [])
    {
        return $this->log('error', $description, $model, $additionalData);
    }

    /**
     * Journalise une action de téléchargement
     *
     * @param string $description
     * @param Model|null $model
     * @param array $additionalData
     * @return ActivityLog
     */
    public function logDownload($description, ?Model $model = null, array $additionalData = [])
    {
        return $this->log('download', $description, $model, $additionalData);
    }

    /**
     * Supprime les logs d'activité plus anciens qu'une certaine période
     * Cette méthode est destinée à être utilisée par une tâche planifiée
     *
     * @param int $days Nombre de jours à conserver
     * @return bool
     */
    public function cleanup(int $days = 90)
    {

        // Calcul de la date limite
        $cutoffDate = now()->subDays($days);

        // Récupérer le nombre de logs qui seront supprimés pour le journaliser
        $countToDelete = ActivityLog::where('created_at', '<', $cutoffDate)->count();

        // Supprimer les logs plus anciens que la date limite
        ActivityLog::where('created_at', '<', $cutoffDate)->delete();

        // Journaliser l'opération
        $this->log(
            'deleted',
            "Nettoyage automatique des logs d'activité plus anciens que {$days} jours",
            null,
            ['cutoff_date' => $cutoffDate->format('Y-m-d'), 'deleted_count' => $countToDelete]
        );

        return true;

    }
}
