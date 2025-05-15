<?php

namespace App\Listeners\Auth;

use App\Facades\Activity;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetListener
{
    public function handle(PasswordReset $event): void
    {
        Activity::event('auth:password-reset')
            ->withRequestMetadata()
            ->subject($event->user)
            ->log();
    }
}
