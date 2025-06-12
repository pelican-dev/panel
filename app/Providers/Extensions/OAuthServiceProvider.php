<?php

namespace App\Providers\Extensions;

use App\Extensions\OAuth\OAuthService;
use App\Extensions\OAuth\Schemas\AuthentikSchema;
use App\Extensions\OAuth\Schemas\CommonSchema;
use App\Extensions\OAuth\Schemas\DiscordSchema;
use App\Extensions\OAuth\Schemas\GithubSchema;
use App\Extensions\OAuth\Schemas\GitlabSchema;
use App\Extensions\OAuth\Schemas\SteamSchema;
use Illuminate\Support\ServiceProvider;

class OAuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(OAuthService::class, function ($app) {
            $service = new OAuthService();

            // Default OAuth providers included with Socialite
            $service->register(new CommonSchema('facebook', icon: 'tabler-brand-facebook-f', hexColor: '#1877f2'));
            $service->register(new CommonSchema('x', icon: 'tabler-brand-x-f', hexColor: '#1da1f2'));
            $service->register(new CommonSchema('linkedin', icon: 'tabler-brand-linkedin-f', hexColor: '#0a66c2'));
            $service->register(new CommonSchema('google', icon: 'tabler-brand-google-f', hexColor: '#4285f4'));
            $service->register(new GithubSchema());
            $service->register(new GitlabSchema());
            $service->register(new CommonSchema('bitbucket', icon: 'tabler-brand-bitbucket-f', hexColor: '#205081'));
            $service->register(new CommonSchema('slack', icon: 'tabler-brand-slack', hexColor: '#6ecadc'));

            // Additional OAuth providers from socialiteproviders.com
            $service->register(new AuthentikSchema());
            $service->register(new DiscordSchema());
            $service->register(new SteamSchema());

            return $service;
        });
    }
}
