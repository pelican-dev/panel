<?php

namespace App\Tests\Integration\Api\Remote\Backups;

use App\Events\Backup\BackupCompleted;
use App\Models\Backup;
use App\Models\Node;
use App\Notifications\BackupCompleted as BackupCompletedNotification;
use App\Tests\Integration\IntegrationTestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

class BackupCompletionNotificationTest extends IntegrationTestCase
{
    private Node $node;

    protected function setUp(): void
    {
        parent::setUp();

        [$user, $server] = $this->generateTestAccount();
        $this->node = $server->node;
    }

    public function test_backup_completed_event_is_fired_on_successful_completion(): void
    {
        Event::fake([BackupCompleted::class]);

        [$user, $server] = $this->generateTestAccount();

        $backup = Backup::factory()->create([
            'server_id' => $server->id,
            'is_successful' => false,
            'completed_at' => null,
        ]);

        $this->withNodeAuthorization($server->node)
            ->postJson("/api/remote/backups/{$backup->uuid}", [
                'successful' => true,
                'checksum' => 'abc123',
                'checksum_type' => 'sha256',
                'size' => 1024000,
            ])
            ->assertNoContent();

        Event::assertDispatched(BackupCompleted::class, function ($event) use ($backup, $server) {
            return $event->backup->id === $backup->id
                && $event->server->id === $server->id
                && $event->owner->id === $server->user->id;
        });
    }

    public function test_event_not_fired_on_failed_backup(): void
    {
        Event::fake([BackupCompleted::class]);

        [$user, $server] = $this->generateTestAccount();

        $backup = Backup::factory()->create([
            'server_id' => $server->id,
            'is_successful' => false,
            'completed_at' => null,
        ]);

        $this->withNodeAuthorization($server->node)
            ->postJson("/api/remote/backups/{$backup->uuid}", [
                'successful' => false,
            ])
            ->assertNoContent();

        Event::assertNotDispatched(BackupCompleted::class);
    }

    public function test_panel_notification_created_on_backup_completion(): void
    {
        // Don't fake events for this test - we want the full flow
        Event::fake([]);

        [$user, $server] = $this->generateTestAccount();

        $backup = Backup::factory()->create([
            'server_id' => $server->id,
            'is_successful' => false,
            'completed_at' => null,
        ]);

        $this->withNodeAuthorization($server->node)
            ->postJson("/api/remote/backups/{$backup->uuid}", [
                'successful' => true,
                'checksum' => 'abc123',
                'checksum_type' => 'sha256',
                'size' => 1024000,
            ])
            ->assertNoContent();

        // Verify notification exists in database
        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $server->user->id,
            'type' => 'Filament\Notifications\DatabaseNotification',
        ]);
    }

    public function test_email_notification_sent_when_config_enabled(): void
    {
        Notification::fake();
        Event::fake([]);  // Clear event fakes to allow real dispatch
        Config::set('panel.email.send_backup_notification', true);

        [$user, $server] = $this->generateTestAccount();

        $backup = Backup::factory()->create([
            'server_id' => $server->id,
            'is_successful' => false,
            'completed_at' => null,
        ]);

        $this->withNodeAuthorization($server->node)
            ->postJson("/api/remote/backups/{$backup->uuid}", [
                'successful' => true,
                'checksum' => 'abc123',
                'checksum_type' => 'sha256',
                'size' => 1024000,
            ])
            ->assertNoContent();

        Notification::assertSentTo(
            [$server->user],
            BackupCompletedNotification::class,
            function ($notification, $channels) use ($backup) {
                return $notification->backup->id === $backup->id;
            }
        );
    }

    public function test_email_notification_not_sent_when_config_disabled(): void
    {
        Notification::fake();
        Event::fake([]);  // Clear event fakes to allow real dispatch
        Config::set('panel.email.send_backup_notification', false);

        [$user, $server] = $this->generateTestAccount();

        $backup = Backup::factory()->create([
            'server_id' => $server->id,
            'is_successful' => false,
            'completed_at' => null,
        ]);

        $this->withNodeAuthorization($server->node)
            ->postJson("/api/remote/backups/{$backup->uuid}", [
                'successful' => true,
                'checksum' => 'abc123',
                'checksum_type' => 'sha256',
                'size' => 1024000,
            ])
            ->assertNoContent();

        // Email notification should not be sent when config is disabled
        Notification::assertNothingSent();
    }

    public function test_backup_model_updated_correctly_on_successful_completion(): void
    {
        Event::fake([BackupCompleted::class]);

        [$user, $server] = $this->generateTestAccount();

        $backup = Backup::factory()->create([
            'server_id' => $server->id,
            'is_successful' => false,
            'completed_at' => null,
            'checksum' => null,
            'bytes' => 0,
        ]);

        $this->withNodeAuthorization($server->node)
            ->postJson("/api/remote/backups/{$backup->uuid}", [
                'successful' => true,
                'checksum' => 'abc123',
                'checksum_type' => 'sha256',
                'size' => 1024000,
            ])
            ->assertNoContent();

        $backup->refresh();

        $this->assertTrue($backup->is_successful);
        $this->assertEquals('sha256:abc123', $backup->checksum);
        $this->assertEquals(1024000, $backup->bytes);
        $this->assertNotNull($backup->completed_at);
    }

    /**
     * Sets the authorization header for node authentication.
     */
    protected function withNodeAuthorization(Node $node): self
    {
        $token = $node->daemon_token_id . '.' . $node->daemon_token;

        $this->withHeader('Authorization', 'Bearer ' . $token);

        return $this;
    }
}
