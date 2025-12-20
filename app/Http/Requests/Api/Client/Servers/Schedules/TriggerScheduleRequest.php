<?php

namespace App\Http\Requests\Api\Client\Servers\Schedules;

use App\Enums\SubuserPermission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class TriggerScheduleRequest extends ClientApiRequest
{
    public function permission(): SubuserPermission
    {
        return SubuserPermission::ScheduleUpdate;
    }

    public function rules(): array
    {
        return [];
    }
}
