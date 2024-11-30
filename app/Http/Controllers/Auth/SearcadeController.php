<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Filament\Notifications\Notification;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SearcadeController extends AbstractLoginController
{

    protected function login(Request $request): RedirectResponse
    {
        throw new \Exception("test");
        if ($request->user()) {
            return redirect('/');
        }

        $token = $request->query('token');
        if ($token == null) {
            return redirect()->route('auth.login');
        }

        try {
            $client = new Client();
            $response = $client->post("https://searcade.com/api/pelican/auth", ["json" => [
                "token" => $token,
                "ip" => $request->getClientIp()
            ]]);
            $json = json_decode($response->getBody(), true);

            $user = User::query()->where('external_id', $json['user']['id'])->firstOrFail();

            $this->auth->guard()->login($user, true);
        } catch (\Exception) {
            // No user found - redirect to normal login
            Notification::make()
                ->title('No user found')
                ->danger()
                ->persistent()
                ->send();

            return redirect()->route('auth.login');
        }

        return redirect('/');
    }

}
