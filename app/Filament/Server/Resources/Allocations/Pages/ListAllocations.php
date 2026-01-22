<?php

namespace App\Filament\Server\Resources\Allocations\Pages;

use App\Filament\Server\Resources\Allocations\AllocationResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Resources\Pages\ListRecords;

class ListAllocations extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = AllocationResource::class;

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return trans('server/network.title');
    }

    public static function getNavigationLabel(): string
    {
        return trans('server/network.title');
    }
}
