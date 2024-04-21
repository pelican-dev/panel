<?php

namespace App\Events\User;

use App\Events\ShouldDispatchWebhooks;
use App\Models\User;
use App\Events\Event;
use App\Traits\Services\HasWebhookPayload;
use Illuminate\Queue\SerializesModels;

class Deleted extends Event implements ShouldDispatchWebhooks
{
    use HasWebhookPayload;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public User $user)
    {
    }
}
