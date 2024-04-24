<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Notifications\Notification;

class CheckBanned
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_banned) {
            $message = trans('server/ban.ban_message');
            Notification::make()
                ->title(trans('string.suspended'))
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
