<?php

namespace App\Filament\Components\Tables\Columns;

use Closure;
use Filament\Tables\Columns\TextColumn;

class DateTimeColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->dateTime();
    }

    public function since(string|Closure|null $timezone = null): static
    {
        $this->formatStateUsing(fn ($state) => $state->diffForHumans());
        $this->tooltip(fn ($state) => $state?->timezone($this->getTimezone()));

        return $this;
    }

    public function getTimezone(): string
    {
        return user()->timezone ?? config('app.timezone', 'UTC');
    }
}
