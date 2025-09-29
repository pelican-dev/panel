<?php

namespace App\Http\Requests\Api\Application\Eggs;

class ExportEggRequest extends GetEggRequest
{
    public function rules(): array
    {
        return [
            'format' => 'nullable|string|in:yaml,json',
        ];
    }
}
