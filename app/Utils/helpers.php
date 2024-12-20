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

if (!function_exists('generateColor')) {
    function generateColor($name)
    {
        $hash = md5($name);
        return '#' . substr($hash, 0, 6);
    }
}
