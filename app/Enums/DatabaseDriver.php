<?php

namespace App\Enums;

enum DatabaseDriver: string
{
    case Mariadb = 'mariadb';
    case Mysql = 'mysql';
    case Postgresql = 'pgsql';
    case Sqlite = 'sqlite';

    public function getFriendlyName(): string
    {
        return match($this) {
            self::Mariadb => 'MariaDB',
            self::Mysql => 'MySQL',
            self::Postgresql => 'PostgreSQL',
            self::Sqlite => 'SQLite',
        };
    }

    public function getJDBCDriver(): string
    {
        return match($this) {
            self::Mariadb, self::Mysql => 'mysql',
            self::Postgresql => 'postgresql',
            self::Sqlite => 'sqlite',
        };
    }

    public function isRemote(): bool
    {
        return match($this) {
            self::Mariadb, self::Mysql, self::Postgresql => true,
            self::Sqlite => false,
        };
    }

    public function getDefaultOption(string $key, bool $useEnv = false): mixed
    {
        $defaultValue = (match($this) {
            self::Mariadb => [
                'driver' => 'mariadb',
                'url' => '',
                'host' => '127.0.0.1',
                'port' => 3306,
                'database' => 'panel',
                'username' => 'pelican',
                'password' => '',
                'unix_socket' => '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null,
                'options' => [],
                'test_database' => 'mysql',
            ],
            self::Mysql => [
                'driver' => 'mysql',
                'url' => '',
                'host' => '127.0.0.1',
                'port' => 3306,
                'database' => 'panel',
                'username' => 'pelican',
                'password' => '',
                'unix_socket' => '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null,
                'options' => [],
                'test_database' => 'mysql',
            ],
            self::Postgresql => [
                'driver' => 'pgsql',
                'url' => '',
                'host' => '127.0.0.1',
                'port' => 5432,
                'database' => 'panel',
                'username' => 'pelican',
                'password' => '',
                'charset' => 'UTF8',
                'prefix' => '',
                'prefix_indexes' => true,
                'sslmode' => 'prefer',
                'test_database' => 'postgres',
            ],
            self::Sqlite => [
                'driver' => 'sqlite',
                'url' => '',
                'database' => 'database.sqlite',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        })[$key] ?? null;
        return $useEnv ? config()->get(sprintf('database.connections.%s.%s', $this->value, $key), $defaultValue) : $defaultValue;
    }

    /**
     * Returns a string to string mapping of internal driver names to friendly names.
     * The recommended driver, if specified, will have (recommended) added to its name.
     */
    public static function getFriendlyNameArray(self|null $recommended = null): array
    {
        $values = array_map(fn (self $value) => sprintf($value === $recommended ? '%s (recommended)' : '%s', $value->getFriendlyName()), self::cases());
        return array_combine(array_column(self::cases(), 'value'), $values);
    }

    /**
     * Returns a string to string mapping of internal driver names to friendly names.
     * The recommended driver, if specified, will have (recommended) added to its name.
     * Only returns databases connected to remotely. Not SQLite.
     */
    public static function getFriendlyNameArrayRemote(self|null $recommended = null): array
    {
        $remoteDrivers = array_filter(self::cases(), fn (self $value) => $value->isRemote());
        $values = array_map(fn (self $value) => sprintf($value === $recommended ? '%s (recommended)' : '%s', $value->getFriendlyName()), $remoteDrivers);
        return array_combine(array_column($remoteDrivers, 'value'), $values);
    }
}
