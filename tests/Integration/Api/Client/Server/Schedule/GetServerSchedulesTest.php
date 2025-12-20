<?php

namespace App\Tests\Integration\Api\Client\Server\Schedule;

use App\Enums\SubuserPermission;
use App\Models\Schedule;
use App\Models\Task;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class GetServerSchedulesTest extends ClientApiIntegrationTestCase
{
    /**
     * Cleanup after tests run.
     */
    protected function tearDown(): void
    {
        Task::query()->forceDelete();
        Schedule::query()->forceDelete();

        parent::tearDown();
    }

    /**
     * Test that schedules for a server are returned.
     */
    #[DataProvider('permissionsDataProvider')]
    public function test_server_schedules_are_returned(array $permissions, bool $individual): void
    {
        [$user, $server] = $this->generateTestAccount($permissions);

        /** @var \App\Models\Schedule $schedule */
        $schedule = Schedule::factory()->create(['server_id' => $server->id]);
        /** @var \App\Models\Task $task */
        $task = Task::factory()->create(['schedule_id' => $schedule->id, 'sequence_id' => 1, 'time_offset' => 0]);

        $response = $this->actingAs($user)
            ->getJson(
                $individual
                    ? "/api/client/servers/$server->uuid/schedules/$schedule->id"
                    : "/api/client/servers/$server->uuid/schedules"
            )
            ->assertOk();

        $prefix = $individual ? '' : 'data.0.';
        if (!$individual) {
            $response->assertJsonCount(1, 'data');
        }

        $response->assertJsonCount(1, $prefix . 'attributes.relationships.tasks.data');

        $response->assertJsonPath($prefix . 'object', Schedule::RESOURCE_NAME);
        $response->assertJsonPath($prefix . 'attributes.relationships.tasks.data.0.object', Task::RESOURCE_NAME);

        $this->assertJsonTransformedWith($response->json($prefix . 'attributes'), $schedule);
        $this->assertJsonTransformedWith($response->json($prefix . 'attributes.relationships.tasks.data.0.attributes'), $task);
    }

    /**
     * Test that a schedule belonging to another server cannot be viewed.
     */
    public function test_schedule_belonging_to_another_server_cannot_be_viewed(): void
    {
        [$user, $server] = $this->generateTestAccount();
        $server2 = $this->createServerModel(['owner_id' => $user->id]);

        $schedule = Schedule::factory()->create(['server_id' => $server2->id]);

        $this->actingAs($user)
            ->getJson("/api/client/servers/$server->uuid/schedules/$schedule->id")
            ->assertNotFound();
    }

    /**
     * Test that a subuser without the required permissions is unable to access the schedules endpoint.
     */
    public function test_user_without_permission_cannot_view_schedules(): void
    {
        [$user, $server] = $this->generateTestAccount([SubuserPermission::WebsocketConnect]);

        $this->actingAs($user)
            ->getJson("/api/client/servers/$server->uuid/schedules")
            ->assertForbidden();

        $schedule = Schedule::factory()->create(['server_id' => $server->id]);

        $this->actingAs($user)
            ->getJson("/api/client/servers/$server->uuid/schedules/$schedule->id")
            ->assertForbidden();
    }

    public static function permissionsDataProvider(): array
    {
        return [
            [[], false],
            [[], true],
            [[SubuserPermission::ScheduleRead], false],
            [[SubuserPermission::ScheduleRead], true],
        ];
    }
}
