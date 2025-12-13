<?php

namespace App\Tests\Integration\Api\Client\Server\Backup;

use App\Enums\SubuserPermission;
use App\Events\ActivityLogged;
use App\Models\Backup;
use App\Repositories\Daemon\DaemonBackupRepository;
use App\Tests\Integration\Api\Client\ClientApiIntegrationTestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;

class DeleteBackupTest extends ClientApiIntegrationTestCase
{
    private MockInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->mock(DaemonBackupRepository::class);
    }

    public function test_user_without_permission_cannot_delete_backup(): void
    {
        [$user, $server] = $this->generateTestAccount([SubuserPermission::BackupCreate]);

        $backup = Backup::factory()->create(['server_id' => $server->id]);

        $this->actingAs($user)->deleteJson($this->link($backup))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Tests that a backup can be deleted for a server and that it is properly updated
     * in the database. Once deleted there should also be a corresponding record in the
     * activity logs table for this API call.
     */
    public function test_backup_can_be_deleted(): void
    {
        Event::fake([ActivityLogged::class]);

        [$user, $server] = $this->generateTestAccount([SubuserPermission::BackupDelete]);

        /** @var \App\Models\Backup $backup */
        $backup = Backup::factory()->create(['server_id' => $server->id]);

        $this->repository->expects('setServer->delete')->with(
            \Mockery::on(function ($value) use ($backup) {
                return $value instanceof Backup && $value->uuid === $backup->uuid;
            })
        )->andReturn(new Response());

        $this->actingAs($user)->deleteJson($this->link($backup))->assertStatus(Response::HTTP_NO_CONTENT);

        $backup->refresh();
        $this->assertSoftDeleted($backup);

        $this->assertActivityFor('server:backup.delete', $user, [$backup, $backup->server]);

        $this->actingAs($user)->deleteJson($this->link($backup))->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
