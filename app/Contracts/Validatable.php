<?php

namespace App\Contracts;

use Illuminate\Validation\Validator;

interface Validatable
{
    public function getValidator(): Validator;

    public static function getRules(): array;

    public static function getRulesForField(string $field): array;

    public function validate(): void;
}
