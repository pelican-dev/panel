<?php

namespace App\Events\Auth;

use App\Models\User;
use App\Events\Event;

class ProvidedAuthenticationToken extends Event
{
    public function __construct(public User $user, public bool $recovery = false) {}
}
