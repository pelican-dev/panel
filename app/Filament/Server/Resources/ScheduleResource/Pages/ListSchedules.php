<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use App\Filament\Server\Resources\ScheduleResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSchedules extends ListRecords
{
    use CanCustomizeHeaderActions;

    protected static string $resource = ScheduleResource::class;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Schedule'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
