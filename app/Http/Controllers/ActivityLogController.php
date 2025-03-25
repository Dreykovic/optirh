<?php

namespace App\Http\Controllers;

use App\Config\ActivityLogActions;
use App\Http\Controllers\Controller;
use App\Jobs\CleanupActivityLogsJob;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActivityLogController extends Controller
{
    /**
     * Le service de journalisation des activités
     *
     * @var ActivityLogService
     */
    protected $activityLogger;

    /**
     * Constructeur du contrôleur
     *
     * @param ActivityLogService $activityLogger
     */
    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;

    }

    /**
     * Affiche la liste des journaux d'activité
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        CleanupActivityLogsJob::dispatch(90);
        // Récupérer l'utilisateur connecté
        $user = auth()->user();

        // Initialiser la requête
        $query = ActivityLog::with('user');

        // Filtrer par utilisateur si ce n'est pas un super admin
        if (!$user->hasRole('GRH')) {
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
        if ($user->hasRole('GRH')) {
            $users = User::all(['id', 'username']);
        }

        // Récupérer les types de modèles distincts pour le filtre
        $modelTypes = ActivityLog::distinct()->pluck('model_type')->filter()->toArray();



        return view('modules.opti-hr.pages.users.activity-logs.index', compact('logs', 'users', 'modelTypes'));

    }

    /**
     * Affiche les détails d'un log d'activité spécifique
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {

        $log = ActivityLog::with('user')->findOrFail($id);

        // Vérifier que l'utilisateur a le droit de voir ce log
        $this->authorize('view', $log);

        $this->activityLogger->log(
            'view',
            "Consultation des détails du log d'activité #{$id}"
        );

        return view('modules.opti-hr.pages.users.activity-logs.show', compact('log'));

    }




}
