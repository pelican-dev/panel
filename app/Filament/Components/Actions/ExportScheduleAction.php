<?php

namespace App\Filament\Components\Actions;

use App\Models\Permission;
use App\Models\Schedule;
use App\Models\Server;
use App\Services\Schedules\Sharing\ScheduleExporterService;
use Filament\Actions\Action;
use Filament\Facades\Filament;

class ExportScheduleAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'export';
    }

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Server $server */
        $server = Filament::getTenant();

        $this->label(trans('filament-actions::export.modal.actions.export.label'));

        $this->authorize(fn () => auth()->user()->can(Permission::ACTION_SCHEDULE_READ, $server));

        $this->action(fn (ScheduleExporterService $service, Schedule $schedule) => response()->streamDownload(function () use ($service, $schedule) {
            echo $service->handle($schedule);
        }, 'schedule-' . str($schedule->name)->kebab()->lower()->trim() . '.json'));
    }
}
