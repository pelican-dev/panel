<?php

namespace App\Events\Auth;

use App\Events\Event;
use App\Models\User;

class ProvidedAuthenticationToken extends Event
{
    public function __construct(public User $user, public bool $recovery = false) {}
}
