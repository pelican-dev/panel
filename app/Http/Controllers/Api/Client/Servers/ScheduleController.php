<?php

namespace App\Http\Controllers\Api\Client\Servers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Server;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use App\Facades\Activity;
use App\Helpers\Utilities;
use App\Exceptions\DisplayException;
use App\Services\Schedules\ProcessScheduleService;
use App\Transformers\Api\Client\ScheduleTransformer;
use App\Http\Controllers\Api\Client\ClientApiController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\Api\Client\Servers\Schedules\ViewScheduleRequest;
use App\Http\Requests\Api\Client\Servers\Schedules\StoreScheduleRequest;
use App\Http\Requests\Api\Client\Servers\Schedules\DeleteScheduleRequest;
use App\Http\Requests\Api\Client\Servers\Schedules\UpdateScheduleRequest;
use App\Http\Requests\Api\Client\Servers\Schedules\TriggerScheduleRequest;
use Dedoc\Scramble\Attributes\Group;

#[Group('Server - Schedule', weight: 0)]
class ScheduleController extends ClientApiController
{
    /**
     * ScheduleController constructor.
     */
    public function __construct(private ProcessScheduleService $service)
    {
        parent::__construct();
    }

    /**
     * List schedules
     *
     * Returns all the schedules belonging to a given server.
     *
     * @return array<array-key, mixed>
     */
    public function index(ViewScheduleRequest $request, Server $server): array
    {
        $schedules = $server->schedules->loadMissing('tasks');

        return $this->fractal->collection($schedules)
            ->transformWith($this->getTransformer(ScheduleTransformer::class))
            ->toArray();
    }

    /**
     * Create schedule
     *
     * Store a new schedule for a server.
     *
     * @return array<array-key, mixed>
     *
     * @throws \App\Exceptions\DisplayException
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function store(StoreScheduleRequest $request, Server $server): array
    {
        /** @var \App\Models\Schedule $model */
        $model = Schedule::query()->create([
            'server_id' => $server->id,
            'name' => $request->input('name'),
            'cron_day_of_week' => $request->input('day_of_week'),
            'cron_month' => $request->input('month'),
            'cron_day_of_month' => $request->input('day_of_month'),
            'cron_hour' => $request->input('hour'),
            'cron_minute' => $request->input('minute'),
            'is_active' => (bool) $request->input('is_active'),
            'only_when_online' => (bool) $request->input('only_when_online'),
            'next_run_at' => $this->getNextRunAt($request),
        ]);

        Activity::event('server:schedule.create')
            ->subject($model)
            ->property('name', $model->name)
            ->log();

        return $this->fractal->item($model)
            ->transformWith($this->getTransformer(ScheduleTransformer::class))
            ->toArray();
    }

    /**
     * View schedule
     *
     * Returns a specific schedule for the server.
     *
     * @return array<array-key, mixed>
     */
    public function view(ViewScheduleRequest $request, Server $server, Schedule $schedule): array
    {
        if ($schedule->server_id !== $server->id) {
            throw new NotFoundHttpException();
        }

        $schedule->loadMissing('tasks');

        return $this->fractal->item($schedule)
            ->transformWith($this->getTransformer(ScheduleTransformer::class))
            ->toArray();
    }

    /**
     * Update schedule
     *
     * Updates a given schedule with the new data provided.
     *
     * @return array<array-key, mixed>
     *
     * @throws \App\Exceptions\DisplayException
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function update(UpdateScheduleRequest $request, Server $server, Schedule $schedule): array
    {
        $active = (bool) $request->input('is_active');

        $data = [
            'name' => $request->input('name'),
            'cron_day_of_week' => $request->input('day_of_week'),
            'cron_month' => $request->input('month'),
            'cron_day_of_month' => $request->input('day_of_month'),
            'cron_hour' => $request->input('hour'),
            'cron_minute' => $request->input('minute'),
            'is_active' => $active,
            'only_when_online' => (bool) $request->input('only_when_online'),
            'next_run_at' => $this->getNextRunAt($request),
        ];

        // Toggle the processing state of the scheduled task when it is enabled or disabled so that an
        // invalid state can be reset without manual database intervention.
        if ($schedule->is_active !== $active) {
            $data['is_processing'] = false;
        }

        $schedule->update($data);

        Activity::event('server:schedule.update')
            ->subject($schedule)
            ->property(['name' => $schedule->name, 'active' => $active])
            ->log();

        return $this->fractal->item($schedule->refresh())
            ->transformWith($this->getTransformer(ScheduleTransformer::class))
            ->toArray();
    }

    /**
     * Run schedule
     *
     * Executes a given schedule immediately rather than waiting on it's normally scheduled time
     * to pass. This does not care about the schedule state.
     *
     * @throws \Throwable
     */
    public function execute(TriggerScheduleRequest $request, Server $server, Schedule $schedule): JsonResponse
    {
        $this->service->handle($schedule, true);

        Activity::event('server:schedule.execute')->subject($schedule)->property('name', $schedule->name)->log();

        return new JsonResponse([], JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Delete schedule
     *
     * Deletes a schedule and it's associated tasks.
     */
    public function delete(DeleteScheduleRequest $request, Server $server, Schedule $schedule): JsonResponse
    {
        $schedule->delete();

        Activity::event('server:schedule.delete')->subject($schedule)->property('name', $schedule->name)->log();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Get the next run timestamp based on the cron data provided.
     *
     * @throws \App\Exceptions\DisplayException
     */
    protected function getNextRunAt(Request $request): Carbon
    {
        try {
            return Utilities::getScheduleNextRunDate(
                $request->input('minute'),
                $request->input('hour'),
                $request->input('day_of_month'),
                $request->input('month'),
                $request->input('day_of_week')
            );
        } catch (Exception) {
            throw new DisplayException('The cron data provided does not evaluate to a valid expression.');
        }
    }
}
