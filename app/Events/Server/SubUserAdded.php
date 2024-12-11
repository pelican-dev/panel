<?php

namespace App\Events\Server;

use App\Events\Event;
use App\Models\Subuser;
use Illuminate\Queue\SerializesModels;

class SubUserAdded extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Subuser $subuser) {}
}
