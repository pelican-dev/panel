<?php

namespace App\Filament\Components\Tables\Columns;

use App\Filament\Components\Tables\Columns\Concerns\HasProgress;
use Filament\Support\Facades\FilamentColor;
use Filament\Tables\Columns\Column;

class ServerEntryColumn extends Column
{
    use HasProgress;

    protected string $view = 'livewire.columns.server-entry-column';

    protected function setUp(): void
    {
        parent::setUp();

        $this->dangerColor = FilamentColor::getColor('danger');
        $this->warningColor = FilamentColor::getColor('warning');
        $this->color = FilamentColor::getColor('primary');
    }
}
