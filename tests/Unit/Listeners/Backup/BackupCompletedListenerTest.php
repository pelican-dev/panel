<?php

namespace App\Tests\Unit\Listeners\Backup;

use App\Events\Backup\BackupCompleted;
use App\Listeners\Backup\BackupCompletedListener;
use App\Models\Backup;
use App\Models\Server;
use App\Models\User;
use App\Notifications\BackupCompleted as BackupCompletedNotification;
use App\Tests\TestCase;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Mockery as m;

class BackupCompletedListenerTest extends TestCase
{
    /**
     * Test that the listener can be instantiated.
     */
    public function test_listener_can_be_instantiated(): void
    {
        $listener = new BackupCompletedListener();

        $this->assertInstanceOf(BackupCompletedListener::class, $listener);
    }

    /**
     * Test that the listener sends a panel notification to the user.
     */
    public function test_listener_sends_panel_notification(): void
    {
        // Create test models
        $user = User::factory()->make(['id' => 1, 'language' => 'en']);
        $server = Server::factory()->make(['id' => 1, 'name' => 'Test Server']);
        $backup = Backup::factory()->make([
            'id' => 1,
            'name' => 'test-backup.tar.gz',
            'bytes' => 1024000,
        ]);

        // Set up relationships
        $backup->setRelation('server', $server);
        $user->setRelation('language', 'en');

        // Create event
        $event = new BackupCompleted($backup, $server, $user);

        // Mock Notification facade
        Notification::shouldReceive('make')
            ->once()
            ->andReturnSelf();
        Notification::shouldReceive('success')
            ->once()
            ->andReturnSelf();
        Notification::shouldReceive('title')
            ->once()
            ->with(m::type('string'))
            ->andReturnSelf();
        Notification::shouldReceive('body')
            ->once()
            ->with(m::type('string'))
            ->andReturnSelf();
        Notification::shouldReceive('actions')
            ->once()
            ->with(m::type('array'))
            ->andReturnSelf();
        Notification::shouldReceive('sendToDatabase')
            ->once()
            ->with($user)
            ->andReturnNull();

        // Mock config to disable email notification for this test
        Config::shouldReceive('get')
            ->with('panel.email.send_backup_notification', true)
            ->once()
            ->andReturn(false);

        // Handle the event
        $listener = new BackupCompletedListener();
        $listener->handle($event);
    }

    /**
     * Test that the listener sends email notification when config is enabled.
     */
    public function test_listener_sends_email_when_config_enabled(): void
    {
        // Create test models with notification spy
        $user = m::mock(User::class)->makePartial();
        $user->shouldReceive('getAttribute')->with('language')->andReturn('en');
        $user->shouldReceive('loadMissing')->with('language')->andReturnSelf();

        $server = Server::factory()->make(['id' => 1, 'name' => 'Test Server']);
        $backup = Backup::factory()->make([
            'id' => 1,
            'name' => 'test-backup.tar.gz',
            'bytes' => 1024000,
        ]);

        // Set up relationships
        $backup->setRelation('server', $server);
        $backup->shouldReceive('loadMissing')->with('server')->andReturnSelf();

        // Create event
        $event = new BackupCompleted($backup, $server, $user);

        // Mock Notification facade
        Notification::shouldReceive('make')->andReturnSelf();
        Notification::shouldReceive('success')->andReturnSelf();
        Notification::shouldReceive('title')->andReturnSelf();
        Notification::shouldReceive('body')->andReturnSelf();
        Notification::shouldReceive('actions')->andReturnSelf();
        Notification::shouldReceive('sendToDatabase')->andReturnNull();

        // Mock config to enable email notification
        Config::shouldReceive('get')
            ->with('panel.email.send_backup_notification', true)
            ->once()
            ->andReturn(true);

        // Expect notify to be called with BackupCompletedNotification
        $user->shouldReceive('notify')
            ->once()
            ->with(m::type(BackupCompletedNotification::class))
            ->andReturnNull();

        // Handle the event
        $listener = new BackupCompletedListener();
        $listener->handle($event);
    }

