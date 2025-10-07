<?php

namespace App\Http\Requests\Api\Client\Servers\Schedules;

use App\Models\Permission;
use App\Models\Schedule;

class StoreScheduleRequest extends ViewScheduleRequest
{
    public function permission(): string
    {
        return Permission::ACTION_SCHEDULE_CREATE;
    }

    public function rules(): array
    {
        $rules = Schedule::getRules();

        return [
            'name' => $rules['name'],
            'is_active' => array_merge(['filled'], $rules['is_active']),
            'only_when_online' => $rules['only_when_online'],
            'minute' => $rules['cron_minute'],
            'hour' => $rules['cron_hour'],
            'day_of_month' => $rules['cron_day_of_month'],
            'month' => $rules['cron_month'],
            'day_of_week' => $rules['cron_day_of_week'],
        ];
    }
}
