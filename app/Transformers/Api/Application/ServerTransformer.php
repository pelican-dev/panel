<?php

namespace App\Transformers\Api\Application;

use App\Models\Server;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\NullResource;
use App\Services\Acl\Api\AdminAcl;
use App\Services\Servers\EnvironmentService;

class ServerTransformer extends BaseTransformer
{
    private EnvironmentService $environmentService;

    /**
     * List of resources that can be included.
     */
    protected array $availableIncludes = [
        'allocations',
        'user',
        'subusers',
        'egg',
        'variables',
        'node',
        'databases',
        'transfer',
    ];

    /**
     * Perform dependency injection.
     */
    public function handle(EnvironmentService $environmentService)
    {
        $this->environmentService = $environmentService;
    }

    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return Server::RESOURCE_NAME;
    }

    /**
     * Return a generic transformed server array.
     */
    public function transform(Server $server): array
    {
        return [
            'id' => $server->getKey(),
            'external_id' => $server->external_id,
            'uuid' => $server->uuid,
            'identifier' => $server->uuid_short,
            'name' => $server->name,
            'description' => $server->description,
            'status' => $server->status,
            // This field is deprecated, please use "status".
            'suspended' => $server->isSuspended(),
            'limits' => [
                'memory' => $server->memory,
                'swap' => $server->swap,
                'disk' => $server->disk,
                'io' => $server->io,
                'cpu' => $server->cpu,
                'threads' => $server->threads,
                // This field is deprecated, please use "oom_killer".
                'oom_disabled' => !$server->oom_killer,
                'oom_killer' => $server->oom_killer,
            ],
            'feature_limits' => [
                'databases' => $server->database_limit,
                'allocations' => $server->allocation_limit,
                'backups' => $server->backup_limit,
            ],
            'user' => $server->owner_id,
            'node' => $server->node_id,
            'allocation' => $server->allocation_id,
            'egg' => $server->egg_id,
            'container' => [
                'startup_command' => $server->startup,
                'image' => $server->image,
                // This field is deprecated, please use "status".
                'installed' => $server->isInstalled() ? 1 : 0,
                'environment' => $this->environmentService->handle($server),
            ],
            $server->getUpdatedAtColumn() => $this->formatTimestamp($server->updated_at),
            $server->getCreatedAtColumn() => $this->formatTimestamp($server->created_at),
        ];
    }

    /**
     * Return a generic array of allocations for this server.
     *
     * @throws \App\Exceptions\Transformer\InvalidTransformerLevelException
     */
    public function includeAllocations(Server $server): Collection|NullResource
    {
        if (!$this->authorize(AdminAcl::RESOURCE_ALLOCATIONS)) {
            return $this->null();
        }

        $server->loadMissing('allocations');

        return $this->collection($server->getRelation('allocations'), $this->makeTransformer(AllocationTransformer::class), 'allocation');
    }

    /**
     * Return a generic array of data about subusers for this server.
     *
     * @throws \App\Exceptions\Transformer\InvalidTransformerLevelException
     */
    public function includeSubusers(Server $server): Collection|NullResource
    {
        if (!$this->authorize(AdminAcl::RESOURCE_USERS)) {
            return $this->null();
        }

        $server->loadMissing('subusers');

        return $this->collection($server->getRelation('subusers'), $this->makeTransformer(SubuserTransformer::class), 'subuser');
    }

    /**
     * Return a generic array of data about subusers for this server.
     *
     * @throws \App\Exceptions\Transformer\InvalidTransformerLevelException
     */
    public function includeUser(Server $server): Item|NullResource
    {
        if (!$this->authorize(AdminAcl::RESOURCE_USERS)) {
            return $this->null();
        }

        $server->loadMissing('user');

        return $this->item($server->getRelation('user'), $this->makeTransformer(UserTransformer::class), 'user');
    }

    /**
     * Return a generic array with egg information for this server.
     *
     * @throws \App\Exceptions\Transformer\InvalidTransformerLevelException
     */
    public function includeEgg(Server $server): Item|NullResource
    {
        if (!$this->authorize(AdminAcl::RESOURCE_EGGS)) {
            return $this->null();
        }

        $server->loadMissing('egg');

        return $this->item($server->getRelation('egg'), $this->makeTransformer(EggTransformer::class), 'egg');
    }

    /**
     * Return a generic array of data about subusers for this server.
     *
     * @throws \App\Exceptions\Transformer\InvalidTransformerLevelException
     */
    public function includeVariables(Server $server): Collection|NullResource
    {
        if (!$this->authorize(AdminAcl::RESOURCE_SERVERS)) {
            return $this->null();
        }

        $server->loadMissing('variables');

        return $this->collection($server->getRelation('variables'), $this->makeTransformer(ServerVariableTransformer::class), 'server_variable');
    }

    /**
     * Return a generic array with node information for this server.
     *
     * @throws \App\Exceptions\Transformer\InvalidTransformerLevelException
     */
    public function includeNode(Server $server): Item|NullResource
    {
        if (!$this->authorize(AdminAcl::RESOURCE_NODES)) {
            return $this->null();
        }

        $server->loadMissing('node');

        return $this->item($server->getRelation('node'), $this->makeTransformer(NodeTransformer::class), 'node');
    }

    /**
     * Return a generic array with database information for this server.
     *
     * @throws \App\Exceptions\Transformer\InvalidTransformerLevelException
     */
    public function includeDatabases(Server $server): Collection|NullResource
    {
        if (!$this->authorize(AdminAcl::RESOURCE_SERVER_DATABASES)) {
            return $this->null();
        }

        $server->loadMissing('databases');

        return $this->collection($server->getRelation('databases'), $this->makeTransformer(ServerDatabaseTransformer::class), 'databases');
    }
}
