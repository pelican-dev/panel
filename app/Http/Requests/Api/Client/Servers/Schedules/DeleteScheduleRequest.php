<?php

namespace App\Http\Requests\Api\Client\Servers\Schedules;

use App\Enums\SubuserPermission;

class DeleteScheduleRequest extends ViewScheduleRequest
{
    public function permission(): SubuserPermission
    {
        return SubuserPermission::ScheduleDelete;
    }
}
