<?php

namespace App\Filament\Server\Resources\Schedules\Pages;

use App\Filament\Server\Resources\Schedules\ScheduleResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\IconSize;

class ViewSchedule extends ViewRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = ScheduleResource::class;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            EditAction::make()
                ->hiddenLabel()->iconButton()->iconSize(IconSize::ExtraLarge)
                ->icon('tabler-calendar-code')
                ->tooltip(trans('server/schedule.edit')),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
