<?php

namespace App\Filament\Components\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class BytesColumn extends TextColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->formatStateUsing(fn ($state) => $state ? convert_bytes_to_readable($state) : '');
    }
}
