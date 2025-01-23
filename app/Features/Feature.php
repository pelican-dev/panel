<?php

namespace App\Features;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;

abstract class Feature implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    /** you need to agree to the eula in order to run the server */
    abstract public function listeners(): array;

    /** eula */
    abstract public function featureName(): string;

    //    abstract public function action(): Action;
    abstract public function modal(): Form;

    public function matchesListeners(string $line): bool
    {
        return collect(static::listeners())->contains(fn ($value) => str($line)->lower->contains($value));
    }
}
