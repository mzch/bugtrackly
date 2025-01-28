<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class TranslationHelper
{
    public static function getTranslations($locale)
    {
        $path = lang_path("{$locale}/bugtrackly.php");
        if (File::exists($path)) {
            return require $path;
        }

        // Fallback to English if language file not found
        return require resource_path("lang/en/bugtrackly.php");
    }
}
