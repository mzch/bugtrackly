<?php

namespace App\Trait\User;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

trait HasFirstnameAndLastnameTrait
{
    /**
     * Retourne le role de l'utilisateur
     * @return Attribute
     */
    protected function fullName(): Attribute{
        return new Attribute(
            get: fn () => $this->attributes['first_name'] . ' ' . $this->attributes['last_name'],
        );
    }

    protected function initiales(): Attribute{
        return new Attribute(
            get: fn () => $this->generateInitiales($this->fullName),
        );
    }

    private function generateInitiales (string $name):string{
        $initials = collect(explode(' ', $name))->map(function ($part) {
            return Str::upper(Str::substr($part, 0, 1));
        })->implode('');

        return Str::substr($initials, 0, 3);
    }


}
