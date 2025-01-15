<?php

namespace App\Extensions;

use App\Models\DatabaseHost;

class DynamicDatabaseConnection
{
    /**
     * Adds a dynamic database connection entry to the runtime config.
     */
    public function set(string $connection, DatabaseHost|int $host, string $database = ''): void
    {
        if (!$host instanceof DatabaseHost) {
            $host = DatabaseHost::query()->findOrFail($host);
        }

        config()->set('database.connections.' . $connection, [
            'driver' => $host->driver->value,
            'host' => $host->host,
            'port' => $host->port,
            'database' => $database !== '' ? $database : $host->driver->getDefaultOption('test_database'),
            'username' => $host->username,
            'password' => $host->password,
            'charset' => $host->driver->getDefaultOption('charset'),
            'collation' => $host->driver->getDefaultOption('collation'),
        ]);
    }
}
