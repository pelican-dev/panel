<?php

namespace App\Transformers\Api\Client;

use App\Models\UserSSHKey;

class UserSSHKeyTransformer extends BaseClientTransformer
{
    public function getResourceName(): string
    {
        return UserSSHKey::RESOURCE_NAME;
    }

    /**
     * @param  UserSSHKey  $model
     */
    public function transform($model): array
    {
        return [
            'name' => $model->name,
            'fingerprint' => $model->fingerprint,
            'public_key' => $model->public_key,
            'created_at' => $model->created_at->toAtomString(),
        ];
    }
}
