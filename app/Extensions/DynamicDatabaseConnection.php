<?php

namespace App\Extensions;

use App\Models\DatabaseHost;

class DynamicDatabaseConnection
{
    public const DB_CHARSET = 'utf8';
    public const DB_COLLATION = 'utf8_unicode_ci';
    public const DB_DRIVER = 'mysql';

    /**
     * Adds a dynamic database connection entry to the runtime config.
     */
    public function set(string $connection, DatabaseHost|int $host, string $database = 'mysql'): void
    {
        if (!$host instanceof DatabaseHost) {
            $host = DatabaseHost::query()->findOrFail($host);
        }

        config()->set('database.connections.' . $connection, [
            'driver' => self::DB_DRIVER,
            'host' => $host->host,
            'port' => $host->port,
            'database' => $database,
            'username' => $host->username,
            'password' => $host->password,
            'charset' => self::DB_CHARSET,
            'collation' => self::DB_COLLATION,
        ]);
    }
}
