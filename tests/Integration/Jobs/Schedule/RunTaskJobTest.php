<?php

namespace App\Tests\Integration\Jobs\Schedule;

use App\Enums\ServerState;
use App\Jobs\Schedule\RunTaskJob;
use App\Models\Schedule;
use App\Models\Server;
use App\Models\Task;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Tests\Integration\IntegrationTestCase;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Bus;
use PHPUnit\Framework\Attributes\DataProvider;

class RunTaskJobTest extends IntegrationTestCase
{
    /**
     * An inactive job should not be run by the system.
     */
    public function test_inactive_job_is_not_run(): void
    {
        $server = $this->createServerModel();

        /** @var \App\Models\Schedule $schedule */
        $schedule = Schedule::factory()->create([
            'server_id' => $server->id,
            'is_processing' => true,
            'last_run_at' => null,
            'is_active' => false,
        ]);
        /** @var \App\Models\Task $task */
        $task = Task::factory()->create(['schedule_id' => $schedule->id, 'is_queued' => true]);

        $job = new RunTaskJob($task);

        Bus::dispatchSync($job);

        $task->refresh();
        $schedule->refresh();

        $this->assertFalse($task->is_queued);
        $this->assertFalse($schedule->is_processing);
        $this->assertFalse($schedule->is_active);
        $this->assertTrue(CarbonImmutable::now()->isSameAs(\DateTimeInterface::ATOM, $schedule->last_run_at));
    }

    public function test_job_with_invalid_action_throws_exception(): void
    {
        $server = $this->createServerModel();

        /** @var \App\Models\Schedule $schedule */
        $schedule = Schedule::factory()->create(['server_id' => $server->id]);
        /** @var \App\Models\Task $task */
        $task = Task::factory()->create(['schedule_id' => $schedule->id, 'action' => 'foobar']);

        $job = new RunTaskJob($task);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid task action provided: foobar');
        Bus::dispatchSync($job);
    }

    #[DataProvider('isManualRunDataProvider')]
    public function test_job_is_executed(bool $isManualRun): void
    {
        $server = $this->createServerModel();

        /** @var \App\Models\Schedule $schedule */
        $schedule = Schedule::factory()->create([
            'server_id' => $server->id,
            'is_active' => !$isManualRun,
            'is_processing' => true,
            'last_run_at' => null,
        ]);
        /** @var \App\Models\Task $task */
        $task = Task::factory()->create([
            'schedule_id' => $schedule->id,
            'action' => 'power',
            'payload' => 'start',
            'is_queued' => true,
            'continue_on_failure' => false,
        ]);

        $mock = \Mockery::mock(DaemonServerRepository::class);
        $this->instance(DaemonServerRepository::class, $mock);

        $mock->expects('setServer')->with(\Mockery::on(function ($value) use ($server) {
            return $value instanceof Server && $value->id === $server->id;
        }))->andReturnSelf();
        $mock->expects('power')->with('start');

        Bus::dispatchSync(new RunTaskJob($task, $isManualRun));

        $task->refresh();
        $schedule->refresh();

        $this->assertFalse($task->is_queued);
        $this->assertFalse($schedule->is_processing);
        $this->assertTrue(CarbonImmutable::now()->isSameAs(\DateTimeInterface::ATOM, $schedule->last_run_at));
    }

    #[DataProvider('isManualRunDataProvider')]
    public function test_exception_during_run_is_handled_correctly(bool $continueOnFailure): void
    {
        $server = $this->createServerModel();

        /** @var \App\Models\Schedule $schedule */
        $schedule = Schedule::factory()->create(['server_id' => $server->id]);
        /** @var \App\Models\Task $task */
        $task = Task::factory()->create([
            'schedule_id' => $schedule->id,
            'action' => 'power',
            'payload' => 'start',
            'continue_on_failure' => $continueOnFailure,
        ]);

        $mock = \Mockery::mock(DaemonServerRepository::class);
        $this->instance(DaemonServerRepository::class, $mock);

        $mock->expects('setServer->power')->andThrow(new ConnectionException());

        if (!$continueOnFailure) {
            $this->expectException(ConnectionException::class);
        }

        Bus::dispatchSync(new RunTaskJob($task));

        if ($continueOnFailure) {
            $task->refresh();
            $schedule->refresh();

            $this->assertFalse($task->is_queued);
            $this->assertFalse($schedule->is_processing);
            $this->assertTrue(CarbonImmutable::now()->isSameAs(\DateTimeInterface::ATOM, $schedule->last_run_at));
        }
    }

    /**
     * Test that a schedule is not executed if the server is suspended.
     */
    public function test_task_is_not_run_if_server_is_suspended(): void
    {
        $server = $this->createServerModel([
            'status' => ServerState::Suspended,
        ]);

        $schedule = Schedule::factory()->for($server)->create([
            'last_run_at' => Carbon::now()->subHour(),
        ]);

        $task = Task::factory()->for($schedule)->create([
            'action' => 'power',
            'payload' => 'start',
        ]);

        Bus::dispatchSync(new RunTaskJob($task));

        $task->refresh();
        $schedule->refresh();

        $this->assertFalse($task->is_queued);
        $this->assertFalse($schedule->is_processing);
        $this->assertTrue(Carbon::now()->isSameAs(\DateTimeInterface::ATOM, $schedule->last_run_at));
    }

    public static function isManualRunDataProvider(): array
    {
        return [[true], [false]];
    }
}
