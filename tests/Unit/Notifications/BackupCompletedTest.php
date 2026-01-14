<?php

namespace App\Tests\Unit\Notifications;

use App\Models\Backup;
use App\Models\Server;
use App\Models\User;
use App\Notifications\BackupCompleted;
use App\Tests\TestCase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BackupCompletedTest extends TestCase
{
    /**
     * Test that the notification can be instantiated.
     */
    public function test_notification_can_be_instantiated(): void
    {
        $backup = Backup::factory()->make();

        $notification = new BackupCompleted($backup);

        $this->assertInstanceOf(BackupCompleted::class, $notification);
    }

    /**
     * Test that the notification properties are accessible.
     */
    public function test_notification_properties_are_accessible(): void
    {
        $backup = Backup::factory()->make();

        $notification = new BackupCompleted($backup);

        $this->assertSame($backup, $notification->backup);
    }

    /**
     * Test that the notification implements ShouldQueue.
     */
    public function test_notification_implements_should_queue(): void
    {
        $this->assertContains(ShouldQueue::class, class_implements(BackupCompleted::class));
    }

    /**
     * Test that the notification uses Queueable trait.
     */
    public function test_notification_uses_queueable_trait(): void
    {
        $traits = class_uses(BackupCompleted::class);

        $this->assertContains('Illuminate\Bus\Queueable', $traits);
    }

    /**
     * Test that via method returns mail channel.
     */
    public function test_via_method_returns_mail_channel(): void
    {
        $backup = Backup::factory()->make();

        $notification = new BackupCompleted($backup);

        $this->assertEquals(['mail'], $notification->via());
    }

    /**
     * Test that toMail returns a MailMessage instance.
     */
    public function test_to_mail_returns_mail_message(): void
    {
        $server = Server::factory()->make(['name' => 'Test Server']);
        $backup = Backup::factory()->make([
            'name' => 'test-backup.tar.gz',
            'bytes' => 1024000,
        ]);
        $backup->setRelation('server', $server);

        $user = User::factory()->make(['username' => 'testuser']);

        $notification = new BackupCompleted($backup);
        $mailMessage = $notification->toMail($user);

        $this->assertInstanceOf(MailMessage::class, $mailMessage);
    }

    /**
     * Test that toMail includes correct greeting.
     */
    public function test_to_mail_includes_greeting(): void
    {
        $server = Server::factory()->make(['name' => 'Test Server']);
        $backup = Backup::factory()->make([
            'name' => 'test-backup.tar.gz',
            'bytes' => 1024000,
        ]);
        $backup->setRelation('server', $server);

        $user = User::factory()->make(['username' => 'testuser']);

        $notification = new BackupCompleted($backup);
        $mailMessage = $notification->toMail($user);

        $this->assertEquals('Hello testuser.', $mailMessage->greeting);
    }

    /**
     * Test that toMail includes backup details.
     */
    public function test_to_mail_includes_backup_details(): void
    {
        $server = Server::factory()->make(['name' => 'Test Server']);
        $backup = Backup::factory()->make([
            'name' => 'test-backup.tar.gz',
            'bytes' => 1024000,
        ]);
        $backup->setRelation('server', $server);

        $user = User::factory()->make(['username' => 'testuser']);

        $notification = new BackupCompleted($backup);
        $mailMessage = $notification->toMail($user);

        // Check that the intro lines contain the expected information
        $this->assertContains('Your backup has finished and is now ready.', $mailMessage->introLines);
        $this->assertContains('Backup Name: test-backup.tar.gz', $mailMessage->introLines);
        $this->assertContains('Server Name: Test Server', $mailMessage->introLines);
        $this->assertContains('Size: ' . convert_bytes_to_readable(1024000), $mailMessage->introLines);
    }

    /**
     * Test that toMail includes action button.
     */
    public function test_to_mail_includes_action_button(): void
    {
        $server = Server::factory()->make([
            'id' => 1,
            'name' => 'Test Server',
        ]);
        $backup = Backup::factory()->make([
            'name' => 'test-backup.tar.gz',
            'bytes' => 1024000,
        ]);
        $backup->setRelation('server', $server);

        $user = User::factory()->make(['username' => 'testuser']);

        $notification = new BackupCompleted($backup);
        $mailMessage = $notification->toMail($user);

        // Check that action button exists
        $this->assertEquals('View Backups', $mailMessage->actionText);
        $this->assertNotEmpty($mailMessage->actionUrl);
    }
}
