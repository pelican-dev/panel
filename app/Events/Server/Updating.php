<?php

namespace App\Events\Server;

use App\Events\Event;
use App\Models\Server;
use Illuminate\Queue\SerializesModels;

class Updating extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Server $server)
    {
    }
}
