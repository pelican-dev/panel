<?php

namespace App\Policies\Server;

trait DefaultPolicies
{
    /**
     * This is a horrendous hack to avoid Laravel's "smart" behavior that does
     * not call the before() function if there isn't a function matching the
     * policy permission.
     */
    public function __call(string $name, mixed $arguments): void
    {
        // do nothing
    }
}
