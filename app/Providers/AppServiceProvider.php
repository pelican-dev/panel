<?php

namespace App\Providers;

use App\Extensions\Themes\Theme;
use App\Models;
use App\Models\ApiKey;
use App\Models\Node;
use App\Services\Helpers\SoftwareVersionService;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $versionData = app(SoftwareVersionService::class)->versionData();
        View::share('appVersion', $versionData['version'] ?? 'undefined');
        View::share('appIsGit', $versionData['is_git'] ?? false);

        Paginator::useBootstrap();

        // If the APP_URL value is set with https:// make sure we force it here. Theoretically
        // this should just work with the proxy logic, but there are a lot of cases where it
        // doesn't, and it triggers a lot of support requests, so lets just head it off here.
        if (Str::startsWith(config('app.url') ?? '', 'https://')) {
            URL::forceScheme('https');
        }

        Relation::enforceMorphMap([
            'allocation' => Models\Allocation::class,
            'api_key' => Models\ApiKey::class,
            'backup' => Models\Backup::class,
            'database' => Models\Database::class,
            'egg' => Models\Egg::class,
            'egg_variable' => Models\EggVariable::class,
            'schedule' => Models\Schedule::class,
            'server' => Models\Server::class,
            'ssh_key' => Models\UserSSHKey::class,
            'task' => Models\Task::class,
            'user' => Models\User::class,
        ]);

        Http::macro(
            'daemon',
            fn (Node $node, array $headers = []) => Http::acceptJson()
                ->asJson()
                ->withToken($node->daemon_token)
                ->withHeaders($headers)
                ->withOptions(['verify' => (bool) app()->environment('production')])
                ->timeout(config('panel.guzzle.timeout'))
                ->connectTimeout(config('panel.guzzle.connect_timeout'))
                ->baseUrl($node->getConnectionAddress())
        );

        $this->bootAuth();
        $this->bootBroadcast();

        $bearerTokens = fn (OpenApi $openApi) => $openApi->secure(SecurityScheme::http('bearer'));
        Gate::define('viewApiDocs', fn () => true);
        Scramble::registerApi('application', ['api_path' => 'api/application', 'info' => ['version' => '1.0']]);
        Scramble::registerApi('client', ['api_path' => 'api/client', 'info' => ['version' => '1.0']])->afterOpenApiGenerated($bearerTokens);
        Scramble::registerApi('remote', ['api_path' => 'api/remote', 'info' => ['version' => '1.0']])->afterOpenApiGenerated($bearerTokens);

        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('discord', \SocialiteProviders\Discord\Provider::class);
        });
    }

    /**
     * Register application service providers.
     */
    public function register(): void
    {
        // Only load the settings service provider if the environment is configured to allow it.
        if (!config('panel.load_environment_only', false) && $this->app->environment() !== 'testing') {
            $this->app->register(SettingsServiceProvider::class);
        }

        $this->app->singleton('extensions.themes', function () {
            return new Theme();
        });

        Scramble::extendOpenApi(fn (OpenApi $openApi) => $openApi->secure(SecurityScheme::http('bearer')));
        Scramble::ignoreDefaultRoutes();
    }

    public function bootAuth(): void
    {
        Sanctum::usePersonalAccessTokenModel(ApiKey::class);
    }

    public function bootBroadcast(): void
    {
        Broadcast::routes();

        /*
         * Authenticate the user's personal channel...
         */
        Broadcast::channel('App.User.*', function ($user, $userId) {
            return (int) $user->id === (int) $userId;
        });
    }
}
