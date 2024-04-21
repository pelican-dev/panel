<?php

namespace App\Events;

interface ShouldDispatchWebhooks
{
    public function getPayload(): array;
}
