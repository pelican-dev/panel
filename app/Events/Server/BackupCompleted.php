<?php

namespace App\Events\Server;

use App\Events\Event;
use App\Models\Backup;
use Illuminate\Queue\SerializesModels;

class BackupCompleted extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Backup $backup) {}
}
