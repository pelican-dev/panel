<?php

namespace App\Services\Databases\Hosts;

use App\Models\DatabaseHost;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\ConnectionInterface;
use App\Extensions\DynamicDatabaseConnection;

class HostCreationService
{
    /**
     * HostCreationService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
        private DatabaseManager $databaseManager,
        private DynamicDatabaseConnection $dynamic,
    ) {}

    /**
     * Create a new database host on the Panel.
     *
     * @throws \Throwable
     */
    public function handle(array $data): DatabaseHost
    {
        return $this->connection->transaction(function () use ($data) {
            $host = DatabaseHost::query()->create([
                'password' => array_get($data, 'password'),
                'name' => array_get($data, 'name'),
                'host' => array_get($data, 'host'),
                'port' => array_get($data, 'port'),
                'username' => array_get($data, 'username'),
                'max_databases' => array_get($data, 'max_databases'),
            ]);

            $host->nodes()->sync(array_get($data, 'node_ids', []));

            // Confirm access using the provided credentials before saving data.
            $this->dynamic->set('dynamic', $host);
            $this->databaseManager->connection('dynamic')->getPdo();

            return $host;
        });
    }
}
