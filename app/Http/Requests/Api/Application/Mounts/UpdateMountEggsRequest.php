<?php

namespace App\Http\Requests\Api\Application\Mounts;

class UpdateMountEggsRequest extends StoreMountRequest
{
    /**
     * @param  array<string, string|string[]>|null  $rules
     * @return array<string, string|string[]>
     */
    public function rules(?array $rules = null): array
    {
        return [
            'eggs' => 'required|array|exists:eggs,id',
            'eggs.*' => 'integer',
        ];
    }
}
