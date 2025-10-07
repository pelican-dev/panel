<?php

namespace App\Tests\Integration\Api\Client\Server\Backup;

use App\Models\Backup;
use App\Models\Subuser;
use App\Services\Backups\DeleteBackupService;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\DataProvider;

class BackupAuthorizationTest extends ClientApiIntegrationTestCase
{
    #[DataProvider('methodDataProvider')]
    public function test_access_to_a_servers_backup_is_restricted_properly(string $method, string $endpoint): void
    {
        // The API $user is the owner of $server1.
        [$user, $server1] = $this->generateTestAccount();
        // Will be a subuser of $server2.
        $server2 = $this->createServerModel();
        // And as no access to $server3.
        $server3 = $this->createServerModel();

        // Set the API $user as a subuser of server 2, but with no permissions
        // to do anything with the backups for that server.
        Subuser::factory()->create(['server_id' => $server2->id, 'user_id' => $user->id]);

        $backup1 = Backup::factory()->create(['server_id' => $server1->id, 'completed_at' => CarbonImmutable::now()]);
        $backup2 = Backup::factory()->create(['server_id' => $server2->id, 'completed_at' => CarbonImmutable::now()]);
        $backup3 = Backup::factory()->create(['server_id' => $server3->id, 'completed_at' => CarbonImmutable::now()]);

        $this->instance(DeleteBackupService::class, $mock = \Mockery::mock(DeleteBackupService::class));

        if ($method === 'DELETE') {
            $mock->expects('handle')->andReturnUndefined();
        }

        // This is the only valid call for this test, accessing the backup for the same
        // server that the API user is the owner of.
        $this->actingAs($user)->json($method, $this->link($server1, '/backups/' . $backup1->uuid . $endpoint))
            ->assertStatus($method === 'DELETE' ? 204 : 200);

        // This request fails because the backup is valid for that server but the user
        // making the request is not authorized to perform that action.
        $this->actingAs($user)->json($method, $this->link($server2, '/backups/' . $backup2->uuid . $endpoint))->assertForbidden();

        // Both of these should report a 404 error due to the backup being linked to
        // servers that are not the same as the server in the request, or are assigned
        // to a server for which the user making the request has no access to.
        $this->actingAs($user)->json($method, $this->link($server1, '/backups/' . $backup2->uuid . $endpoint))->assertNotFound();
        $this->actingAs($user)->json($method, $this->link($server1, '/backups/' . $backup3->uuid . $endpoint))->assertNotFound();
        $this->actingAs($user)->json($method, $this->link($server2, '/backups/' . $backup3->uuid . $endpoint))->assertNotFound();
        $this->actingAs($user)->json($method, $this->link($server3, '/backups/' . $backup3->uuid . $endpoint))->assertNotFound();
    }

    public static function methodDataProvider(): array
    {
        return [
            ['GET', ''],
            ['GET', '/download'],
            ['DELETE', ''],
        ];
    }
}
