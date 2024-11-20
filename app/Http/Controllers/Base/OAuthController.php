<?php

namespace App\Http\Controllers\Base;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Services\Users\UserUpdateService;

class OAuthController extends Controller
{
    /**
     * OAuthController constructor.
     */
    public function __construct(
        private UserUpdateService $updateService
    ) {
    }

    /**
     * Link a new OAuth
     */
    protected function link(string $driver): RedirectResponse
    {
        // Driver is disabled - redirect to account page
        if (!config("auth.oauth.$driver.enabled")) {
            // TODO: replace with profile route once new client area is merged
            return redirect()->route('account');
        }

        return Socialite::with($driver)->redirect();
    }

    /**
     * Remove a OAuth link
     */
    protected function unlink(Request $request, string $driver): RedirectResponse
    {
        $oauth = $request->user()->oauth;
        unset($oauth[$driver]);

        $this->updateService->handle($request->user(), ['oauth' => $oauth]);

        // TODO: replace with profile route once new client area is merged
        return redirect()->route('account');
    }
}
