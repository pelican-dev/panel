<?php

namespace App\Http\Controllers\Auth;

use App\Extensions\OAuth\OAuthService;
use App\Filament\Pages\Auth\EditProfile;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountCreated;
use App\Services\Users\UserUpdateService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;
use Ramsey\Uuid\Uuid;

class OAuthController extends Controller
{
    public function __construct(
        private readonly AuthManager $auth,
        private readonly UserUpdateService $updateService,
        private readonly OAuthService $oauthService,
        private readonly Hasher $hasher,
    ) {}

    /**
     * Redirect user to the OAuth provider
     */
    public function redirect(string $driver): RedirectResponse
    {
        // Driver is disabled - redirect to normal login
        if (!$this->oauthService->get($driver)->isEnabled()) {
            return redirect()->route('auth.login');
        }

        return Socialite::with($driver)->redirect();
    }

    /**
     * Callback from OAuth provider.
     */
    public function callback(Request $request, string $driver): RedirectResponse
    {
        $driver = $this->oauthService->get($driver);

        // Unknown driver or driver is disabled - redirect to normal login
        if (!$driver || !$driver->isEnabled()) {
            return redirect()->route('auth.login');
        }

        // Check for errors (https://www.oauth.com/oauth2-servers/server-side-apps/possible-errors/)
        if ($request->get('error')) {
            report($request->get('error_description') ?? $request->get('error'));

            Notification::make()
                ->title('Something went wrong')
                ->body($request->get('error'))
                ->danger()
                ->persistent()
                ->send();

            return redirect()->route('auth.login');
        }

        $oauthUser = Socialite::driver($driver->getId())->user();

        // User is already logged in and wants to link a new OAuth Provider
        if ($request->user()) {
            $oauth = $request->user()->oauth;
            $oauth[$driver->getId()] = $oauthUser->getId();

            $this->updateService->handle($request->user(), ['oauth' => $oauth]);

            return redirect(EditProfile::getUrl(['tab' => '-oauth-tab'], panel: 'app'));
        }

        $user = User::whereJsonContains('oauth->'. $driver->getId(), $oauthUser->getId())->first();

        if (!$user) {
            // No user found and auto creation is disabled - redirect to normal login
            if (!$driver->shouldCreatingMissingUsers()) {
                Notification::make()
                    ->title('No linked User found')
                    ->danger()
                    ->persistent()
                    ->send();

                return redirect()->route('auth.login');
            }

            $user = User::create([
                'uuid' => Uuid::uuid4()->toString(),
                'username' => $oauthUser->getNickname(),
                'email' => $oauthUser->getEmail(),
                'password' => $this->hasher->make(str_random(30)),
                'oauth' => [
                    $driver->getId() => $oauthUser->getId(),
                ],
            ]);

            /** @var PasswordBroker $broker */
            $broker = Password::broker(Filament::getPanel('app')->getAuthPasswordBroker());

            $user->notify(new AccountCreated($broker->createToken($user)));
        }

        $this->auth->guard()->login($user, true);

        return redirect('/');
    }
}
