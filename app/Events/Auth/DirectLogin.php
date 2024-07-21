<?php

namespace App\Events\Auth;

use App\Models\User;
use App\Events\Event;

class DirectLogin extends Event
{
    public function __construct(public User $user, public bool $remember) {}
}
