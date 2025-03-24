<?php

use Carbon\Carbon;


/**
 * Convertit une taille en octets en une représentation lisible pour les humains
 *
 * @param int $bytes Taille en octets
 * @param int $precision Nombre de chiffres après la virgule (par défaut 2)
 * @return string Taille formatée avec unité (KB, MB, GB, etc.)
 */
if (!function_exists('human_filesize')) {
    function human_filesize($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        // Calcul de la taille avec la précision demandée
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
if (!function_exists('formatDate')) {
    /**
     * Retourne une règle de validation pour les heures d'ouverture.
     *
     * @return Closure
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

if (!function_exists('numberToWords')) {
    function numberToWords($number)
    {
        $units = [
            0 => 'zéro', 1 => 'un', 2 => 'deux', 3 => 'trois', 4 => 'quatre', 5 => 'cinq',
            6 => 'six', 7 => 'sept', 8 => 'huit', 9 => 'neuf', 10 => 'dix',
            11 => 'onze', 12 => 'douze', 13 => 'treize', 14 => 'quatorze', 15 => 'quinze',
            16 => 'seize', 17 => 'dix-sept', 18 => 'dix-huit', 19 => 'dix-neuf',
        ];

        $tens = [
            2 => 'vingt', 3 => 'trente', 4 => 'quarante', 5 => 'cinquante',
            6 => 'soixante', 7 => 'soixante-dix', 8 => 'quatre-vingt', 9 => 'quatre-vingt-dix',
        ];

        if ($number < 20) {
            return $units[$number];
        }

        if ($number < 100) {
            $unit = $number % 10;
            $ten = (int) ($number / 10);

            $word = $tens[$ten];
            if ($ten == 7 || $ten == 9) {
                return $tens[$ten - 1].'-'.$units[$unit + 10];
            }

            if ($unit > 0) {
                $word .= '-'.$units[$unit];
            }

            return $word;
        }

        if ($number < 1000) {
            $hundreds = (int) ($number / 100);
            $remainder = $number % 100;

            $word = ($hundreds > 1 ? $units[$hundreds].'-cent' : 'cent');
            if ($remainder > 0) {
                $word .= '-'.numberToWords($remainder);
            }

            return $word;
        }

        return 'Nombre trop grand à convertir';
    }
}

if (!function_exists('calculateWorkingDays')) {
    function calculateWorkingDays($startDate, $endDate)
    {
        $count = 0;
        $currentDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        while ($currentDate->lte($endDate)) {
            ++$count;

            $currentDate->addDay();
        }

        return $count;
    }
}

if (!function_exists('formatDateRange')) {
    /**
     * Convertit une plage de dates au format '2025-01-13 au 2025-02-01' en '13 janvier au 01 février 2025'.
     *
     * @param string $startDate La date de début au format 'Y-m-d'
     * @param string $endDate   La date de fin au format 'Y-m-d'
     *
     * @return string La plage de dates formatée
     */
    function formatDateRange($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Format des dates
        $startFormatted = $start->format('d').' '.$start->locale('fr')->isoFormat('MMMM').' '.$start->format('Y');
        $endFormatted = $end->format('d').' '.$end->locale('fr')->isoFormat('MMMM').' '.$end->format('Y');

        return $startFormatted.' au '.$endFormatted;
    }
}
