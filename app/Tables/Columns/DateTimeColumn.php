<?php

namespace App\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class DateTimeColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->dateTime(timezone: auth()->user()?->timezone ?? config('app.timezone', 'UTC'));
    }
}
