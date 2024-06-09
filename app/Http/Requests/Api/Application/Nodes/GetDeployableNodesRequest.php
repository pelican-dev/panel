<?php

namespace App\Http\Requests\Api\Application\Nodes;

class GetDeployableNodesRequest extends GetNodesRequest
{
    public function rules(): array
    {
        return [
            'page' => 'integer',
            'memory' => 'required|integer|min:0',
            'disk' => 'required|integer|min:0',
            'cpu' => 'sometimes|integer|min:0',
            'tags' => 'sometimes|array',

            /** @deprecated use tags instead */
            'location_ids' => 'sometimes|array',
        ];
    }
}
