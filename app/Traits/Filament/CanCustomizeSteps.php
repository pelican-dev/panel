<?php

namespace App\Traits\Filament;

use App\Enums\StepPosition;
use Filament\Schemas\Components\Wizard\Step;

trait CanCustomizeSteps
{
    /** @var Step[] */
    protected static array $customSteps = [];

    public static function registerCustomSteps(StepPosition $position, Step ...$customSteps): void
    {
        static::$customSteps[$position->value] = array_merge(static::$customSteps[$position->value] ?? [], $customSteps);
    }

    /** @return Step[] */
    protected function getDefaultSteps(): array
    {
        return [];
    }

    /** @return Step[] */
    protected function getSteps(): array
    {
        return array_merge(
            static::$customSteps[StepPosition::Before->value] ?? [],
            $this->getDefaultSteps(),
            static::$customSteps[StepPosition::After->value] ?? []
        );
    }
}
