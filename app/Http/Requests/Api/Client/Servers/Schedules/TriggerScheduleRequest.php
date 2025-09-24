<?php

namespace App\Http\Requests\Api\Client\Servers\Schedules;

use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\Permission;

class TriggerScheduleRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_SCHEDULE_UPDATE;
    }

    public function rules(): array
    {
        return [];
    }
}
