<?php

namespace App\Tests\Integration\Jobs\Schedule;

use App\Enums\ServerState;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use GuzzleHttp\Psr7\Request;
use App\Models\Task;
use GuzzleHttp\Psr7\Response;
use App\Models\Server;
use App\Models\Schedule;
use Illuminate\Support\Facades\Bus;
use App\Jobs\Schedule\RunTaskJob;
use GuzzleHttp\Exception\BadResponseException;
use App\Tests\Integration\IntegrationTestCase;
use App\Repositories\Daemon\DaemonPowerRepository;
use App\Exceptions\Http\Connection\DaemonConnectionException;

class RunTaskJobTest extends IntegrationTestCase
{
    /**
     * An inactive job should not be run by the system.
     */
    public function testInactiveJobIsNotRun(): void
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

    public function testJobWithInvalidActionThrowsException(): void
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

    /**
     * @dataProvider isManualRunDataProvider
     */
    public function testJobIsExecuted(bool $isManualRun): void
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
            'action' => Task::ACTION_POWER,
            'payload' => 'start',
            'is_queued' => true,
            'continue_on_failure' => false,
        ]);

        $mock = \Mockery::mock(DaemonPowerRepository::class);
        $this->instance(DaemonPowerRepository::class, $mock);

        $mock->expects('setServer')->with(\Mockery::on(function ($value) use ($server) {
            return $value instanceof Server && $value->id === $server->id;
        }))->andReturnSelf();
        $mock->expects('send')->with('start');

        Bus::dispatchSync(new RunTaskJob($task, $isManualRun));

        $task->refresh();
        $schedule->refresh();

        $this->assertFalse($task->is_queued);
        $this->assertFalse($schedule->is_processing);
        $this->assertTrue(CarbonImmutable::now()->isSameAs(\DateTimeInterface::ATOM, $schedule->last_run_at));
    }

    /**
     * @dataProvider isManualRunDataProvider
     */
    public function testExceptionDuringRunIsHandledCorrectly(bool $continueOnFailure): void
    {
        $server = $this->createServerModel();

        /** @var \App\Models\Schedule $schedule */
        $schedule = Schedule::factory()->create(['server_id' => $server->id]);
        /** @var \App\Models\Task $task */
        $task = Task::factory()->create([
            'schedule_id' => $schedule->id,
            'action' => Task::ACTION_POWER,
            'payload' => 'start',
            'continue_on_failure' => $continueOnFailure,
        ]);

        $mock = \Mockery::mock(DaemonPowerRepository::class);
        $this->instance(DaemonPowerRepository::class, $mock);

        $mock->expects('setServer->send')->andThrow(
            new DaemonConnectionException(new BadResponseException('Bad request', new Request('GET', '/test'), new Response()))
        );

        if (!$continueOnFailure) {
            $this->expectException(DaemonConnectionException::class);
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
    public function testTaskIsNotRunIfServerIsSuspended(): void
    {
        $server = $this->createServerModel([
            'status' => ServerState::Suspended,
        ]);

        $schedule = Schedule::factory()->for($server)->create([
            'last_run_at' => Carbon::now()->subHour(),
        ]);

        $task = Task::factory()->for($schedule)->create([
            'action' => Task::ACTION_POWER,
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
