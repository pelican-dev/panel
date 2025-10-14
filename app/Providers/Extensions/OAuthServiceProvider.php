<?php

namespace App\Providers\Extensions;

use App\Extensions\OAuth\OAuthService;
use App\Extensions\OAuth\Schemas\AuthentikSchema;
use App\Extensions\OAuth\Schemas\BitbucketSchema;
use App\Extensions\OAuth\Schemas\DiscordSchema;
use App\Extensions\OAuth\Schemas\FacebookSchema;
use App\Extensions\OAuth\Schemas\GithubSchema;
use App\Extensions\OAuth\Schemas\GitlabSchema;
use App\Extensions\OAuth\Schemas\GoogleSchema;
use App\Extensions\OAuth\Schemas\LinkedinSchema;
use App\Extensions\OAuth\Schemas\SlackSchema;
use App\Extensions\OAuth\Schemas\SteamSchema;
use App\Extensions\OAuth\Schemas\XSchema;
use Illuminate\Support\ServiceProvider;

class OAuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(OAuthService::class, function ($app) {
            $service = new OAuthService();

            // Default OAuth providers included with Socialite
            $service->register(new FacebookSchema());
            $service->register(new XSchema());
            $service->register(new LinkedinSchema());
            $service->register(new GoogleSchema());
            $service->register(new GithubSchema());
            $service->register(new GitlabSchema());
            $service->register(new BitbucketSchema());
            $service->register(new SlackSchema());

            // Additional OAuth providers from socialiteproviders.com
            $service->register(new AuthentikSchema());
            $service->register(new DiscordSchema());
            $service->register(new SteamSchema());

            return $service;
        });
    }
}
