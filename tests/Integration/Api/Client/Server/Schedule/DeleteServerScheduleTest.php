<?php

namespace App\Tests\Integration\Api\Client\Server\Schedule;

use App\Enums\SubuserPermission;
use App\Models\Schedule;
use App\Models\Task;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\DataProvider;

class DeleteServerScheduleTest extends ClientApiIntegrationTestCase
{
    /**
     * Test that a schedule can be deleted from the system.
     */
    #[DataProvider('permissionsDataProvider')]
    public function test_schedule_can_be_deleted(array $permissions): void
    {
        [$user, $server] = $this->generateTestAccount($permissions);

        $schedule = Schedule::factory()->create(['server_id' => $server->id]);
        $task = Task::factory()->create(['schedule_id' => $schedule->id]);

        $this->actingAs($user)
            ->deleteJson("/api/client/servers/$server->uuid/schedules/$schedule->id")
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('schedules', ['id' => $schedule->id]);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /**
     * Test that no error is returned if the schedule does not exist on the system at all.
     */
    public function test_not_found_error_is_returned_if_schedule_does_not_exist_at_all(): void
    {
        [$user, $server] = $this->generateTestAccount();

        $this->actingAs($user)
            ->deleteJson("/api/client/servers/$server->uuid/schedules/123456789")
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * Ensure that a schedule belonging to another server cannot be deleted and its presence is not
     * revealed to the user.
     */
    public function test_not_found_error_is_returned_if_schedule_does_not_belong_to_server(): void
    {
        [$user, $server] = $this->generateTestAccount();
        $server2 = $this->createServerModel(['owner_id' => $user->id]);

        $schedule = Schedule::factory()->create(['server_id' => $server2->id]);

        $this->actingAs($user)
            ->deleteJson("/api/client/servers/$server->uuid/schedules/$schedule->id")
            ->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertDatabaseHas('schedules', ['id' => $schedule->id]);
    }

    /**
     * Test that an error is returned if the subuser does not have the required permissions to
     * delete the schedule from the server.
     */
    public function test_error_is_returned_if_subuser_does_not_have_required_permissions(): void
    {
        [$user, $server] = $this->generateTestAccount([SubuserPermission::ScheduleUpdate]);

        $schedule = Schedule::factory()->create(['server_id' => $server->id]);

        $this->actingAs($user)
            ->deleteJson("/api/client/servers/$server->uuid/schedules/$schedule->id")
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseHas('schedules', ['id' => $schedule->id]);
    }

    public static function permissionsDataProvider(): array
    {
        return [[[]], [[SubuserPermission::ScheduleDelete]]];
    }
}
