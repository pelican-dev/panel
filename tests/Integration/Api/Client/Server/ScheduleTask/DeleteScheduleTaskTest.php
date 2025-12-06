<?php

namespace App\Tests\Integration\Api\Client\Server\ScheduleTask;

use App\Enums\SubuserPermission;
use App\Models\Schedule;
use App\Models\Task;
use App\Models\User;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use Illuminate\Http\Response;

class DeleteScheduleTaskTest extends ClientApiIntegrationTestCase
{
    /**
     * Test that an error is returned if the schedule does not belong to the server.
     */
    public function test_schedule_not_belonging_to_server_returns_error(): void
    {
        $server2 = $this->createServerModel();
        [$user] = $this->generateTestAccount();

        $schedule = Schedule::factory()->create(['server_id' => $server2->id]);
        $task = Task::factory()->create(['schedule_id' => $schedule->id]);

        $this->actingAs($user)->deleteJson($this->link($task))->assertNotFound();
    }

    /**
     * Test that an error is returned if the task and schedule in the URL do not line up
     * with each other.
     */
    public function test_task_belonging_to_different_schedule_returns_error(): void
    {
        [$user, $server] = $this->generateTestAccount();

        $schedule = Schedule::factory()->create(['server_id' => $server->id]);
        $schedule2 = Schedule::factory()->create(['server_id' => $server->id]);
        $task = Task::factory()->create(['schedule_id' => $schedule->id]);

        $this->actingAs($user)->deleteJson("/api/client/servers/$server->uuid/schedules/$schedule2->id/tasks/$task->id")->assertNotFound();
    }

    /**
     * Test that a user without the required permissions returns an error.
     */
    public function test_user_without_permission_returns_error(): void
    {
        [$user, $server] = $this->generateTestAccount([SubuserPermission::ScheduleCreate]);

        $schedule = Schedule::factory()->create(['server_id' => $server->id]);
        $task = Task::factory()->create(['schedule_id' => $schedule->id]);

        $this->actingAs($user)->deleteJson($this->link($task))->assertForbidden();

        $user2 = User::factory()->create();

        $this->actingAs($user2)->deleteJson($this->link($task))->assertNotFound();
    }

    /**
     * Test that a schedule task is deleted and items with a higher sequence ID are decremented
     * properly in the database.
     */
    public function test_schedule_task_is_deleted_and_subsequent_tasks_are_updated(): void
    {
        [$user, $server] = $this->generateTestAccount();

        $schedule = Schedule::factory()->create(['server_id' => $server->id]);
        $tasks = [
            Task::factory()->create(['schedule_id' => $schedule->id, 'sequence_id' => 1]),
            Task::factory()->create(['schedule_id' => $schedule->id, 'sequence_id' => 2]),
            Task::factory()->create(['schedule_id' => $schedule->id, 'sequence_id' => 3]),
            Task::factory()->create(['schedule_id' => $schedule->id, 'sequence_id' => 4]),
        ];

        $response = $this->actingAs($user)->deleteJson($this->link($tasks[1]));
        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('tasks', ['id' => $tasks[0]->id, 'sequence_id' => 1]);
        $this->assertDatabaseHas('tasks', ['id' => $tasks[2]->id, 'sequence_id' => 2]);
        $this->assertDatabaseHas('tasks', ['id' => $tasks[3]->id, 'sequence_id' => 3]);
        $this->assertDatabaseMissing('tasks', ['id' => $tasks[1]->id]);
    }
}
