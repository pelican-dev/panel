<?php

namespace App\Http\Requests\Api\Client\Servers\Schedules;

use App\Enums\SubuserPermission;

class UpdateScheduleRequest extends StoreScheduleRequest
{
    public function permission(): SubuserPermission
    {
        return SubuserPermission::ScheduleUpdate;
    }
}
