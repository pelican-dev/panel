<?php

namespace App\Traits\Services;

trait HasWebhookPayload
{
    public function getPayload(): array
    {
        if (method_exists($this, '__serialize')) {
            return $this->__serialize();
        }

        return [];
    }

}
