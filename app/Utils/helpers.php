<?php

use Carbon\Carbon;

if (!function_exists('formatDate')) {
    /**
     * Retourne une règle de validation pour les heures d'ouverture.
     *
     * @return \Closure
     */
    function formatDate($datetime)
    {
        return Carbon::parse($datetime)->format('Y-m-d');
    }
}
