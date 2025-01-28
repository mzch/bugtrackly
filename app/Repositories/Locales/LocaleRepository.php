<?php

namespace App\Repositories\Locales;

use App\Repositories\Locales\LocaleRepositoryInterface;
use Illuminate\Support\Collection;

class LocaleRepository implements LocaleRepositoryInterface
{

    public function getAllLocales(): Collection
    {
        return collect([
            'en' => 'English',
            'fr' => 'Français',
        ]);
    }
}
