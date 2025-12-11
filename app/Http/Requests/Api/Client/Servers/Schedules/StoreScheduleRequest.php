<?php

namespace App\Http\Requests\Api\Client\Servers\Schedules;

use App\Enums\SubuserPermission;
use App\Models\Schedule;

class StoreScheduleRequest extends ViewScheduleRequest
{
    public function permission(): SubuserPermission
    {
        return SubuserPermission::ScheduleCreate;
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
