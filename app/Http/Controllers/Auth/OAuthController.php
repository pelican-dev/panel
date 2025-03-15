<?php

namespace App\Http\Controllers\Auth;

use App\Extensions\OAuth\Providers\OAuthProvider;
use App\Filament\Pages\Auth\EditProfile;
use Filament\Notifications\Notification;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Users\UserUpdateService;
use Exception;
use Illuminate\Http\Request;

class OAuthController extends Controller
{
    public function __construct(
        private readonly AuthManager $auth,
        private readonly UserUpdateService $updateService
    ) {}

    /**
     * Redirect user to the OAuth provider
     */
    public function redirect(string $driver): RedirectResponse
    {
        // Driver is disabled - redirect to normal login
        if (!OAuthProvider::get($driver)->isEnabled()) {
            return redirect()->route('auth.login');
        }

        return Socialite::with($driver)->redirect();
    }

    /**
     * Callback from OAuth provider.
     */
    public function callback(Request $request, string $driver): RedirectResponse
    {
        // Driver is disabled - redirect to normal login
        if (!OAuthProvider::get($driver)->isEnabled()) {
            return redirect()->route('auth.login');
        }

        $oauthUser = Socialite::driver($driver)->user();

        // User is already logged in and wants to link a new OAuth Provider
        if ($request->user()) {
            $oauth = $request->user()->oauth;
            $oauth[$driver] = $oauthUser->getId();

            $this->updateService->handle($request->user(), ['oauth' => $oauth]);

            return redirect(EditProfile::getUrl(['tab' => '-oauth-tab']));
        }

        try {
            $user = User::query()->whereJsonContains('oauth->'. $driver, $oauthUser->getId())->firstOrFail();

            $this->auth->guard()->login($user, true);
        } catch (Exception) {
            // No user found - redirect to normal login
            Notification::make()
                ->title('No linked User found')
                ->danger()
                ->persistent()
                ->send();

            return redirect()->route('auth.login');
        }

        return redirect('/');
    }
}
