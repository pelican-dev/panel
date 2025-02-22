<?php

namespace App\Filament\Components\Tables\Columns;

use Filament\Tables\Columns\IconColumn;

class NodeHealthColumn extends IconColumn
{
    protected string $view = 'livewire.columns.version-column';

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(trans('admin/node.table.health'));

        $this->alignCenter();
    }
}
