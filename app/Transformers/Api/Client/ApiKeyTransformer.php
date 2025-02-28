<?php

namespace App\Transformers\Api\Client;

use App\Models\ApiKey;

class ApiKeyTransformer extends BaseClientTransformer
{
    /**
     * {@inheritdoc}
     */
    public function getResourceName(): string
    {
        return ApiKey::RESOURCE_NAME;
    }

    /**
     * @param  ApiKey  $model
     */
    public function transform($model): array
    {
        return [
            'identifier' => $model->identifier,
            'description' => $model->memo,
            'allowed_ips' => $model->allowed_ips,
            'last_used_at' => $model->last_used_at ? $model->last_used_at->toAtomString() : null,
            'created_at' => $model->created_at->toAtomString(),
        ];
    }
}
