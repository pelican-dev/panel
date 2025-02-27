<?php

namespace App\Filament\Server\Resources\ScheduleResource\Pages;

use Carbon\Carbon;
use App\Models\Server;
use App\Helpers\Utilities;
use Filament\Facades\Filament;
use App\Exceptions\DisplayException;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Server\Resources\ScheduleResource;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!isset($data['server_id'])) {
            /** @var Server $server */
            $server = Filament::getTenant();

            $data['server_id'] = $server->id;
        }

        if (!isset($data['next_run_at'])) {
            $data['next_run_at'] = $this->getNextRunAt($data['cron_minute'], $data['cron_hour'], $data['cron_day_of_month'], $data['cron_month'], $data['cron_day_of_week']);
        }

        return $data;
    }

    protected function getNextRunAt(string $minute, string $hour, string $dayOfMonth, string $month, string $dayOfWeek): Carbon
    {
        try {
            return Utilities::getScheduleNextRunDate(
                $minute,
                $hour,
                $dayOfMonth,
                $month,
                $dayOfWeek
            );
        } catch (\Exception) {
            throw new DisplayException('The cron data provided does not evaluate to a valid expression.');
        }
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
