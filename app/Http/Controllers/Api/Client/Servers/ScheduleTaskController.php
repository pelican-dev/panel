<?php

namespace App\Http\Controllers\Api\Client\Servers;

use App\Models\Task;
use Illuminate\Http\Response;
use App\Models\Server;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use App\Facades\Activity;
use App\Models\Permission;
use Illuminate\Database\ConnectionInterface;
use App\Exceptions\Http\HttpForbiddenException;
use App\Transformers\Api\Client\TaskTransformer;
use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Http\Controllers\Api\Client\ClientApiController;
use App\Exceptions\Service\ServiceLimitExceededException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\Api\Client\Servers\Schedules\StoreTaskRequest;
use Dedoc\Scramble\Attributes\Group;

#[Group('Server - Schedule', weight: 1)]
class ScheduleTaskController extends ClientApiController
{
    /**
     * ScheduleTaskController constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
    ) {
        parent::__construct();
    }

    /**
     * Create task
     *
     * Create a new task for a given schedule and store it in the database.
     *
     * @return array<array-key, mixed>
     *
     * @throws \App\Exceptions\Model\DataValidationException
     * @throws \App\Exceptions\Service\ServiceLimitExceededException
     */
    public function store(StoreTaskRequest $request, Server $server, Schedule $schedule): array
    {
        $limit = config('panel.client_features.schedules.per_schedule_task_limit', 10);
        if ($schedule->tasks()->count() >= $limit) {
            throw new ServiceLimitExceededException("Schedules may not have more than $limit tasks associated with them. Creating this task would put this schedule over the limit.");
        }

        if ($server->backup_limit === 0 && $request->action === 'backup') {
            throw new HttpForbiddenException("A backup task cannot be created when the server's backup limit is set to 0.");
        }

        /** @var \App\Models\Task|null $lastTask */
        $lastTask = $schedule->tasks()->orderByDesc('sequence_id')->first();

        /** @var \App\Models\Task $task */
        $task = $this->connection->transaction(function () use ($request, $schedule, $lastTask) {
            $sequenceId = ($lastTask->sequence_id ?? 0) + 1;
            $requestSequenceId = $request->integer('sequence_id', $sequenceId);

            // Ensure that the sequence id is at least 1.
            if ($requestSequenceId < 1) {
                $requestSequenceId = 1;
            }

            // If the sequence id from the request is greater than or equal to the next available
            // sequence id, we don't need to do anything special.  Otherwise, we need to update
            // the sequence id of all tasks that are greater than or equal to the request sequence
            // id to be one greater than the current value.
            if ($requestSequenceId < $sequenceId) {
                $schedule->tasks()
                    ->where('sequence_id', '>=', $requestSequenceId)
                    ->increment('sequence_id');
                $sequenceId = $requestSequenceId;
            }

            return Task::query()->create([
                'schedule_id' => $schedule->id,
                'sequence_id' => $sequenceId,
                'action' => $request->input('action'),
                'payload' => $request->input('payload') ?? '',
                'time_offset' => $request->input('time_offset'),
                'continue_on_failure' => $request->boolean('continue_on_failure'),
            ]);
        });

        Activity::event('server:task.create')
            ->subject($schedule, $task)
            ->property(['name' => $schedule->name, 'action' => $task->action, 'payload' => $task->payload])
            ->log();

        return $this->fractal->item($task)
            ->transformWith($this->getTransformer(TaskTransformer::class))
            ->toArray();
    }

    /**
     * Update task
     *
     * Updates a given task for a server.
     *
     * @return array<array-key, mixed>
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function update(StoreTaskRequest $request, Server $server, Schedule $schedule, Task $task): array
    {
        if ($schedule->id !== $task->schedule_id || $server->id !== $schedule->server_id) {
            throw new NotFoundHttpException();
        }

        if ($server->backup_limit === 0 && $request->action === 'backup') {
            throw new HttpForbiddenException("A backup task cannot be created when the server's backup limit is set to 0.");
        }

        $this->connection->transaction(function () use ($request, $schedule, $task) {
            $sequenceId = $request->integer('sequence_id', $task->sequence_id);
            // Ensure that the sequence id is at least 1.
            if ($sequenceId < 1) {
                $sequenceId = 1;
            }

            // Shift all other tasks in the schedule up or down to make room for the new task.
            if ($sequenceId < $task->sequence_id) {
                $schedule->tasks()
                    ->where('sequence_id', '>=', $sequenceId)
                    ->where('sequence_id', '<', $task->sequence_id)
                    ->increment('sequence_id');
            } elseif ($sequenceId > $task->sequence_id) {
                $schedule->tasks()
                    ->where('sequence_id', '>', $task->sequence_id)
                    ->where('sequence_id', '<=', $sequenceId)
                    ->decrement('sequence_id');
            }

            $task->update([
                'sequence_id' => $sequenceId,
                'action' => $request->input('action'),
                'payload' => $request->input('payload') ?? '',
                'time_offset' => $request->input('time_offset'),
                'continue_on_failure' => $request->boolean('continue_on_failure'),
            ]);
        });

        Activity::event('server:task.update')
            ->subject($schedule, $task)
            ->property(['name' => $schedule->name, 'action' => $task->action, 'payload' => $task->payload])
            ->log();

        return $this->fractal->item($task->refresh())
            ->transformWith($this->getTransformer(TaskTransformer::class))
            ->toArray();
    }

    /**
     * Delete task
     *
     * Delete a given task for a schedule. If there are subsequent tasks stored in the database
     * for this schedule their sequence IDs are decremented properly.
     *
     * @throws \Exception
     */
    public function delete(ClientApiRequest $request, Server $server, Schedule $schedule, Task $task): JsonResponse
    {
        if ($task->schedule_id !== $schedule->id || $schedule->server_id !== $server->id) {
            throw new NotFoundHttpException();
        }

        if (!$request->user()->can(Permission::ACTION_SCHEDULE_UPDATE, $server)) {
            throw new HttpForbiddenException('You do not have permission to perform this action.');
        }

        $schedule->tasks()
            ->where('sequence_id', '>', $task->sequence_id)
            ->decrement('sequence_id');
        $task->delete();

        Activity::event('server:task.delete')->subject($schedule, $task)->property('name', $schedule->name)->log();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
