<?php

namespace App\Http\Controllers\Auth;

use App\Services\Users\UserCreationService;
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
    /**
     * OAuthController constructor.
     */
    public function __construct(
        private AuthManager $auth,
        private UserUpdateService $updateService,
        private UserCreationService $creationService
    ) {
    }

    /**
     * Redirect user to the OAuth provider
     */
    protected function redirect(string $driver): RedirectResponse
    {
        return Socialite::with($driver)->redirect();
    }

    /**
     * Callback from OAuth provider.
     */
    protected function callback(Request $request, string $driver): RedirectResponse
    {
        $oauthUser = Socialite::driver($driver)->user();

        // User is already logged in and wants to link a new OAuth Provider
        if ($request->user()) {
            $oauth = $request->user()->oauth;
            $oauth[$driver] = $oauthUser->getId();

            $this->updateService->handle($request->user(), ['oauth' => $oauth]);

            return redirect()->route('account');
        }

        $user = User::query()->whereJsonContains('oauth->'. $driver, $oauthUser->getId())->first();

        // creating the user if none was found (User Provisioning)
        if (!$user && env('OAUTH_USER_PROVISIONING') == 'true') {
            $userdata = [
                'username' => $oauthUser->getId(),
                'email' => $oauthUser->getEmail(),
                'name_first' => str_split($oauthUser->getName())[0],
                'name_last' => str_split($oauthUser->getName())[1],
                'oauth' => [$driver => $oauthUser->getId()],
            ];

            $user = $this->creationService->handle($userdata);
        }else if (!$user) {
            // No user found - redirect to normal login
            return redirect()->route('auth.login');
        }

        $this->auth->guard()->login($user, true);

        return redirect('/');
    }
}
