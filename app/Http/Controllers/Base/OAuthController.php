<?php

namespace App\Http\Controllers\Base;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Services\Users\UserUpdateService;
use Illuminate\Http\Response;

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
            return redirect()->route('account');
        }

        return Socialite::with($driver)->redirect();
    }

    /**
     * Remove a OAuth link
     */
    protected function unlink(Request $request, string $driver): Response
    {
        $oauth = $request->user()->oauth;
        unset($oauth[$driver]);

        $this->updateService->handle($request->user(), ['oauth' => $oauth]);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
