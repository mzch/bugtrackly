<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class ValideContentIfAsFileRule implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $files = $this->data["files"];
        if(is_array($files) && count($files) > 0 && blank($value)) {
            $msg = count($files) > 1 ? "des fichiers joints." : "du fichier joint.";
            $fail('Veuillez écrire une note à propos ' . $msg);
        }
    }

    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }
}
