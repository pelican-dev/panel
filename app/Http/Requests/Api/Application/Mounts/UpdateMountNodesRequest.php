<?php

namespace App\Http\Requests\Api\Application\Mounts;

class UpdateMountNodesRequest extends StoreMountRequest
{
    /**
     * @param  array<string, string|string[]>|null  $rules
     * @return array<string, string|string[]>
     */
    public function rules(?array $rules = null): array
    {
        return [
            'nodes' => 'required|array|exists:nodes,id',
            'nodes.*' => 'integer',
        ];
    }
}
