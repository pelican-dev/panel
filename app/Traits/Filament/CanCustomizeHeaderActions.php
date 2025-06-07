<?php

namespace App\Traits\Filament;

use App\Enums\HeaderActionPosition;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;

trait CanCustomizeHeaderActions
{
    /** @var array<string, Action|ActionGroup> */
    protected static array $customHeaderActions = [];

    public static function registerCustomHeaderActions(HeaderActionPosition $position, Action|ActionGroup ...$customHeaderActions): void
    {
        static::$customHeaderActions[$position->value] = array_merge(static::$customHeaderActions[$position->value] ?? [], $customHeaderActions);
    }

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [];
    }

    /** @return array<Action|ActionGroup> */
    protected function getHeaderActions(): array
    {
        return array_merge(
            static::$customHeaderActions[HeaderActionPosition::Before->value] ?? [],
            $this->getDefaultHeaderActions(),
            static::$customHeaderActions[HeaderActionPosition::After->value] ?? []
        );
    }
}
