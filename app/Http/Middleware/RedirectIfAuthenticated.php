<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, \Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // Priorité des redirections en fonction des permissions
                // L'accès complet a la priorité la plus élevée
                if ($user->can('access-un-all')) {
                    return redirect(RouteServiceProvider::GATEWAY);
                }

                // Ensuite, vérifier les autres permissions dans l'ordre de priorité
                if ($user->can('access-un-opti-hr')) {
                    return redirect(RouteServiceProvider::OPTI_HR_HOME);
                }

                if ($user->can('access-un-recours')) {
                    return redirect(RouteServiceProvider::RECOURS_HOME);
                }

                // Si l'utilisateur n'a aucune permission spécifique
                return redirect(RouteServiceProvider::GATEWAY);
            }
        }

        return $next($request);
    }
}
