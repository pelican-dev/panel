<?php

namespace App\Filament\Components\Actions;

use App\Enums\SubuserPermission;
use App\Models\Schedule;
use App\Models\Server;
use App\Services\Schedules\Sharing\ScheduleExporterService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Support\Enums\IconSize;

class ExportScheduleAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'export';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->hiddenLabel();

        $this->iconButton();

        $this->iconSize(IconSize::ExtraLarge);

        $this->icon('tabler-download');

        $this->tooltip(trans('server/schedule.export'));

        /** @var Server $server */
        $server = Filament::getTenant();

        $this->label(trans('filament-actions::export.modal.actions.export.label'));

        $this->authorize(fn () => user()?->can(SubuserPermission::ScheduleRead, $server));

        $this->action(fn (ScheduleExporterService $service, Schedule $schedule) => response()->streamDownload(function () use ($service, $schedule) {
            echo $service->handle($schedule);
        }, 'schedule-' . str($schedule->name)->kebab()->lower()->trim() . '.json', [
            'Content-Type' => 'application/json',
        ]));
    }
}
