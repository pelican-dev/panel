<?php

namespace App\Listeners\Auth;

use App\Facades\Activity;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;

class AuthenticationListener
{
    private const PROTECTED_FIELDS = [
        'password', 'token', 'secret',
    ];

    /**
     * Handles an authentication event by logging the user and information about
     * the request.
     */
    public function handle(Failed|Login $event): void
    {
        $activity = Activity::withRequestMetadata();

        if ($event->user) {
            $activity = $activity->subject($event->user);
        }

        if ($event instanceof Failed) {
            foreach ($event->credentials as $key => $value) {
                if (!in_array($key, self::PROTECTED_FIELDS, true)) {
                    $activity = $activity->property($key, $value);
                }
            }
        }

        $activity->event($event instanceof Failed ? 'auth:fail' : 'auth:success')->log();
    }
}
