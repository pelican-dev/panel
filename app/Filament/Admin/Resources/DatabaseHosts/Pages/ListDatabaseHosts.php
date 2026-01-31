<?php

namespace App\Filament\Admin\Resources\DatabaseHosts\Pages;

use App\Filament\Admin\Resources\DatabaseHosts\DatabaseHostResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Resources\Pages\ListRecords;

class ListDatabaseHosts extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = DatabaseHostResource::class;
}
