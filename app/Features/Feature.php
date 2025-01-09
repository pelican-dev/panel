<?php

namespace App\Features;

use Filament\Actions\Action;

abstract class Feature
{
    /** you need to agree to the eula in order to run the server */
    abstract static public function listeners(): array;

    /** eula */
    abstract static public function featureName(): string;

    abstract static public function action(): Action;

    public function matchesListeners(string $line): bool
    {
        return collect(static::listeners())->contains(fn ($value) => str($line)->lower->contains($value));
    }
}
