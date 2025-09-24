<?php

namespace App\Tests\Integration\Services\Schedules;

use App\Exceptions\DisplayException;
use App\Jobs\Schedule\RunTaskJob;
use App\Models\Schedule;
use App\Models\Task;
use App\Services\Schedules\ProcessScheduleService;
use App\Tests\Integration\IntegrationTestCase;
use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Facades\Bus;
use PHPUnit\Framework\Attributes\DataProvider;

class ProcessScheduleServiceTest extends IntegrationTestCase
{
    /**
     * Test that a schedule with no tasks registered returns an error.
     */
    public function test_schedule_with_no_tasks_returns_exception(): void
    {
        $server = $this->createServerModel();
        $schedule = Schedule::factory()->create(['server_id' => $server->id]);

        $this->expectException(DisplayException::class);
        $this->expectExceptionMessage('Cannot process schedule for task execution: no tasks are registered.');

        $this->getService()->handle($schedule);
    }

    /**
     * Test that an error during the schedule update is not persisted to the database.
     */
    public function test_error_during_schedule_data_update_does_not_persist_changes(): void
    {
        $server = $this->createServerModel();

        /** @var \App\Models\Schedule $schedule */
        $schedule = Schedule::factory()->create([
            'server_id' => $server->id,
            'cron_minute' => 'hodor', // this will break the getNextRunDate() function.
        ]);

        /** @var \App\Models\Task $task */
        $task = Task::factory()->create(['schedule_id' => $schedule->id, 'sequence_id' => 1]);

        $this->expectException(\InvalidArgumentException::class);

        $this->getService()->handle($schedule);

        $this->assertDatabaseMissing('schedules', ['id' => $schedule->id, 'is_processing' => true]);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id, 'is_queued' => true]);
    }

    /**
     * Test that a job is dispatched as expected using the initial delay.
     */
    #[DataProvider('dispatchNowDataProvider')]
    public function test_job_can_be_dispatched_with_expected_initial_delay(bool $now): void
    {
        Bus::fake();

        $server = $this->createServerModel();

        /** @var \App\Models\Schedule $schedule */
        $schedule = Schedule::factory()->create(['server_id' => $server->id]);

        /** @var \App\Models\Task $task */
        $task = Task::factory()->create(['schedule_id' => $schedule->id, 'time_offset' => 10, 'sequence_id' => 1]);

        $this->getService()->handle($schedule, $now);

        Bus::assertDispatched(RunTaskJob::class, function ($job) use ($now, $task) {
            $this->assertInstanceOf(RunTaskJob::class, $job);
            $this->assertSame($task->id, $job->task->id);
            // Jobs using dispatchNow should not have a delay associated with them.
            $this->assertSame($now ? null : 10, $job->delay);

            return true;
        });

        $this->assertDatabaseHas('schedules', ['id' => $schedule->id, 'is_processing' => true]);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'is_queued' => true]);
    }

    /**
     * Test that even if a schedule's task sequence gets messed up the first task based on
     * the ascending order of tasks is used.
     */
    public function test_first_sequence_task_is_found(): void
    {
        Bus::fake();

        $server = $this->createServerModel();
        /** @var \App\Models\Schedule $schedule */
        $schedule = Schedule::factory()->create(['server_id' => $server->id]);

        /** @var \App\Models\Task $task */
        $task2 = Task::factory()->create(['schedule_id' => $schedule->id, 'sequence_id' => 4]);
        $task = Task::factory()->create(['schedule_id' => $schedule->id, 'sequence_id' => 2]);
        $task3 = Task::factory()->create(['schedule_id' => $schedule->id, 'sequence_id' => 3]);

        $this->getService()->handle($schedule);

        Bus::assertDispatched(RunTaskJob::class, function (RunTaskJob $job) use ($task) {
            return $task->id === $job->task->id;
        });

        $this->assertDatabaseHas('schedules', ['id' => $schedule->id, 'is_processing' => true]);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'is_queued' => true]);
        $this->assertDatabaseHas('tasks', ['id' => $task2->id, 'is_queued' => false]);
        $this->assertDatabaseHas('tasks', ['id' => $task3->id, 'is_queued' => false]);
    }

    /**
     * Tests that a task's processing state is reset correctly if using "dispatchNow" and there is
     * an exception encountered while running it.
     */
    public function test_task_dispatched_now_is_reset_properly_if_error_is_encountered(): void
    {
        $this->swap(Dispatcher::class, $dispatcher = \Mockery::mock(Dispatcher::class));

        $server = $this->createServerModel();
        /** @var \App\Models\Schedule $schedule */
        $schedule = Schedule::factory()->create(['server_id' => $server->id, 'last_run_at' => null]);
        /** @var \App\Models\Task $task */
        $task = Task::factory()->create(['schedule_id' => $schedule->id, 'sequence_id' => 1]);

        $dispatcher->expects('dispatchNow')->andThrows(new \Exception('Test thrown exception'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Test thrown exception');

        $this->getService()->handle($schedule, true);

        $this->assertDatabaseHas('schedules', [
            'id' => $schedule->id,
            'is_processing' => false,
            'last_run_at' => CarbonImmutable::now()->toAtomString(),
        ]);

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'is_queued' => false]);
    }

    public static function dispatchNowDataProvider(): array
    {
        return [[true], [false]];
    }

    private function getService(): ProcessScheduleService
    {
        return $this->app->make(ProcessScheduleService::class);
    }
}
