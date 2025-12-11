<?php

namespace App\Transformers\Api\Client;

use App\Enums\SubuserPermission;
use App\Models\Allocation;
use App\Models\Egg;
use App\Models\EggVariable;
use App\Models\Server;
use App\Models\Subuser;
use App\Services\Servers\StartupCommandService;
use Illuminate\Container\Container;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\NullResource;

class ServerTransformer extends BaseClientTransformer
{
    protected array $defaultIncludes = ['allocations', 'variables'];

    protected array $availableIncludes = ['egg', 'subusers'];

    public function getResourceName(): string
    {
        return Server::RESOURCE_NAME;
    }

    /**
     * @param  Server  $server
     */
    public function transform($server): array
    {
        /** @var StartupCommandService $service */
        $service = Container::getInstance()->make(StartupCommandService::class);

        $user = $this->request->user();

        $data = [
            'server_owner' => $user->id === $server->owner_id,
            'identifier' => $server->uuid_short,
            'internal_id' => $server->id,
            'uuid' => $server->uuid,
            'name' => $server->name,
            'node' => $server->node->name,
            'is_node_under_maintenance' => $server->node->isUnderMaintenance(),
            'sftp_details' => [
                'ip' => $server->node->fqdn,
                'alias' => $server->node->daemon_sftp_alias,
                'port' => $server->node->daemon_sftp,
            ],
            'description' => $server->description,
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
            'invocation' => $service->handle($server, hideAllValues: !$user->can(SubuserPermission::StartupRead, $server)),
            'docker_image' => $server->image,
            'egg_features' => $server->egg->inherit_features,
            'feature_limits' => [
                'databases' => $server->database_limit,
                'allocations' => $server->allocation_limit,
                'backups' => $server->backup_limit,
            ],
            'status' => $server->status,
            // This field is deprecated, please use "status".
            'is_suspended' => $server->isSuspended(),
            // This field is deprecated, please use "status".
            'is_installing' => !$server->isInstalled(),
            'is_transferring' => !is_null($server->transfer),
        ];

        if (!config('panel.editable_server_descriptions')) {
            unset($data['description']);
        }

        return $data;
    }

    /**
     * Returns the allocations associated with this server.
     */
    public function includeAllocations(Server $server): Collection
    {
        $transformer = $this->makeTransformer(AllocationTransformer::class);

        $user = $this->request->user();
        // While we include this permission, we do need to actually handle it slightly different here
        // for the purpose of keeping things functionally working. If the user doesn't have read permissions
        // for the allocations we'll only return the primary server allocation, and any notes associated
        // with it will be hidden.
        //
        // This allows us to avoid too much permission regression, without also hiding information that
        // is generally needed for the frontend to make sense when browsing or searching results.
        if (!$user->can(SubuserPermission::AllocationRead, $server)) {
            $primary = clone $server->allocation;
            $primary->notes = null;

            return $this->collection([$primary], $transformer, Allocation::RESOURCE_NAME);
        }

        return $this->collection($server->allocations, $transformer, Allocation::RESOURCE_NAME);
    }

    public function includeVariables(Server $server): Collection|NullResource
    {
        if (!$this->request->user()->can(SubuserPermission::StartupRead, $server)) {
            return $this->null();
        }

        return $this->collection(
            $server->variables->where('user_viewable', true),
            $this->makeTransformer(EggVariableTransformer::class),
            EggVariable::RESOURCE_NAME
        );
    }

    /**
     * Returns the egg associated with this server.
     */
    public function includeEgg(Server $server): Item
    {
        return $this->item($server->egg, $this->makeTransformer(EggTransformer::class), Egg::RESOURCE_NAME);
    }

    /**
     * Returns the subusers associated with this server.
     */
    public function includeSubusers(Server $server): Collection|NullResource
    {
        if (!$this->request->user()->can(SubuserPermission::UserRead, $server)) {
            return $this->null();
        }

        return $this->collection($server->subusers, $this->makeTransformer(SubuserTransformer::class), Subuser::RESOURCE_NAME);
    }
}
