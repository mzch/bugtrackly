<?php

namespace App\Rules;

use App\Models\TicketCategory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCategory implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Accepter null et 0 directement
        if ($value==='none' || $value === -1) {
            return;
        }

        if (!TicketCategory::where('id', $value)->exists()) {
            $fail("La catégorie sélectionnée est invalide.");
        }
    }
}
