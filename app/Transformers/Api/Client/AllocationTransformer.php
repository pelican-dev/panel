<?php

namespace App\Transformers\Api\Client;

use App\Models\Allocation;

class AllocationTransformer extends BaseClientTransformer
{
    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return 'allocation';
    }

    /**
     * @param  Allocation  $model
     */
    public function transform($model): array
    {
        return [
            'id' => $model->id,
            'ip' => $model->ip,
            'ip_alias' => $model->ip_alias,
            'port' => $model->port,
            'notes' => $model->notes,
            'is_default' => $model->server->allocation_id === $model->id,
        ];
    }
}
