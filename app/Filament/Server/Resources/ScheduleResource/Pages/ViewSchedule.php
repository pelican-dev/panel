<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use Filament\Actions;
use App\Models\Schedule;
use App\Facades\Activity;
use App\Models\Permission;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ViewRecord;
use App\Services\Schedules\ProcessScheduleService;
use App\Filament\Server\Resources\ScheduleResource;

class ViewSchedule extends ViewRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('runNow')
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
            Actions\EditAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
