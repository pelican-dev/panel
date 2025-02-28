<?php

namespace App\Transformers\Api\Application;

use App\Models\Role;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\NullResource;

class RoleTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'permissions',
    ];

    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return Role::RESOURCE_NAME;
    }

    /**
     * @param  Role  $model
     */
    public function transform($model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'created_at' => $model->created_at->toAtomString(),
            'updated_at' => $model->updated_at->toAtomString(),
        ];
    }

    /**
     * Include the permissions associated with this role.
     */
    public function includePermissions(Role $model): Collection|NullResource
    {
        $model->loadMissing('permissions');

        return $this->collection($model->getRelation('permissions'), $this->makeTransformer(RolePermissionTransformer::class), 'permissions');
    }
}
