<?php

namespace App\Services\Databases\Hosts;

use App\Models\DatabaseHost;
use Illuminate\Database\ConnectionInterface;
use Throwable;

class HostCreationService
{
    /**
     * HostCreationService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
    ) {}

    /**
     * Create a new database host on the Panel.
     *
     * @param array{
     *     password: string,
     *     name: string,
     *     host: string,
     *     port: int,
     *     username: string,
     *     max_databases: int,
     *     node_ids?: array<int>
     * } $data
     *
     * @throws Throwable
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
            $host->buildConnection()->getPdo();

            return $host;
        });
    }
}
