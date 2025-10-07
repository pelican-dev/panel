<?php

namespace App\Services\Databases;

use App\Exceptions\Service\Database\NoSuitableDatabaseHostException;
use App\Models\Database;
use App\Models\DatabaseHost;
use App\Models\Server;
use Webmozart\Assert\Assert;

readonly class DeployServerDatabaseService
{
    public function __construct(private DatabaseManagementService $managementService) {}

    /**
     * @param  array{database?: string, remote?: string}  $data
     */
    public function handle(Server $server, array $data): Database
    {
        Assert::notEmpty($data['database'] ?? null);
        Assert::notEmpty($data['remote'] ?? null);

        $hosts = DatabaseHost::query()->get();
        if ($hosts->isEmpty()) {
            throw new NoSuitableDatabaseHostException();
        }

        $nodeHosts = $server->node->databaseHosts()->get();
        // TODO: @areyouscared remove allow random feature for database hosts
        if ($nodeHosts->isEmpty() && !config('panel.client_features.databases.allow_random')) {
            throw new NoSuitableDatabaseHostException();
        }

        return $this->managementService->create($server, [
            'database_host_id' => $nodeHosts->isEmpty()
                ? $hosts->random()->id
                : $nodeHosts->random()->id,
            'database' => DatabaseManagementService::generateUniqueDatabaseName($data['database'], $server->id),
            'remote' => $data['remote'],
        ]);
    }
}
