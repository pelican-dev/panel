<?php

namespace App\Traits\Filament;

use App\Enums\HeaderWidgetPosition;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;

trait CanCustomizeHeaderWidgets
{
    /** @var array<class-string<Widget>|WidgetConfiguration> */
    protected static array $customHeaderWidgets = [];

    public static function registerCustomHeaderWidgets(HeaderWidgetPosition $position, string|WidgetConfiguration ...$customHeaderWidgets): void
    {
        static::$customHeaderWidgets[$position->value] = array_merge(static::$customHeaderWidgets[$position->value] ?? [], $customHeaderWidgets);
    }

    /** @return array<class-string<Widget>|WidgetConfiguration> */
    protected function getDefaultHeaderWidgets(): array
    {
        return [];
    }

    /** @return array<class-string<Widget>|WidgetConfiguration> */
    protected function getHeaderWidgets(): array
    {
        return array_merge(
            static::$customHeaderWidgets[HeaderWidgetPosition::Before->value] ?? [],
            $this->getDefaultHeaderWidgets(),
            static::$customHeaderWidgets[HeaderWidgetPosition::After->value] ?? []
        );
    }
}
