<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Port implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
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

        if ($value <= 1024) {
            $fail('The :attribute must be greater than 1024.');
        }

        if ($value > 65535) {
            $fail('The :attribute must be less than 65535.');
        }
    }
}
