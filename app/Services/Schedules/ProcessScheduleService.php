<?php

namespace App\Services\Schedules;

use App\Enums\ContainerStatus;
use App\Exceptions\DisplayException;
use App\Jobs\Schedule\RunTaskJob;
use App\Models\Schedule;
use App\Models\Task;
use App\Repositories\Daemon\DaemonServerRepository;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Database\ConnectionInterface;

class ProcessScheduleService
{
    public function __construct(private ConnectionInterface $connection, private Dispatcher $dispatcher, private DaemonServerRepository $serverRepository) {}

    /**
     * Process a schedule and push the first task onto the queue worker.
     */
    public function handle(Schedule $schedule, bool $now = false): void
    {
        $task = $schedule->firstTask();

        if (!$task) {
            throw new DisplayException('Cannot process schedule for task execution: no tasks are registered.');
        }

        $this->connection->transaction(function () use ($schedule, $task) {
            $schedule->forceFill([
                'is_processing' => true,
                'next_run_at' => $schedule->getNextRunDate(),
            ])->saveOrFail();

            $task->update(['is_queued' => true]);
        });

        $job = new RunTaskJob($task, $now);
        if ($schedule->only_when_online) {
            // Check that the server is currently in a starting or running state before executing
            // this schedule if this option has been set.
            try {
                $state = ContainerStatus::tryFrom(fluent($this->serverRepository->setServer($schedule->server)->getDetails())->get('state')) ?? ContainerStatus::Offline;

                // If the server is stopping or offline just do nothing with this task.
                if ($state->isOffline()) {
                    $job->failed();

                    return;
                }
            } catch (Exception) {
                $job->failed();

                return;
            }
        }

        if (!$now) {
            $this->dispatcher->dispatch($job->delay($task->time_offset));
        } else {
            // When using dispatchNow the RunTaskJob::failed() function is not called automatically
            // so we need to manually trigger it and then continue with the exception throw.
            try {
                $this->dispatcher->dispatchNow($job);
            } catch (Exception $exception) {
                $job->failed();

                throw $exception;
            }
        }
    }
}
