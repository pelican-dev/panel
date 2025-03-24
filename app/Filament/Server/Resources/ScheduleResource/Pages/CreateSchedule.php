<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\ScheduleResource;
use App\Models\Schedule;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        /** @var Schedule $schedule */
        $schedule = $this->record;

        Activity::event('server:schedule.create')
            ->property('name', $schedule->name)
            ->log();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!isset($data['server_id'])) {
            /** @var Server $server */
            $server = Filament::getTenant();

            $data['server_id'] = $server->id;
        }

        if (!isset($data['next_run_at'])) {
            $data['next_run_at'] = ScheduleResource::getNextRun(
                $data['cron_minute'],
                $data['cron_hour'],
                $data['cron_day_of_month'],
                $data['cron_month'],
                $data['cron_day_of_week']
            );
        }

        return $data;
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
