<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Users\UserUpdateService;
use Illuminate\Http\Request;

class OAuthController extends Controller
{
    /**
     * OAuthController constructor.
     */
    public function __construct(
        private AuthManager $auth,
        private UserUpdateService $updateService
    ) {
    }

    /**
     * Redirect user to the OAuth provider
     */
    protected function redirect(Request $request): RedirectResponse
    {
        $driver = $request->get('driver');

        return Socialite::with($driver)->redirect();
    }

    /**
     * Callback from OAuth provider.
     */
    protected function callback(Request $request, string $driver): RedirectResponse
    {
        $oauthUser = Socialite::driver($driver)->user();

        // User is already logged in and wants to link a new OAuth Provider
        if ($request->user() != null) {
            $oauth = json_decode($request->user()->oauth, true);
            $oauth[$driver] = $oauthUser->getId();

            $this->updateService->handle($request->user(), ['oauth' => json_encode($oauth)]);

            return redirect()->route('account');
        }

        try {
            $user = User::query()->whereJsonContains('oauth->'. $driver, $oauthUser->getId())->firstOrFail();
        } catch (\Exception $e) {
            // No user found - redirect to normal login
            return redirect()->route('auth.login');
        }

        $this->auth->guard()->login($user, true);

        return redirect('/');
    }
}
