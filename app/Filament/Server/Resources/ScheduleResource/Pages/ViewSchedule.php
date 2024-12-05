<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use App\Filament\Server\Resources\ScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSchedule extends ViewRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
