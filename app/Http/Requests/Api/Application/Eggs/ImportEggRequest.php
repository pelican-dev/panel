<?php

namespace App\Http\Requests\Api\Application\Eggs;

class ImportEggRequest extends PostEggRequest
{
    public function rules(): array
    {
        return [
            'format' => 'nullable|string|in:yaml,json',
        ];
    }
}
