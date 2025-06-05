<?php

namespace App\Filament\Server\Resources\ActivityResource\Pages;

use App\Filament\Server\Resources\ActivityResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    use CanCustomizeHeaderActions;

    protected static string $resource = ActivityResource::class;

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
