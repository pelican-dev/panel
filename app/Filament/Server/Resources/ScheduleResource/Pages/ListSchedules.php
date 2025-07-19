<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use App\Filament\Components\Actions\ImportScheduleAction;
use App\Filament\Server\Resources\ScheduleResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSchedules extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = ScheduleResource::class;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Schedule'),
            ImportScheduleAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
