<?php

namespace App\Http\Requests\Api\Application\Mounts;

class UpdateMountServersRequest extends StoreMountRequest
{
    /**
     * @param  array<string, string|string[]>|null  $rules
     * @return array<string, string|string[]>
     */
    public function rules(?array $rules = null): array
    {
        return [
            'servers' => 'required|array|exists:servers,id',
            'servers.*' => 'integer',
        ];
    }
}
