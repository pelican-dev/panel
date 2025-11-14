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
use App\Models\Allocation;
use App\Models\ApiKey;
use App\Models\Backup;
use App\Models\Database;
use App\Models\Egg;
use App\Models\EggVariable;
use App\Models\Node;
use App\Models\Schedule;
use App\Models\Server;
use App\Models\Task;
use App\Models\User;
use App\Models\UserSSHKey;
use App\Services\Helpers\SoftwareVersionService;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Filament\Forms\View\FormsIconAlias;
use Filament\Notifications\View\NotificationsIconAlias;
use Filament\Schemas\View\SchemaIconAlias;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\View\SupportIconAlias;
use Filament\Tables\View\TablesIconAlias;
use Filament\View\PanelsIconAlias;
use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Spatie\Health\Facades\Health;

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
            'allocation' => Allocation::class,
            'api_key' => ApiKey::class,
            'backup' => Backup::class,
            'database' => Database::class,
            'egg' => Egg::class,
            'egg_variable' => EggVariable::class,
            'schedule' => Schedule::class,
            'server' => Server::class,
            'ssh_key' => UserSSHKey::class,
            'task' => Task::class,
            'user' => User::class,
            'node' => Node::class,
        ]);

        Http::macro(
            'daemon',
            fn (Node $node, array $headers = []) => Http::acceptJson()
                ->asJson()
                ->withToken($node->daemon_token)
                ->withHeaders($headers)
                ->withOptions(['verify' => (bool) $app->environment('production')])
                ->timeout(config('panel.guzzle.timeout'))
                ->connectTimeout(config('panel.guzzle.connect_timeout'))
                ->baseUrl($node->getConnectionAddress())
        );

        Sanctum::usePersonalAccessTokenModel(ApiKey::class);

        Gate::define('viewApiDocs', fn () => true);

        $bearerTokens = fn (OpenApi $openApi) => $openApi->secure(SecurityScheme::http('bearer'));
        Scramble::registerApi('application', ['api_path' => 'api/application', 'info' => ['version' => '1.0']])->afterOpenApiGenerated($bearerTokens);
        Scramble::registerApi('client', ['api_path' => 'api/client', 'info' => ['version' => '1.0']])->afterOpenApiGenerated($bearerTokens);

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

        Gate::before(function (User $user, $ability) {
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

        FilamentIcon::register([
            PanelsIconAlias::USER_MENU_LOGOUT_BUTTON => 'tabler-logout-2',
            PanelsIconAlias::USER_MENU_PROFILE_ITEM => 'tabler-user',
            PanelsIconAlias::THEME_SWITCHER_LIGHT_BUTTON => 'tabler-sun',
            PanelsIconAlias::THEME_SWITCHER_DARK_BUTTON => 'tabler-moon',
            PanelsIconAlias::THEME_SWITCHER_SYSTEM_BUTTON => 'tabler-device-desktop',
            PanelsIconAlias::SIDEBAR_OPEN_DATABASE_NOTIFICATIONS_BUTTON => 'tabler-bell',
            PanelsIconAlias::TOPBAR_OPEN_DATABASE_NOTIFICATIONS_BUTTON => 'tabler-bell',
            PanelsIconAlias::GLOBAL_SEARCH_FIELD => 'tabler-search',
            PanelsIconAlias::SIDEBAR_EXPAND_BUTTON => 'tabler-arrow-right-dashed',
            PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON => 'tabler-arrow-left-dashed',

            TablesIconAlias::ACTIONS_FILTER => 'tabler-filters',
            TablesIconAlias::SEARCH_FIELD => 'tabler-search',
            TablesIconAlias::ACTIONS_COLUMN_MANAGER => 'tabler-columns',
            TablesIconAlias::ACTIONS_OPEN_BULK_ACTIONS => 'tabler-box-multiple',

            NotificationsIconAlias::DATABASE_MODAL_EMPTY_STATE => 'tabler-bell-off',
            NotificationsIconAlias::NOTIFICATION_CLOSE_BUTTON => 'tabler-x',
            NotificationsIconAlias::NOTIFICATION_INFO => 'tabler-info-circle',
            NotificationsIconAlias::NOTIFICATION_SUCCESS => 'tabler-check-circle',
            NotificationsIconAlias::NOTIFICATION_WARNING => 'tabler-alert-triangle',
            NotificationsIconAlias::NOTIFICATION_DANGER => 'tabler-alert-circle',

            SupportIconAlias::MODAL_CLOSE_BUTTON => 'tabler-x',
            SupportIconAlias::BREADCRUMBS_SEPARATOR => 'tabler-chevrons-right',
            SupportIconAlias::PAGINATION_NEXT_BUTTON => 'tabler-arrow-right',
            SupportIconAlias::PAGINATION_PREVIOUS_BUTTON => 'tabler-arrow-left',
            SupportIconAlias::SECTION_COLLAPSE_BUTTON => 'tabler-arrow-up',

            FormsIconAlias::COMPONENTS_KEY_VALUE_ACTIONS_DELETE => 'tabler-trash',
            FormsIconAlias::COMPONENTS_REPEATER_ACTIONS_DELETE => 'tabler-trash',
            FormsIconAlias::COMPONENTS_REPEATER_ACTIONS_EXPAND => 'tabler-arrow-down',
            FormsIconAlias::COMPONENTS_REPEATER_ACTIONS_COLLAPSE => 'tabler-arrow-up',
            FormsIconAlias::COMPONENTS_REPEATER_ACTIONS_REORDER => 'tabler-arrows-sort',

            SchemaIconAlias::COMPONENTS_WIZARD_COMPLETED_STEP => 'tabler-check',
        ]);
    }
}
