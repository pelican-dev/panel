<?php

namespace App\Services\Databases\Hosts;

use App\Models\DatabaseHost;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\ConnectionInterface;
use App\Extensions\DynamicDatabaseConnection;

class HostUpdateService
{
    /**
     * HostUpdateService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
        private DatabaseManager $databaseManager,
        private DynamicDatabaseConnection $dynamic,
    ) {
    }

    /**
     * Update a database host and persist to the database.
     *
     * @throws \Throwable
     */
    public function handle(DatabaseHost|int $host, array $data): DatabaseHost
    {
        if (!$host instanceof DatabaseHost) {
            $host = DatabaseHost::query()->findOrFail($host);
        }

        if (empty(array_get($data, 'password'))) {
            unset($data['password']);
        }

        return $this->connection->transaction(function () use ($data, $host) {
            $host->update($data);

            $this->dynamic->set('dynamic', $host);
            $this->databaseManager->connection('dynamic')->getPdo();

            return $host;
        });
    }
}
