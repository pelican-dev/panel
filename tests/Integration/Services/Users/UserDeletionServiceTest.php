<?php

namespace App\Tests\Integration\Services\Users;

use App\Exceptions\DisplayException;
use App\Jobs\RevokeSftpAccessJob;
use App\Models\Subuser;
use App\Models\User;
use App\Tests\Integration\IntegrationTestCase;
use Illuminate\Support\Facades\Bus;

class UserDeletionServiceTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Bus::fake([RevokeSftpAccessJob::class]);
    }

    public function test_exception_returned_if_user_assigned_to_servers(): void
    {
        $server = $this->createServerModel();

        $this->expectException(DisplayException::class);
        $this->expectExceptionMessage(trans('exceptions.users.has_servers'));

        $server->user->delete();

        $this->assertModelExists($server->user);

        Bus::assertNotDispatched(RevokeSftpAccessJob::class);
    }

    public function test_user_is_deleted(): void
    {
        $user = User::factory()->create();

        $user->delete();

        $this->assertModelMissing($user);

        Bus::assertNotDispatched(RevokeSftpAccessJob::class);
    }

    public function test_user_is_deleted_and_access_revoked(): void
    {
        $user = User::factory()->create();

        $server1 = $this->createServerModel();
        $server2 = $this->createServerModel(['node_id' => $server1->node_id]);

        Subuser::factory()->for($server1)->for($user)->create();
        Subuser::factory()->for($server2)->for($user)->create();

        $user->delete();

        $this->assertModelMissing($user);

        Bus::assertDispatchedTimes(RevokeSftpAccessJob::class);
        Bus::assertDispatched(fn (RevokeSftpAccessJob $job) => $job->user === $user->uuid && $job->target->is($server1->node));
    }
}
