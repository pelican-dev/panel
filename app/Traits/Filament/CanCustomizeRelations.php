<?php

namespace App\Traits\Filament;

use Filament\Resources\RelationManagers\RelationManager;

trait CanCustomizeRelations
{
    /** @var array<class-string<RelationManager>> */
    protected static array $customRelations = [];

    public static function registerCustomRelations(string ...$customRelations): void
    {
        static::$customRelations = array_merge(static::$customRelations, $customRelations);
    }

    /** @return class-string<RelationManager>[] */
    public static function getDefaultRelations(): array
    {
        return [];
    }

    /** @return class-string<RelationManager>[] */
    public static function getRelations(): array
    {
        return array_unique(array_merge(static::$customRelations, static::getDefaultRelations()));
    }
}
