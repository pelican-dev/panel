<?php

namespace App\Features;

use Filament\Actions\Action;

abstract class Feature
{
    /**
     * A matching subset string (case-sensitive) from the console output
     *
     * @return array<string>
     */
    abstract public function listeners(): array;

    /** eula */
    abstract public function featureName(): string;

    abstract public function action(): Action;

    public function matchesListeners(string $line): bool
    {
        return collect(static::listeners())->contains(fn ($value) => str($line)->contains($value, true));
    }
}
