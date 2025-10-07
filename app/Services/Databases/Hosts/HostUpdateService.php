<?php

namespace App\Services\Databases\Hosts;

use App\Models\DatabaseHost;
use Illuminate\Database\ConnectionInterface;
use Throwable;

class HostUpdateService
{
    /**
     * HostUpdateService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
    ) {}

    /**
     * Update a database host and persist to the database.
     *
     * @param  array<mixed>  $data
     *
     * @throws Throwable
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

            // Confirm access using the provided credentials before saving data.
            $host->buildConnection()->getPdo();

            return $host;
        });
    }
}
