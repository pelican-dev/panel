<?php

namespace App\Contracts\Core;

use App\Events\Event;

interface ReceivesEvents
{
    /**
     * Handles receiving an event from the application.
     */
    public function handle(Event $notification): void;
}
