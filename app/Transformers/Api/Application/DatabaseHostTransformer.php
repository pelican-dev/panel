<?php

namespace App\Transformers\Api\Application;

use App\Models\Database;
use App\Models\DatabaseHost;
use App\Models\Node;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\NullResource;

class DatabaseHostTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'databases',
        'nodes',
    ];

    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return DatabaseHost::RESOURCE_NAME;
    }

    /**
     * @param  DatabaseHost  $model
     */
    public function transform($model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'host' => $model->host,
            'port' => $model->port,
            'username' => $model->username,
            'created_at' => $model->created_at->toAtomString(),
            'updated_at' => $model->updated_at->toAtomString(),
        ];
    }

    /**
     * Include the databases associated with this host.
     */
    public function includeDatabases(DatabaseHost $model): Collection|NullResource
    {
        if (!$this->authorize(Database::RESOURCE_NAME)) {
            return $this->null();
        }

        $model->loadMissing('databases');

        return $this->collection($model->getRelation('databases'), $this->makeTransformer(ServerDatabaseTransformer::class), Database::RESOURCE_NAME);
    }

    /**
     * Include the nodes associated with this host.
     */
    public function includeNodes(DatabaseHost $model): Collection|NullResource
    {
        if (!$this->authorize(Node::RESOURCE_NAME)) {
            return $this->null();
        }

        $model->loadMissing('nodes');

        return $this->collection($model->getRelation('nodes'), $this->makeTransformer(NodeTransformer::class), Node::RESOURCE_NAME);
    }
}
