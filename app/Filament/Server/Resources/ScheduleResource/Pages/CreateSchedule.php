<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\ScheduleResource;
use App\Helpers\Utilities;
use App\Models\Schedule;
use App\Models\Server;
use Exception;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;

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
            try {
                $data['next_run_at'] = Utilities::getScheduleNextRunDate(
                    $data['cron_minute'],
                    $data['cron_hour'],
                    $data['cron_day_of_month'],
                    $data['cron_month'],
                    $data['cron_day_of_week']
                );
            } catch (Exception) {
                Notification::make()
                    ->title('The cron data provided does not evaluate to a valid expression')
                    ->danger()
                    ->send();

                throw new Halt();
            }
        }

        return $data;
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
