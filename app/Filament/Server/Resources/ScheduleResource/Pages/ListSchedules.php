<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use App\Filament\Server\Resources\ScheduleResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
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
                ->hiddenLabel()->iconButton()->iconSize(IconSize::Large)
                ->icon('tabler-calendar-plus')
                ->tooltip(trans('server/schedule.new')),
            ImportScheduleAction::make()
                ->hiddenLabel()->iconButton()->iconSize(IconSize::Large)
                ->icon('tabler-download')
                ->tooltip(trans('server/schedule.import')),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return trans('server/schedule.title');
    }
}
