<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exceptions\Http\TwoFactorAuthRequiredException;
use App\Filament\Pages\Auth\EditProfile;
use App\Livewire\AlertBanner;
use App\Models\User;

class RequireTwoFactorAuthentication
{
    public const LEVEL_NONE = 0;

    public const LEVEL_ADMIN = 1;

    public const LEVEL_ALL = 2;

    /**
     * Check the user state on the incoming request to determine if they should be allowed to
     * proceed or not. This checks if the Panel is configured to require 2FA on an account in
     * order to perform actions. If so, we check the level at which it is required (all users
     * or just admins) and then check if the user has enabled it for their account.
     *
     * @throws \App\Exceptions\Http\TwoFactorAuthRequiredException
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        /** @var ?User $user */
        $user = $request->user();

        $uri = rtrim($request->getRequestUri(), '/') . '/';
        $current = $request->route()->getName();

        if (!$user || Str::startsWith($uri, ['/auth/', '/profile']) || Str::startsWith($current, ['auth.', 'account.', 'filament.app.auth.'])) {
            return $next($request);
        }

        $level = (int) config('panel.auth.2fa_required');

        if ($level === self::LEVEL_NONE || $user->use_totp) {
            // If this setting is not configured, or the user is already using 2FA then we can just send them right through, nothing else needs to be checked.
            return $next($request);
        } elseif ($level === self::LEVEL_ADMIN && !$user->isAdmin()) {
            // If the level is set as admin and the user is not an admin, pass them through as well.
            return $next($request);
        }

        // For API calls return an exception which gets rendered nicely in the API response...
        if ($request->isJson() || Str::startsWith($uri, '/api/')) {
            throw new TwoFactorAuthRequiredException();
        }

        // ... otherwise display banner and redirect to profile
        AlertBanner::make('2fa_must_be_enabled')
            ->body(trans('auth.2fa_must_be_enabled'))
            ->warning()
            ->send();

        return redirect(EditProfile::getUrl(['tab' => '-2fa-tab'], panel: 'app'));
    }
}
