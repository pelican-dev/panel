<?php

namespace App\Transformers\Api\Application;

use App\Models\Mount;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\NullResource;
use App\Services\Acl\Api\AdminAcl;

class MountTransformer extends BaseTransformer
{
    /**
     * List of resources that can be included.
     */
    protected array $availableIncludes = ['eggs', 'nodes', 'servers'];

    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return Mount::RESOURCE_NAME;
    }

    public function transform(Mount $model)
    {
        return $model->toArray();
    }

    /**
     * Return the eggs associated with this mount.
     *
     * @throws \App\Exceptions\Transformer\InvalidTransformerLevelException
     */
    public function includeEggs(Mount $mount): Collection|NullResource
    {
        if (!$this->authorize(AdminAcl::RESOURCE_EGGS)) {
            return $this->null();
        }

        $mount->loadMissing('eggs');

        return $this->collection(
            $mount->getRelation('eggs'),
            $this->makeTransformer(EggTransformer::class),
            'egg'
        );
    }

    /**
     * Return the nodes associated with this mount.
     *
     * @throws \App\Exceptions\Transformer\InvalidTransformerLevelException
     */
    public function includeNodes(Mount $mount): Collection|NullResource
    {
        if (!$this->authorize(AdminAcl::RESOURCE_NODES)) {
            return $this->null();
        }

        $mount->loadMissing('nodes');

        return $this->collection(
            $mount->getRelation('nodes'),
            $this->makeTransformer(NodeTransformer::class),
            'node'
        );
    }

    /**
     * Return the servers associated with this mount.
     *
     * @throws \App\Exceptions\Transformer\InvalidTransformerLevelException
     */
    public function includeServers(Mount $mount): Collection|NullResource
    {
        if (!$this->authorize(AdminAcl::RESOURCE_SERVERS)) {
            return $this->null();
        }

        $mount->loadMissing('servers');

        return $this->collection(
            $mount->getRelation('servers'),
            $this->makeTransformer(ServerTransformer::class),
            'server'
        );
    }
}
