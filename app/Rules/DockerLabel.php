<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DockerLabel implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach (array_keys($value) as $key) {
            // Docker labels are validated via https://regex101.com/r/FiYrwo/1 following Docker key format
            // recommendations: https://docs.docker.com/engine/manage-resources/labels/
            if (!preg_match('/^(?!com\.docker\.|io\.docker\.|org\.dockerproject\.)(?=.*[a-z]$)[a-z](?:[a-z0-9]|(?<!\.)\.(?!\.)|(?<!-)-(?!-))*$/', $key)) {
                $fail("{$attribute} contains an invalid label: {$key}");

                return;
            }
        }
    }
}
