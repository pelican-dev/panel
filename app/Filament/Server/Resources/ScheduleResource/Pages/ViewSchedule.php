<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\ScheduleResource;
use App\Models\Permission;
use App\Models\Schedule;
use App\Services\Schedules\ProcessScheduleService;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
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
            Action::make('runNow')
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_SCHEDULE_UPDATE, Filament::getTenant()))
                ->label(fn (Schedule $schedule) => $schedule->tasks->count() === 0 ? trans('server/schedule.no_tasks') : ($schedule->is_processing ? trans('server/schedule.processing') : trans('server/schedule.run_now')))
                ->color(fn (Schedule $schedule) => $schedule->tasks->count() === 0 || $schedule->is_processing ? 'warning' : 'primary')
                ->disabled(fn (Schedule $schedule) => $schedule->tasks->count() === 0 || $schedule->is_processing)
                ->action(function (ProcessScheduleService $service, Schedule $schedule) {
                    $service->handle($schedule, true);

                    Activity::event('server:schedule.execute')
                        ->subject($schedule)
                        ->property('name', $schedule->name)
                        ->log();

                    $this->fillForm();
                }),
            EditAction::make()
                ->hiddenLabel()->iconButton()->iconSize(IconSize::Large)
                ->icon('tabler-calendar-code')
                ->tooltip(trans('server/schedule.edit')),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
