<?php

namespace App\Rules;

use App\Models\Objects\Endpoint;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Port implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Allow port to be optional
        if (empty($value)) {
            return;
        }

        // Require port to be a number
        if (!is_numeric($value)) {
            $fail('The :attribute must be numeric.');
        }

        // Require port to be an integer
        $value = intval($value);
        if (floatval($value) !== (float) $value) {
            $fail('The :attribute must be an integer.');
        }

        // Require minimum valid port
        if ($value <= Endpoint::PORT_FLOOR) {
            $fail('The :attribute must be greater than 1024.');
        }

        // Require maximum valid port
        if ($value > Endpoint::PORT_CEIL) {
            $fail('The :attribute must be less than 65535.');
        }
    }
}
