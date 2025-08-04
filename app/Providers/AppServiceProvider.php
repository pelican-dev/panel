<?php

namespace App\Providers;

use App\Checks\CacheCheck;
use App\Checks\DatabaseCheck;
use App\Checks\DebugModeCheck;
use App\Checks\EnvironmentCheck;
use App\Checks\NodeVersionsCheck;
use App\Checks\PanelVersionCheck;
use App\Checks\ScheduleCheck;
use App\Checks\UsedDiskSpaceCheck;
use App\Models;
use App\Services\Helpers\SoftwareVersionService;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Livewire\Component;
use Livewire\Livewire;
use Spatie\Health\Facades\Health;

use function Livewire\on;
use function Livewire\store;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(
        Application $app,
        SoftwareVersionService $versionService,
        Repository $config,
    ): void {
        // If the APP_URL value is set with https:// make sure we force it here. Theoretically
        // this should just work with the proxy logic, but there are a lot of cases where it
        // doesn't, and it triggers a lot of support requests, so lets just head it off here.
        URL::forceHttps(Str::startsWith(config('app.url') ?? '', 'https://'));

        if ($app->runningInConsole() && empty(config('app.key'))) {
            $config->set('app.key', '');
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
            'node' => Models\Node::class,
        ]);

        Http::macro(
            'daemon',
            fn (Models\Node $node, array $headers = []) => Http::acceptJson()
                ->asJson()
                ->withToken($node->daemon_token)
                ->withHeaders($headers)
                ->withOptions(['verify' => (bool) $app->environment('production')])
                ->timeout(config('panel.guzzle.timeout'))
                ->connectTimeout(config('panel.guzzle.connect_timeout'))
                ->baseUrl($node->getConnectionAddress())
        );

        Sanctum::usePersonalAccessTokenModel(Models\ApiKey::class);

        Gate::define('viewApiDocs', fn () => true);

        $bearerTokens = fn (OpenApi $openApi) => $openApi->secure(SecurityScheme::http('bearer'));
        Scramble::registerApi('application', ['api_path' => 'api/application', 'info' => ['version' => '1.0']])->afterOpenApiGenerated($bearerTokens);
        Scramble::registerApi('client', ['api_path' => 'api/client', 'info' => ['version' => '1.0']])->afterOpenApiGenerated($bearerTokens);

        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Sky,
            'primary' => Color::Blue,
            'success' => Color::Green,
            'warning' => Color::Amber,
            'blurple' => Color::hex('#5865F2'),
        ]);

        FilamentView::registerRenderHook(
            PanelsRenderHook::PAGE_START,
            fn () => Blade::render('@livewire(\App\Livewire\AlertBannerContainer::class)'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::FOOTER,
            fn () => Blade::render('filament.layouts.footer'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::STYLES_BEFORE,
            fn () => Blade::render("@vite(['resources/css/app.css'])")
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_AFTER,
            fn () => Blade::render("@vite(['resources/js/app.js'])"),
        );

        on('dehydrate', function (Component $component) {
            if (!Livewire::isLivewireRequest()) {
                return;
            }

            if (store($component)->has('redirect')) {
                return;
            }

            if (count(session()->get('alert-banners') ?? []) <= 0) {
                return;
            }

            $component->dispatch('alertBannerSent');
        });

        // Don't run any health checks during tests
        if (!$app->runningUnitTests()) {
            Health::checks([
                DebugModeCheck::new()->if($app->isProduction()),
                EnvironmentCheck::new(),
                CacheCheck::new(),
                DatabaseCheck::new(),
                ScheduleCheck::new(),
                UsedDiskSpaceCheck::new(),
                PanelVersionCheck::new(),
                NodeVersionsCheck::new(),
            ]);
        }

        Gate::before(function (Models\User $user, $ability) {
            return $user->isRootAdmin() ? true : null;
        });

        AboutCommand::add('Pelican', [
            'Panel Version' => $versionService->currentPanelVersion(),
            'Latest Version' => $versionService->latestPanelVersion(),
            'Up-to-Date' => $versionService->isLatestPanel() ? '<fg=green;options=bold>Yes</>' : '<fg=red;options=bold>No</>',
        ]);

        AboutCommand::add('Drivers', 'Backups', config('backups.default'));

        AboutCommand::add('Environment', 'Installation Directory', base_path());
    }

    /**
     * Register application service providers.
     */
    public function register(): void
    {
        Scramble::ignoreDefaultRoutes();
    }
}
