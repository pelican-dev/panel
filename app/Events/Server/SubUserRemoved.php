<?php

namespace App\Events\Server;

use App\Events\Event;
use App\Models\Server;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class SubUserRemoved extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Server $server, public User $user) {}
}
