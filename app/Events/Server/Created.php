<?php

namespace App\Events\Server;

use App\Events\Event;
use App\Events\ShouldDispatchWebhooks;
use App\Models\Server;
use App\Traits\Services\HasWebhookPayload;
use Illuminate\Queue\SerializesModels;

class Created extends Event implements ShouldDispatchWebhooks
{
    use HasWebhookPayload;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Server $server)
    {
    }
}
