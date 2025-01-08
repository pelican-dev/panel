<?php

namespace App\Extensions;

use App\Models\DatabaseHost;

class DynamicDatabaseConnection
{
    public const DB_DEFAULTS = [
        'mysql' => [
            'DB_CHARSET' => 'utf8',
            'DB_COLLATION' => 'utf8_unicode_ci',
            'DEFAULT_DB' => 'mysql',
        ],
        'mariadb' => [
            'DB_CHARSET' => 'utf8',
            'DB_COLLATION' => 'utf8_unicode_ci',
            'DEFAULT_DB' => 'mysql',
        ],
        'pgsql' => [
            'DB_CHARSET' => 'utf8',
            'DB_COLLATION' => 'en_US',
            'DEFAULT_DB' => 'postgres',
        ],
    ];

    /**
     * Adds a dynamic database connection entry to the runtime config.
     */
    public function set(string $connection, DatabaseHost|int $host, string $database = ""): void
    {
        if (!$host instanceof DatabaseHost) {
            $host = DatabaseHost::query()->findOrFail($host);
        }

        config()->set('database.connections.' . $connection, [
            'driver' => $host->driver,
            'host' => $host->host,
            'port' => $host->port,
            'database' => $database !== '' ? self::DB_DEFAULTS[$host->driver]['DEFAULT_DB'] : $database,
            'username' => $host->username,
            'password' => $host->password,
            'charset' => self::DB_DEFAULTS[$host->driver]['DB_CHARSET'],
            'collation' => self::DB_DEFAULTS[$host->driver]['DB_COLLATION'],
        ]);
    }
}
