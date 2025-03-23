<?php

// app/Http/Middleware/EnsureUserHasAccess.php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasAccess
{
    public function handle(Request $request, \Closure $next, ...$permissions)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Si l'utilisateur possède l'une des permissions requises, continuer
        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return $next($request);
            }
        }

        // Si l'utilisateur n'a pas les permissions requises, rediriger vers la page appropriée
        if ($user->can('access-all')) {
            return redirect(RouteServiceProvider::GATEWAY);
        }

        if ($user->can('access-opti-hr')) {
            return redirect(RouteServiceProvider::OPTI_HR_HOME);
        }

        if ($user->can('access-recours')) {
            return redirect(RouteServiceProvider::RECOURS_HOME);
        }

        // Si aucune permission n'est trouvée, rediriger vers une page par défaut
        return redirect('/');
    }
}
