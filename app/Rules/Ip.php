<?php

namespace App\Rules;

use App\Services\Allocations\AssignmentService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Ip implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match(AssignmentService::IP_REGEX, $value)) {
            $fail('The :attribute field must be a valid IP Address');
        }
    }
}
