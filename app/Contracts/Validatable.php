<?php

namespace App\Contracts;

use Illuminate\Validation\Validator;

interface Validatable
{
    public function getValidator(): Validator;

    /**
     * @return array<string, mixed>
     */
    public static function getRules(): array;

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function getRulesForField(string $field): array;

    public function validate(): void;
}
