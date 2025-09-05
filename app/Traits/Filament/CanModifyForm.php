<?php

namespace App\Traits\Filament;

use Closure;
use Filament\Schemas\Schema;

trait CanModifyForm
{
    /** @var array<Closure> */
    protected static array $customFormModifications = [];

    public static function modifyForm(Closure $closure): void
    {
        static::$customFormModifications[] = $closure;
    }

    public static function defaultForm(Schema $schema): Schema
    {
        return $schema;
    }

    public static function form(Schema $schema): Schema
    {
        $schema = static::defaultForm($schema);

        foreach (static::$customFormModifications as $closure) {
            $schema = $closure($schema);
        }

        return $schema;
    }
}
