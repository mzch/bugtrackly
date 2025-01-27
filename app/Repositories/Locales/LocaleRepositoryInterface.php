<?php

namespace App\Repositories\Locales;

use Illuminate\Support\Collection;

interface LocaleRepositoryInterface
{
    public function getAllLocales():Collection;
}
