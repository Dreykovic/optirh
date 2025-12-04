<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        // Exceptions que vous ne souhaitez pas journaliser
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Logique personnalisée pour journaliser les exceptions
        });
    }

    /**
     * Render une exception en HTTP response
     */
    public function render($request, Throwable $exception)
    {
        // Vérifier si la requête doit recevoir une réponse JSON
        if ($request->expectsJson() ||
            $request->is('api/*') ||
            $request->attributes->get('expects_json_response', false) ||
            $this->isJsonRoute($request)) {

            return $this->handleJsonException($request, $exception);
        }

        return $this->handleWebException($request, $exception);
    }

    /**
     * Déterminer si la route actuelle est configurée pour retourner du JSON
     */
    private function isJsonRoute(Request $request): bool
    {
        // Vérifier d'abord si la route a été marquée par le middleware
        if ($request->attributes->get('expects_json_response', false)) {
            return true;
        }

        // Ensuite appliquer la détection automatique
        if ($request->ajax() || $request->wantsJson()) {
            return true;
        }

        // Autres vérifications automatiques...

        return false;
    }

    /**
     * Gérer les exceptions pour les requêtes JSON
     */
    private function handleJsonException($request, Throwable $exception)
    {
        // Définir le code HTTP et la réponse par défaut
        $status = 500;
        $response = [
            'ok' => false,
            'message' => 'Une erreur est survenue sur le serveur.',
        ];

        // Personnaliser selon le type d'exception
        if ($exception instanceof ValidationException) {
            $status = 422;
            $response['message'] = 'Les données fournies sont invalides.';
            $response['errors'] = $exception->errors();
        } elseif ($exception instanceof ModelNotFoundException) {
            $status = 404;
            $response['message'] = 'Données introuvables. Veuillez vérifier les entrées.';
        } elseif ($exception instanceof NotFoundHttpException) {
            $status = 404;
            $response['message'] = 'La route demandée n\'existe pas.';
        }

        // En mode debug, ajouter plus d'informations
        if (config('app.debug')) {
            $response['error'] = $exception->getMessage();
            $response['trace'] = explode("\n", $exception->getTraceAsString());
        }

        return response()->json($response, $status);
    }

    /**
     * Gérer les exceptions pour les requêtes web standards
     */
    private function handleWebException($request, Throwable $exception)
    {
        // Pour les exceptions de validation, utiliser le comportement par défaut de Laravel
        if ($exception instanceof ValidationException) {
            return parent::render($request, $exception);
        }

        // Log de l'erreur
        Log::error('Erreur: '.$exception->getMessage().' dans '.$exception->getFile().' ligne '.$exception->getLine());

        // Pour les erreurs d'autorisation (403)
        if ($exception instanceof AuthorizationException) {
            return response()->view('errors.403', [], 403);
        }

        // Pour les erreurs de session expirée / CSRF (419)
        if ($exception instanceof TokenMismatchException) {
            return response()->view('errors.419', [], 419);
        }

        // Pour les erreurs 404, utiliser une vue personnalisée
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }

        // Pour les erreurs ModelNotFoundException, rediriger avec message
        if ($exception instanceof ModelNotFoundException) {
            return redirect()->back()->with('error', "L'élément demandé est introuvable.");
        }

        // En production, afficher un message générique
        if (! config('app.debug')) {
            return response()->view('errors.500', [], 500);
        }

        // En mode debug, utiliser le comportement par défaut pour voir les détails
        return parent::render($request, $exception);
    }
}
