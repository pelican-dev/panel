<?php

namespace App\Traits\Filament;

use Closure;
use Filament\Tables\Table;

trait CanModifyTable
{
    /** @var array<Closure> */
    protected static array $customTableModifications = [];

    public static function modifyTable(Closure $closure): void
    {
        static::$customTableModifications[] = $closure;
    }

    public static function defaultTable(Table $table): Table
    {
        return $table;
    }

    public static function table(Table $table): Table
    {
        $table = static::defaultTable($table);

        foreach (static::$customTableModifications as $closure) {
            $table = $closure($table);
        }

        return $table;
    }
}
