<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
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

        // Appliquer les filtres de recherche
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Récupérer les logs avec pagination
        $logs = $query->latest()->paginate(15);

        // Pour les super admin, récupérer la liste des utilisateurs pour le filtre
        $users = [];
        if ($user->hasRole('super-admin')) {
            $users = User::all(['id', 'name']);
        }

        return view('pages.admin.users.activity-logs.index', compact('logs', 'users'));
    }

    public function show(ActivityLog $log)
    {
        // Vérifier que l'utilisateur a le droit de voir ce log
        $this->authorize('view', $log);

        return view('pages.admin.users.activity-logs.show', compact('log'));
    }
}
