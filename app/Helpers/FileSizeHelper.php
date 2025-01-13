<?php

namespace App\Helpers;

class FileSizeHelper
{
    public static function formatFileSize($bytes, $useBase1000 = false)
    {
        $base = $useBase1000 ? 1000 : 1024;
        $units = $useBase1000
            ? ['octets', 'ko', 'Mo', 'Go', 'To']
            : ['octets', 'KiB', 'MiB', 'GiB', 'TiB'];

        // Calculer l'unité
        $power = $bytes > 0 ? floor(log($bytes, $base)) : 0;

        // Appliquer la précision selon l'unité
        switch ($power) {
            case 0: // octets
            case 1: // Ko
                // Pas de décimale pour octets et Ko
                return number_format($bytes / pow($base, $power), 0, ',', ' ') . ' ' . $units[$power];
            case 2: // Mo
                // 1 chiffre après la virgule pour Mo
                return number_format($bytes / pow($base, $power), 1, ',', ' ') . ' ' . $units[$power];
            case 3: // Go
                // 2 chiffres après la virgule pour Go
                return number_format($bytes / pow($base, $power), 2, ',', ' ') . ' ' . $units[$power];
            default:
                // Pour les autres unités, pas de décimale
                return number_format($bytes / pow($base, $power), 0, ',', ' ') . ' ' . $units[$power];
        }

    }


}
