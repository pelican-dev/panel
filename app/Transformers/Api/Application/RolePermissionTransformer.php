<?php

namespace App\Transformers\Api\Application;

use Spatie\Permission\Models\Permission;

class RolePermissionTransformer extends BaseTransformer
{
    public function getResourceName(): string
    {
        return 'permissions';
    }

    /**
     * @param  Permission  $model
     */
    public function transform($model): array
    {
        return [
            'name' => $model->name,
            'created_at' => $model->created_at->toAtomString(),
            'updated_at' => $model->updated_at->toAtomString(),
        ];
    }
}
