<?php

namespace App\Http\Requests\Api\Client\Servers\Schedules;

use App\Enums\SubuserPermission;
use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\Schedule;
use App\Models\Server;
use App\Models\Task;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ViewScheduleRequest extends ClientApiRequest
{
    /**
     * Determine if this resource can be viewed.
     */
    public function authorize(): bool
    {
        if (!parent::authorize()) {
            return false;
        }

        $server = $this->route()->parameter('server');
        $schedule = $this->route()->parameter('schedule');

        // If the schedule does not belong to this server throw a 404 error. Also throw an
        // error if the task being requested does not belong to the associated schedule.
        if ($server instanceof Server && $schedule instanceof Schedule) {
            $task = $this->route()->parameter('task');

            if ($schedule->server_id !== $server->id || ($task instanceof Task && $task->schedule_id !== $schedule->id)) {
                throw new NotFoundHttpException('The requested resource does not exist on the system.');
            }
        }

        return true;
    }

    public function permission(): SubuserPermission
    {
        return SubuserPermission::ScheduleRead;
    }
}
