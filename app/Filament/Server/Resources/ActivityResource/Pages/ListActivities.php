<?php

namespace App\Filament\Server\Resources\ActivityResource\Pages;

use App\Filament\Server\Resources\ActivityResource;
use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
