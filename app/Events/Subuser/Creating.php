<?php

namespace App\Events\Subuser;

use App\Events\Event;
use App\Events\ShouldDispatchWebhooks;
use App\Models\Subuser;
use App\Traits\Services\HasWebhookPayload;
use Illuminate\Queue\SerializesModels;

class Creating extends Event implements ShouldDispatchWebhooks
{
    use SerializesModels;
    use HasWebhookPayload;

    /**
     * Create a new event instance.
     */
    public function __construct(public Subuser $subuser)
    {
    }
}
