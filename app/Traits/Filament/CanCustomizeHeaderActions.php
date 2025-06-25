<?php

namespace App\Traits\Filament;

use App\Enums\HeaderActionPosition;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;

trait CanCustomizeHeaderActions
{
    /** @var array<Action|ActionGroup|CreateAction|DeleteAction> */
    protected static array $customHeaderActions = [];

    public static function registerCustomHeaderActions(HeaderActionPosition $position, Action|ActionGroup ...$customHeaderActions): void
    {
        static::$customHeaderActions[$position->value] = array_merge(static::$customHeaderActions[$position->value] ?? [], $customHeaderActions);
    }

    /** @return array<int,CreateAction> */
    protected function getDefaultHeaderActions(): array
    {
        return [];
    }

    /** @return array<Action|ActionGroup>
     * @throws Exception
     */
    protected function getHeaderActions(): array
    {
        return array_merge(
            static::$customHeaderActions[HeaderActionPosition::Before->value] ?? [],
            $this->getDefaultHeaderActions(),
            static::$customHeaderActions[HeaderActionPosition::After->value] ?? []
        );
    }
}
