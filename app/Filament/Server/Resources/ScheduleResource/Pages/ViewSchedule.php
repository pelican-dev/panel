<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\ScheduleResource;
use App\Models\Permission;
use App\Models\Schedule;
use App\Services\Schedules\ProcessScheduleService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ViewRecord;

class ViewSchedule extends ViewRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('runNow')
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_SCHEDULE_UPDATE, Filament::getTenant()))
                ->label(fn (Schedule $schedule) => $schedule->tasks->count() === 0 ? 'No tasks' : ($schedule->is_processing ? 'Processing' : 'Run now'))
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
            EditAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
