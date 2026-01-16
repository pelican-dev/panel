<?php

namespace App\Traits\Filament;

use App\Enums\TabPosition;
use Filament\Schemas\Components\Tabs\Tab;

trait CanCustomizeStaticTabs
{
    /** @var array<string, Tab[]> */
    protected static array $customTabs = [];

    public static function registerCustomTabs(TabPosition $position, Tab ...$customTabs): void
    {
        static::$customTabs[$position->value] = array_merge(static::$customTabs[$position->value] ?? [], $customTabs);
    }

    /** @return Tab[] */
    protected static function getDefaultTabs(): array
    {
        return [];
    }

    /** @return Tab[] */
    protected static function getTabs(): array
    {
        return array_merge(
            static::$customTabs[TabPosition::Before->value] ?? [],
            static::getDefaultTabs(),
            static::$customTabs[TabPosition::After->value] ?? []
        );
    }
}
