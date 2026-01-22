<?php

namespace App\Filament\Server\Resources\Schedules\Pages;

use App\Filament\Server\Resources\Schedules\ScheduleResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Resources\Pages\ListRecords;

class ListSchedules extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = ScheduleResource::class;

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return trans('server/schedule.title');
    }
}
