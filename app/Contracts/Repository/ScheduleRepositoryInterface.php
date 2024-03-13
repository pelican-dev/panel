<?php

namespace App\Contracts\Repository;

use App\Models\Schedule;
use Illuminate\Support\Collection;

interface ScheduleRepositoryInterface extends RepositoryInterface
{
    /**
     * Return all the schedules for a given server.
     */
    public function findServerSchedules(int $server): Collection;

    /**
     * Return a schedule model with all the associated tasks as a relationship.
     *
     * @throws \App\Exceptions\Repository\RecordNotFoundException
     */
    public function getScheduleWithTasks(int $schedule): Schedule;
}
