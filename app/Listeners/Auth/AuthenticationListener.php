<?php

namespace App\Listeners\Auth;

use App\Facades\Activity;
use App\Events\Auth\DirectLogin;
use Illuminate\Auth\Events\Failed;

class AuthenticationListener
{
    /**
     * Handles an authentication event by logging the user and information about
     * the request.
     */
    public function handle(Failed|DirectLogin $event): void
    {
        $activity = Activity::withRequestMetadata();
        if ($event->user) {
            $activity = $activity->subject($event->user);
        }

        if ($event instanceof Failed) {
            foreach ($event->credentials as $key => $value) {
                $activity = $activity->property($key, $value);
            }
        }

        $activity->event($event instanceof Failed ? 'auth:fail' : 'auth:success')->log();
    }
}
