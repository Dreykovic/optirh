<?php

namespace App\Http\Controllers\Admin;

use App\Config\ActivityLogActions;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActivityLogController extends Controller
{
    /**
     * Le service de journalisation des activités
     *
     * @var ActivityLogger
     */
    protected $activityLogger;

    /**
     * Constructeur du contrôleur
     *
     * @param ActivityLogger $activityLogger
     */
    public function __construct(ActivityLogger $activityLogger)
    {
        $this->activityLogger = $activityLogger;
        $this->middleware(['permission:voir-une-activité|voir-un-tout'], ['only' => ['index']]);
    }

    /**
     * Affiche la liste des journaux d'activité
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            // Récupérer l'utilisateur connecté
            $user = auth()->user();

            // Initialiser la requête
            $query = ActivityLog::with('user');

            // Filtrer par utilisateur si ce n'est pas un super admin
            if (!$user->hasRole('super-admin')) {
                // Si l'utilisateur est un admin normal, il ne voit que ses propres logs
                $query->where('user_id', $user->id);
            } elseif ($request->has('user_id') && $request->user_id) {
                // Si c'est un super admin qui a spécifié un utilisateur dans le filtre
                $query->where('user_id', $request->user_id);
            }

            // Filtrer par groupe d'action
            if ($request->has('action_group') && $request->action_group && $request->action_group !== 'all') {
                $actionCodes = ActivityLogActions::getActionCodesByGroup($request->action_group);
                $query->whereIn('action', $actionCodes);
            }

            // Filtrer par action spécifique
            if ($request->has('action') && $request->action) {
                $query->where('action', $request->action);
            }

            // Filtrer par date de début
            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            // Filtrer par date de fin
            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Filtrer par type de modèle
            if ($request->has('model_type') && $request->model_type) {
                $query->where('model_type', $request->model_type);
            }

            // Récupérer les logs avec pagination
            $logs = $query->latest()->paginate(25);

            // Pour les super admin, récupérer la liste des utilisateurs pour le filtre
            $users = [];
            if ($user->hasRole('super-admin')) {
                $users = User::all(['id', 'name']);
            }

            // Récupérer les types de modèles distincts pour le filtre
            $modelTypes = ActivityLog::distinct()->pluck('model_type')->filter()->toArray();

            $this->activityLogger->log(
                'view',
                "Consultation du journal d'activités"
            );

            return view('pages.admin.activity-logs.index', compact('logs', 'users', 'modelTypes'));
        } catch (\Throwable $th) {
            Log::error('Erreur lors du chargement du journal d\'activités', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            return back()->with('error', 'Une erreur s\'est produite lors du chargement du journal d\'activités. Veuillez réessayer.');
        }
    }

    /**
     * Affiche les détails d'un log d'activité spécifique
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $log = ActivityLog::with('user')->findOrFail($id);

            // Vérifier que l'utilisateur a le droit de voir ce log
            $this->authorize('view', $log);

            $this->activityLogger->log(
                'view',
                "Consultation des détails du log d'activité #{$id}"
            );

            return view('pages.admin.activity-logs.show', compact('log'));
        } catch (\Throwable $th) {
            Log::error('Erreur lors de l\'affichage des détails d\'un log d\'activité', [
                'log_id' => $id,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            return back()->with('error', 'Une erreur s\'est produite lors du chargement des détails du log. Veuillez réessayer.');
        }
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
        try {
            // Calcul de la date limite
            $cutoffDate = now()->subDays($days);

            // Récupérer le nombre de logs qui seront supprimés pour le journaliser
            $countToDelete = ActivityLog::where('created_at', '<', $cutoffDate)->count();

            // Supprimer les logs plus anciens que la date limite
            ActivityLog::where('created_at', '<', $cutoffDate)->delete();

            // Journaliser l'opération
            $this->activityLogger->log(
                'deleted',
                "Nettoyage automatique des logs d'activité plus anciens que {$days} jours",
                null,
                ['cutoff_date' => $cutoffDate->format('Y-m-d'), 'deleted_count' => $countToDelete]
            );

            return true;
        } catch (\Throwable $th) {
            Log::error('Erreur lors du nettoyage des logs d\'activité', [
                'days' => $days,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            return false;
        }
    }
}
