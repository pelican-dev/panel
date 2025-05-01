<?php

namespace App\Providers\Extensions;

use App\Extensions\OAuth\OAuthProvider;
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
        $this->app->singleton(OAuthProvider::class, function ($app) {
            $provider = new OAuthProvider();
            // Default OAuth providers included with Socialite
            $provider->register(new CommonSchema('facebook', null, 'tabler-brand-facebook-f', '#1877f2'));
            $provider->register(new CommonSchema('x', null, 'tabler-brand-x-f', '#1da1f2'));
            $provider->register(new CommonSchema('linkedin', null, 'tabler-brand-linkedin-f', '#0a66c2'));
            $provider->register(new CommonSchema('google', null, 'tabler-brand-google-f', '#4285f4'));
            $provider->register(new GithubSchema());
            $provider->register(new GitlabSchema());
            $provider->register(new CommonSchema('bitbucket', null, 'tabler-brand-bitbucket-f', '#205081'));
            $provider->register(new CommonSchema('slack', null, 'tabler-brand-slack', '#6ecadc'));

            // Additional OAuth providers from socialiteproviders.com
            $provider->register(new AuthentikSchema());
            $provider->register(new DiscordSchema());
            $provider->register(new SteamSchema());

            return $provider;
        });
    }
}
