<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Notifications\Notification;

class CheckBanned
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_suspended) {
            $message = trans('server/users.suspend.suspend_message');
            Notification::make()
                ->title(trans('strings.suspended'))
                ->danger()
                ->seconds(7)
                ->body($message)
                ->send();
            auth()->logout();

            return redirect('panel/login');
        }

        return $next($request);
    }
}
