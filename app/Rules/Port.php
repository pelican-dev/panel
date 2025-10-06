<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class Port implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string):PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_numeric($value)) {
            $fail('The :attribute must be numeric.');
        }

        $value = intval($value);
        if (floatval($value) !== (float) $value) {
            $fail('The :attribute must be an integer.');
        }

        if ($value < 0) {
            $fail('The :attribute must be greater or equal to 0.');
        }

        if ($value > 65535) {
            $fail('The :attribute must be less or equal to 65535.');
        }
    }
}
