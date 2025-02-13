<?php

namespace App\Tests\Integration\Api\Client\Server\Schedule;

use App\Models\Task;
use Illuminate\Http\Response;
use App\Models\Schedule;
use App\Models\Permission;
use Illuminate\Support\Facades\Bus;
use App\Jobs\Schedule\RunTaskJob;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ExecuteScheduleTest extends ClientApiIntegrationTestCase
{
    /**
     * Test that a schedule can be executed and is updated in the database correctly.
     */
    #[DataProvider('permissionsDataProvider')]
    public function test_schedule_is_executed_right_away(array $permissions): void
    {
        [$user, $server] = $this->generateTestAccount($permissions);

        Bus::fake();

        /** @var \App\Models\Schedule $schedule */
        $schedule = Schedule::factory()->create([
            'server_id' => $server->id,
        ]);

        $response = $this->actingAs($user)->postJson($this->link($schedule, '/execute'));
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('errors.0.code', 'DisplayException');
        $response->assertJsonPath('errors.0.detail', 'Cannot process schedule for task execution: no tasks are registered.');

        /** @var \App\Models\Task $task */
        $task = Task::factory()->create([
            'schedule_id' => $schedule->id,
            'sequence_id' => 1,
            'time_offset' => 2,
        ]);

        $this->actingAs($user)->postJson($this->link($schedule, '/execute'))->assertStatus(Response::HTTP_ACCEPTED);

        Bus::assertDispatched(function (RunTaskJob $job) use ($task) {
            // A task executed right now should not have any job delay associated with it.
            $this->assertNull($job->delay);
            $this->assertSame($task->id, $job->task->id);

            return true;
        });
    }

    /**
     * Test that a user without the schedule update permission cannot execute it.
     */
    public function test_user_without_schedule_update_permission_cannot_execute(): void
    {
        [$user, $server] = $this->generateTestAccount([Permission::ACTION_SCHEDULE_CREATE]);

        /** @var \App\Models\Schedule $schedule */
        $schedule = Schedule::factory()->create(['server_id' => $server->id]);

        $this->actingAs($user)->postJson($this->link($schedule, '/execute'))->assertForbidden();
    }

    public static function permissionsDataProvider(): array
    {
        return [[[]], [[Permission::ACTION_SCHEDULE_UPDATE]]];
    }
}
