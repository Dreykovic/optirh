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



if (!function_exists('getFileIconClass')) {
    /**
     * Returns the CSS class for the background color of the file type.
     */
    function getFileIconClass(string $mimeType): string
    {
        return match ($mimeType) {
            'application/pdf' => 'bg-lightgreen',
            'image/png', 'image/jpeg', 'image/jpg' => 'light-danger-bg',
            default => 'light-danger-bg',
        };
    }
}

if (!function_exists('getFileIcon')) {
    /**
     * Returns the icon class for the file type.
     */
    function getFileIcon(string $mimeType): string
    {
        return match ($mimeType) {
            'application/pdf' => 'icofont-file-pdf',
            'image/png', 'image/jpeg', 'image/jpg' => 'icofont-image',
            default => 'icofont-bug',
        };
    }
}
