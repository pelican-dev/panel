<?php

namespace App\Services\Databases;

use Webmozart\Assert\Assert;
use App\Models\Server;
use App\Models\Database;
use App\Models\DatabaseHost;
use App\Exceptions\Service\Database\NoSuitableDatabaseHostException;

class DeployServerDatabaseService
{
    /**
     * DeployServerDatabaseService constructor.
     */
    public function __construct(private DatabaseManagementService $managementService)
    {
    }

    /**
     * @throws \Throwable
     * @throws \App\Exceptions\Service\Database\TooManyDatabasesException
     * @throws \App\Exceptions\Service\Database\DatabaseClientFeatureNotEnabledException
     */
    public function handle(Server $server, array $data): Database
    {
        Assert::notEmpty($data['database'] ?? null);
        Assert::notEmpty($data['remote'] ?? null);

        $hosts = DatabaseHost::query()->get()->toBase();
        if ($hosts->isEmpty()) {
            throw new NoSuitableDatabaseHostException();
        } else {
            $nodeHosts = $hosts->where('node_id', $server->node_id)->toBase();

            if ($nodeHosts->isEmpty() && !config('panel.client_features.databases.allow_random')) {
                throw new NoSuitableDatabaseHostException();
            }
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
