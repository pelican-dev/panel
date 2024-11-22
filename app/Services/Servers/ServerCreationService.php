<?php

namespace App\Services\Servers;

use App\Enums\ServerState;
use App\Models\ServerVariable;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Arr;
use App\Models\User;
use Webmozart\Assert\Assert;
use App\Models\Server;
use Illuminate\Support\Collection;
use App\Models\Allocation;
use Illuminate\Database\ConnectionInterface;
use App\Models\Objects\DeploymentObject;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Services\Deployment\FindViableNodesService;
use App\Services\Deployment\AllocationSelectionService;
use App\Exceptions\Http\Connection\DaemonConnectionException;
use App\Models\Egg;

class ServerCreationService
{
    /**
     * ServerCreationService constructor.
     */
    public function __construct(
        private AllocationSelectionService $allocationSelectionService,
        private ConnectionInterface $connection,
        private DaemonServerRepository $daemonServerRepository,
        private FindViableNodesService $findViableNodesService,
        private ServerDeletionService $serverDeletionService,
        private VariableValidatorService $validatorService
    ) {}

    /**
     * Create a server on the Panel and trigger a request to the Daemon to begin the server
     * creation process. This function will attempt to set as many additional values
     * as possible given the input data. For example, if an allocation_id is passed with
     * no node_id the node_is will be picked from the allocation.
     *
     * @throws \Throwable
     * @throws \App\Exceptions\DisplayException
     * @throws \Illuminate\Validation\ValidationException
     * @throws \App\Exceptions\Service\Deployment\NoViableAllocationException
     */
    public function handle(array $data, ?DeploymentObject $deployment = null): Server
    {
        if (!isset($data['oom_killer']) && isset($data['oom_disabled'])) {
            $data['oom_killer'] = !$data['oom_disabled'];
        }

        /** @var Egg $egg */
        $egg = Egg::query()->findOrFail($data['egg_id']);

        // Fill missing fields from egg
        $data['image'] = $data['image'] ?? collect($egg->docker_images)->first();
        $data['startup'] = $data['startup'] ?? $egg->startup;

        // If a deployment object has been passed we need to get the allocation
        // that the server should use, and assign the node from that allocation.
        if ($deployment instanceof DeploymentObject) {
            $allocation = $this->configureDeployment($data, $deployment);
            $data['allocation_id'] = $allocation->id;
            $data['node_id'] = $allocation->node_id;
        }

        // Auto-configure the node based on the selected allocation
        // if no node was defined.
        if (empty($data['node_id'])) {
            Assert::false(empty($data['allocation_id']), 'Expected a non-empty allocation_id in server creation data.');

            $data['node_id'] = Allocation::query()->findOrFail($data['allocation_id'])->node_id;
        }

        $eggVariableData = $this->validatorService
            ->setUserLevel(User::USER_LEVEL_ADMIN)
            ->handle(Arr::get($data, 'egg_id'), Arr::get($data, 'environment', []));

        // Due to the design of the Daemon, we need to persist this server to the disk
        // before we can actually create it on the Daemon.
        //
        // If that connection fails out we will attempt to perform a cleanup by just
        // deleting the server itself from the system.
        /** @var \App\Models\Server $server */
        $server = $this->connection->transaction(function () use ($data, $eggVariableData) {
            // Create the server and assign any additional allocations to it.
            $server = $this->createModel($data);

            $this->storeAssignedAllocations($server, $data);
            $this->storeEggVariables($server, $eggVariableData);

            return $server;
        }, 5);

        try {
            $this->daemonServerRepository->setServer($server)->create(
                Arr::get($data, 'start_on_completion', false) ?? false
            );
        } catch (DaemonConnectionException $exception) {
            $this->serverDeletionService->withForce()->handle($server);

            throw $exception;
        }

        return $server;
    }

    /**
     * Gets an allocation to use for automatic deployment.
     *
     * @throws \App\Exceptions\DisplayException
     * @throws \App\Exceptions\Service\Deployment\NoViableAllocationException
     */
    private function configureDeployment(array $data, DeploymentObject $deployment): Allocation
    {
        $nodes = $this->findViableNodesService->handle(
            Arr::get($data, 'memory', 0),
            Arr::get($data, 'disk', 0),
            Arr::get($data, 'cpu', 0),
            Arr::get($data, 'tags', []),
        );

        return $this->allocationSelectionService->setDedicated($deployment->isDedicated())
            ->setNodes($nodes->pluck('id')->toArray())
            ->setPorts($deployment->getPorts())
            ->handle();
    }

    /**
     * Store the server in the database and return the model.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    private function createModel(array $data): Server
    {
        $uuid = $this->generateUniqueUuidCombo();

        return Server::create([
            'external_id' => Arr::get($data, 'external_id'),
            'uuid' => $uuid,
            'uuid_short' => substr($uuid, 0, 8),
            'node_id' => Arr::get($data, 'node_id'),
            'name' => Arr::get($data, 'name'),
            'description' => Arr::get($data, 'description') ?? '',
            'status' => ServerState::Installing,
            'skip_scripts' => Arr::get($data, 'skip_scripts') ?? isset($data['skip_scripts']),
            'owner_id' => Arr::get($data, 'owner_id'),
            'memory' => Arr::get($data, 'memory'),
            'swap' => Arr::get($data, 'swap'),
            'disk' => Arr::get($data, 'disk'),
            'io' => Arr::get($data, 'io'),
            'cpu' => Arr::get($data, 'cpu'),
            'threads' => Arr::get($data, 'threads'),
            'oom_killer' => Arr::get($data, 'oom_killer') ?? false,
            'allocation_id' => Arr::get($data, 'allocation_id'),
            'egg_id' => Arr::get($data, 'egg_id'),
            'startup' => Arr::get($data, 'startup'),
            'image' => Arr::get($data, 'image'),
            'database_limit' => Arr::get($data, 'database_limit') ?? 0,
            'allocation_limit' => Arr::get($data, 'allocation_limit') ?? 0,
            'backup_limit' => Arr::get($data, 'backup_limit') ?? 0,
            'docker_labels' => Arr::get($data, 'docker_labels'),
        ]);
    }

    /**
     * Configure the allocations assigned to this server.
     */
    private function storeAssignedAllocations(Server $server, array $data): void
    {
        $records = [$data['allocation_id']];
        if (isset($data['allocation_additional']) && is_array($data['allocation_additional'])) {
            $records = array_merge($records, $data['allocation_additional']);
        }

        Allocation::query()->whereIn('id', $records)->update([
            'server_id' => $server->id,
        ]);
    }

    /**
     * Process environment variables passed for this server and store them in the database.
     */
    private function storeEggVariables(Server $server, Collection $variables): void
    {
        $now = now();

        $records = $variables->map(function ($result) use ($server, $now) {
            return [
                'server_id' => $server->id,
                'variable_id' => $result->id,
                'variable_value' => $result->value ?? '',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->toArray();

        if (!empty($records)) {
            ServerVariable::query()->insert($records);
        }
    }

    /**
     * Create a unique UUID and UUID-Short combo for a server.
     */
    private function generateUniqueUuidCombo(): string
    {
        $uuid = Uuid::uuid4()->toString();

        $shortUuid = str($uuid)->substr(0, 8);
        if (Server::query()->where('uuid', $uuid)->orWhere('uuid_short', $shortUuid)->exists()) {
            return $this->generateUniqueUuidCombo();
        }

        return $uuid;
    }
}
