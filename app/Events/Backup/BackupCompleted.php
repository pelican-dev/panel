<?php

namespace App\Events\Backup;

use App\Events\Event;
use App\Models\Backup;
use App\Models\Server;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class BackupCompleted extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Backup $backup, public Server $server, public User $owner) {}
}
