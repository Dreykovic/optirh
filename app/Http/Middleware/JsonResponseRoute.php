<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware JsonResponseRoute - Force les réponses JSON
 * 
 * Ce middleware marque les requêtes comme attendant une réponse JSON.
 * Il est utilisé pour les routes API ou AJAX qui doivent toujours 
 * retourner du JSON, même en cas d'erreur ou d'exception.
 * 
 * @package App\Http\Middleware
 * @author OPTIRH Team
 */
class JsonResponseRoute
{
    /**
     * Traite la requête HTTP entrante
     * 
     * Force l'attribut 'expects_json_response' à true pour indiquer
     * que cette route doit retourner une réponse JSON. Ceci est utile
     * pour les routes AJAX qui ont besoin d'une réponse structurée
     * même en cas d'erreur (validation, authentification, etc.).
     *
     * @param Request $request La requête HTTP entrante
     * @param Closure $next Le prochain middleware dans la pile
     * @return Response La réponse HTTP
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Marquer cette requête comme attendant une réponse JSON
        // Cet attribut sera utilisé par le gestionnaire d'exceptions
        // pour s'assurer que les erreurs sont retournées en JSON
        $request->attributes->set('expects_json_response', true);

        // Définir l'en-tête Accept pour forcer JSON si pas déjà présent
        if (!$request->hasHeader('Accept') || !str_contains($request->header('Accept'), 'application/json')) {
            $request->headers->set('Accept', 'application/json');
        }

        // Continuer vers le prochain middleware/contrôleur
        return $next($request);
    }
}
