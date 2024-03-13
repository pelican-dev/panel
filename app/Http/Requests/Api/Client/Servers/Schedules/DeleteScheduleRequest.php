<?php

namespace App\Http\Requests\Api\Client\Servers\Schedules;

use App\Models\Permission;

class DeleteScheduleRequest extends ViewScheduleRequest
{
    public function permission(): string
    {
        return Permission::ACTION_SCHEDULE_DELETE;
    }
}
