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
            $service->register(new CommonSchema('facebook', 'tabler-brand-facebook-f', '#1877f2'));
            $service->register(new CommonSchema('x', 'tabler-brand-x-f', '#1da1f2'));
            $service->register(new CommonSchema('linkedin', 'tabler-brand-linkedin-f', '#0a66c2'));
            $service->register(new CommonSchema('google', 'tabler-brand-google-f', '#4285f4'));
            $service->register(new GithubSchema());
            $service->register(new GitlabSchema());
            $service->register(new CommonSchema('bitbucket', 'tabler-brand-bitbucket-f', '#205081'));
            $service->register(new CommonSchema('slack', 'tabler-brand-slack', '#6ecadc'));

            // Additional OAuth providers from socialiteproviders.com
            $service->register(new AuthentikSchema());
            $service->register(new DiscordSchema());
            $service->register(new SteamSchema());

            return $service;
        });
    }
}
