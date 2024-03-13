<?php

namespace App\Http\Requests\Api\Client\Servers\Schedules;

use App\Models\Permission;

class UpdateScheduleRequest extends StoreScheduleRequest
{
    public function permission(): string
    {
        return Permission::ACTION_SCHEDULE_UPDATE;
    }
}