    /**
     * Test that the listener does not send email notification when config is disabled.
     */
    public function test_listener_does_not_send_email_when_config_disabled(): void
    {
        // Create test models
        $user = m::mock(User::class)->makePartial();
        $user->shouldReceive('getAttribute')->with('language')->andReturn('en');
        $user->shouldReceive('loadMissing')->with('language')->andReturnSelf();

        $server = Server::factory()->make(['id' => 1, 'name' => 'Test Server']);
        $backup = Backup::factory()->make([
            'id' => 1,
            'name' => 'test-backup.tar.gz',
            'bytes' => 1024000,
        ]);

        // Set up relationships
        $backup->setRelation('server', $server);
        $backup->shouldReceive('loadMissing')->with('server')->andReturnSelf();

        // Create event
        $event = new BackupCompleted($backup, $server, $user);

        // Mock Notification facade
        Notification::shouldReceive('make')->andReturnSelf();
        Notification::shouldReceive('success')->andReturnSelf();
        Notification::shouldReceive('title')->andReturnSelf();
        Notification::shouldReceive('body')->andReturnSelf();
        Notification::shouldReceive('actions')->andReturnSelf();
        Notification::shouldReceive('sendToDatabase')->andReturnNull();

        // Mock config to disable email notification
        Config::shouldReceive('get')
            ->with('panel.email.send_backup_notification', true)
            ->once()
            ->andReturn(false);

        // Expect notify to NOT be called
        $user->shouldNotReceive('notify');

        // Handle the event
        $listener = new BackupCompletedListener();
        $listener->handle($event);
    }

    /**
     * Test that the listener handles null language gracefully.
     */
    public function test_listener_handles_null_language(): void
    {
        // Create test models with null language
        $user = User::factory()->make(['id' => 1, 'language' => null]);
        $server = Server::factory()->make(['id' => 1, 'name' => 'Test Server']);
        $backup = Backup::factory()->make([
            'id' => 1,
            'name' => 'test-backup.tar.gz',
            'bytes' => 1024000,
        ]);

        // Set up relationships
        $backup->setRelation('server', $server);
        $user->setRelation('language', null);

        // Create event
        $event = new BackupCompleted($backup, $server, $user);

        // Mock Notification facade - should still work with default locale
        Notification::shouldReceive('make')->once()->andReturnSelf();
        Notification::shouldReceive('success')->once()->andReturnSelf();
        Notification::shouldReceive('title')->once()->andReturnSelf();
        Notification::shouldReceive('body')->once()->andReturnSelf();
        Notification::shouldReceive('actions')->once()->andReturnSelf();
        Notification::shouldReceive('sendToDatabase')->once()->andReturnNull();

        // Mock config
        Config::shouldReceive('get')
            ->with('panel.email.send_backup_notification', true)
            ->once()
            ->andReturn(false);

        // Handle the event - should not throw exception
        $listener = new BackupCompletedListener();
        $listener->handle($event);

        // If we get here without exception, the test passes
        $this->assertTrue(true);
    }

    /**
     * Test that the listener loads missing relationships.
     */
    public function test_listener_loads_missing_relationships(): void
    {
        // Create mocked models to verify loadMissing calls
        $user = m::mock(User::class)->makePartial();
        $server = Server::factory()->make(['id' => 1, 'name' => 'Test Server']);
        $backup = m::mock(Backup::class)->makePartial();

        // Expect loadMissing to be called
        $backup->shouldReceive('loadMissing')
            ->once()
            ->with('server')
            ->andReturnSelf();

        $user->shouldReceive('loadMissing')
            ->once()
            ->with('language')
            ->andReturnSelf();

        $user->shouldReceive('getAttribute')->with('language')->andReturn('en');
        $backup->shouldReceive('getAttribute')->with('server')->andReturn($server);
        $backup->shouldReceive('getAttribute')->with('name')->andReturn('test-backup.tar.gz');

        // Create event
        $event = new BackupCompleted($backup, $server, $user);

        // Mock Notification facade
        Notification::shouldReceive('make')->andReturnSelf();
        Notification::shouldReceive('success')->andReturnSelf();
        Notification::shouldReceive('title')->andReturnSelf();
        Notification::shouldReceive('body')->andReturnSelf();
        Notification::shouldReceive('actions')->andReturnSelf();
        Notification::shouldReceive('sendToDatabase')->andReturnNull();

        // Mock config
        Config::shouldReceive('get')
            ->with('panel.email.send_backup_notification', true)
            ->once()
            ->andReturn(false);

        // Handle the event
        $listener = new BackupCompletedListener();
        $listener->handle($event);

        // Mockery will automatically verify the expectations
    }
}
