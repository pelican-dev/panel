<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DatabaseDriver: string implements HasLabel
{
    case SQLite = 'sqlite';
    case MariaDB = 'mariadb';
    case MySQL = 'mysql';
    case PostgreSQL = 'pgsql';

    public function getLabel(): string
    {
        return match ($this) {
            self::SQLite => 'SQLite',
            self::MariaDB => 'MariaDB',
            self::MySQL => 'MySQL',
            self::PostgreSQL => 'PostgreSQL',
        };
    }

    public function requiredExtension(): string
    {
        return match ($this) {
            self::SQLite => 'pdo_sqlite',
            self::MariaDB, self::MySQL => 'pdo_mysql',
            self::PostgreSQL => 'pdo_pgsql',
        };
    }

    public function defaultPort(): ?int
    {
        return match ($this) {
            self::SQLite => null,
            self::MariaDB, self::MySQL => 3306,
            self::PostgreSQL => 5432,
        };
    }

    /** @return array<string, string> */
    public static function options(bool $recommendSQLite = false): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $driver) => [
                $driver->value => $driver->getLabel()
                    . ($recommendSQLite && $driver === self::SQLite ? ' (recommended)' : ''),
            ])
            ->all();
    }
}
