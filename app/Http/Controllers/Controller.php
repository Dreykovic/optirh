<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use App\Services\ActivityLogger;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
    /**
     * Le service de journalisation des activités
     *
     * @var ActivityLogger
     */
    protected $activityLogger;

    public function __construct(ActivityLogger $activityLogger)
    {
        $this->activityLogger = $activityLogger;
        App::setLocale('fr');
    }

    public function formatMontant($text)
    {
        $text = trim($text);
        $text = strrev($text);
        $length = strlen($text);
        $newText = '';

        for ($i = 0; $i < $length; ++$i) {
            if (($i + 1) % 3 === 1 && $i != 1) {
                $newText .= ' ';
            }
            $newText .= $text[$i];
        }

        $newText = strrev($newText);

        return $newText . ' FCFA';
    }

    public function isAdult($birthdate)
    {
        // Récupération de la date de naissance de l'utilisateur
        // $birthday = Carbon::createFromFormat('Y-m-d', );
        $birthday = Carbon::parse($birthdate);

        // Création de la date actuelle
        $currentDate = Carbon::now();

        // Calcul de l'âge de l'utilisateur
        $age = $birthday->diffInYears($currentDate);

        // Vérification si l'utilisateur a 18 ans ou plus
        return $age >= 18;
    }

    /*
     * Réponse JSON en cas de succès.
     */
    public function successResponse($message, $data = [])
    {
        return response()->json([
            'ok' => true,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Réponse JSON en cas d'erreur de validation.
     */
    public function validationErrorResponse(ValidationException $e)
    {
        return response()->json([
            'ok' => false,
            'message' => 'Les données fournies sont invalides.',
            'errors' => $e->errors(),
        ], 422);
    }

    /**
     * Réponse JSON en cas de données non trouvées.
     */
    public function notFoundResponse()
    {
        return response()->json([
            'ok' => false,
            'message' => 'Données introuvables. Veuillez vérifier les entrées.',
        ], 404);
    }

    /**
     * Réponse JSON en cas d'erreur générale.
     */
    public function generalErrorResponse(\Throwable $th)
    {
        return response()->json([
            'ok' => false,
            'message' => 'Une erreur s’est produite. Veuillez réessayer.',
            'error' => $th->getMessage(),
        ], 500);
    }

}
