<?php

// app/Http/Middleware/RedirectToAppropriateHome.php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectToAppropriateHome
{
    // Liste des routes qui ne doivent pas être soumises à la redirection
    protected $except = [
        '/logout',
        '/api/*',
        '/login',
        '/register',
        '/password/*',
        '/assets/*',
        '/js/*',
        '/css/*',
        '/images/*',
    ];

    public function handle(Request $request, \Closure $next)
    {
        // Ignorer certaines routes
        foreach ($this->except as $path) {
            if ($request->is($path)) {
                return $next($request);
            }
        }

        // Si l'utilisateur est authentifié
        if (Auth::check()) {
            $user = Auth::user();
            $currentPath = $request->path();

            // Vérifier si l'utilisateur est sur la bonne route selon ses permissions
            if ($currentPath === 'recours' && !$user->hasPermissionTo('access-recours') && !$user->hasPermissionTo('access-all')) {
                return $this->redirectBasedOnPermission($user);
            }

            if ($currentPath === 'opti-hr' && !$user->hasPermissionTo('access-opti-hr') && !$user->hasPermissionTo('access-all')) {
                return $this->redirectBasedOnPermission($user);
            }

            if ($currentPath === '/' && !$user->hasPermissionTo('access-all')) {
                return $this->redirectBasedOnPermission($user);
            }
        }

        return $next($request);
    }

    private function redirectBasedOnPermission($user)
    {
        if ($user->hasPermissionTo('access-all')) {
            return redirect(RouteServiceProvider::GATEWAY);
        }

        if ($user->hasPermissionTo('access-opti-hr')) {
            return redirect(RouteServiceProvider::OPTI_HR_HOME);
        }

        if ($user->hasPermissionTo('access-recours')) {
            return redirect(RouteServiceProvider::RECOURS_HOME);
        }

        return redirect('/');
    }
}
