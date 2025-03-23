<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonResponseRoute
{
    public function handle(Request $request, Closure $next)
    {
        // Marquer cette requête comme attendant une réponse JSON
        $request->attributes->set('expects_json_response', true);

        return $next($request);
    }
}
