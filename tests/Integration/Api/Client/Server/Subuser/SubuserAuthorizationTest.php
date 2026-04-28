<?php

namespace App\Tests\Integration\Api\Client\Server\Subuser;

use App\Jobs\RevokeSftpAccessJob;
use App\Models\Subuser;
use App\Models\User;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use Illuminate\Support\Facades\Bus;
use PHPUnit\Framework\Attributes\DataProvider;

class SubuserAuthorizationTest extends ClientApiIntegrationTestCase
{
    /**
     * Test that mismatched subusers are not accessible to a server.
     */
    #[DataProvider('methodDataProvider')]
    public function test_user_cannot_access_resource_belonging_to_other_servers(string $method): void
    {
        Bus::fake([RevokeSftpAccessJob::class]);

        // Generic subuser, the specific resource we're trying to access.
        /** @var User $internal */
        $internal = User::factory()->create();

        // The API $user is the owner of $server1.
        [$user, $server1] = $this->generateTestAccount();
        // Will be a subuser of $server2.
        $server2 = $this->createServerModel();
        // And as no access to $server3.
        $server3 = $this->createServerModel();

        // Set the API $user as a subuser of server 2, but with no permissions
        // to do anything with the subusers for that server.
        Subuser::factory()->create(['server_id' => $server2->id, 'user_id' => $user->id]);

        Subuser::factory()->create(['server_id' => $server1->id, 'user_id' => $internal->id]);
        Subuser::factory()->create(['server_id' => $server2->id, 'user_id' => $internal->id]);
        Subuser::factory()->create(['server_id' => $server3->id, 'user_id' => $internal->id]);

        // This route is acceptable since they're accessing a subuser on their own server.
        $this->actingAs($user)->json($method, $this->link($server1, '/users/' . $internal->uuid))->assertStatus($method === 'POST' ? 422 : ($method === 'DELETE' ? 204 : 200));

        // This route can be revealed since the subuser belongs to the correct server, but
        // errors out with a 403 since $user does not have the right permissions for this.
        $this->actingAs($user)->json($method, $this->link($server2, '/users/' . $internal->uuid))->assertForbidden();
        $this->actingAs($user)->json($method, $this->link($server3, '/users/' . $internal->uuid))->assertNotFound();

        if ($method === 'DELETE') {
            Bus::assertDispatchedTimes(function (RevokeSftpAccessJob $job) use ($server1, $internal) {
                return $job->user === $internal->uuid && $job->target->is($server1);
            });
        } else {
            Bus::assertNotDispatched(RevokeSftpAccessJob::class);
        }
    }

    public static function methodDataProvider(): array
    {
        return [['GET'], ['POST'], ['DELETE']];
    }
}
