<?php

namespace App\Tests\Unit\Events\Backup;

use App\Events\Backup\BackupCompleted;
use App\Models\Backup;
use App\Models\Server;
use App\Models\User;
use App\Tests\TestCase;

class BackupCompletedTest extends TestCase
{
    /**
     * Test that the event can be instantiated with required parameters.
     */
    public function test_event_can_be_instantiated(): void
    {
        $backup = Backup::factory()->make();
        $server = Server::factory()->make();
        $user = User::factory()->make();

        $event = new BackupCompleted($backup, $server, $user);

        $this->assertInstanceOf(BackupCompleted::class, $event);
    }

    /**
     * Test that the event properties are accessible.
     */
    public function test_event_properties_are_accessible(): void
    {
        $backup = Backup::factory()->make();
        $server = Server::factory()->make();
        $user = User::factory()->make();

        $event = new BackupCompleted($backup, $server, $user);

        $this->assertSame($backup, $event->backup);
        $this->assertSame($server, $event->server);
        $this->assertSame($user, $event->owner);
    }

    /**
     * Test that the event uses SerializesModels trait.
     */
    public function test_event_uses_serializes_models_trait(): void
    {
        $traits = class_uses(BackupCompleted::class);

        $this->assertContains('Illuminate\Queue\SerializesModels', $traits);
    }
}
