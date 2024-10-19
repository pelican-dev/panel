<?php

namespace App\Transformers\Api\Application;

use Spatie\Permission\Models\Permission;

class RolePermissionTransformer extends BaseTransformer
{
    public function getResourceName(): string
    {
        return 'permissions';
    }

    public function transform(Permission $model): array
    {
        return [
            'name' => $model->name,
            'created_at' => $model->created_at->toAtomString(),
            'updated_at' => $model->updated_at->toAtomString(),
        ];
    }
}
