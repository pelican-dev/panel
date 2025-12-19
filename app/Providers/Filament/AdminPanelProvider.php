<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Pages\ListLogs;
use App\Filament\Admin\Pages\ViewLogs;
use App\Services\Helpers\PluginService;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = parent::panel($panel)
            ->id('admin')
            ->path('admin')
            ->homeUrl('/')
            ->breadcrumbs(false)
            ->sidebarCollapsibleOnDesktop(fn () => !$panel->hasTopNavigation())
            ->userMenuItems([
                Action::make('exit_admin')
                    ->label(fn () => trans('profile.exit_admin'))
                    ->url(fn () => Filament::getPanel('app')->getUrl())
                    ->icon('tabler-arrow-back'),
            ])
            ->navigationGroups([
                NavigationGroup::make(fn () => trans('admin/dashboard.server'))
                    ->collapsible(false),
                NavigationGroup::make(fn () => trans('admin/dashboard.user'))
                    ->collapsible(false),
                NavigationGroup::make(fn () => trans('admin/dashboard.advanced')),
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->plugins([
                FilamentLogViewerPlugin::make()
                    ->authorize(fn () => user()->can('view panelLog'))
                    ->listLogs(ListLogs::class)
                    ->viewLog(ViewLogs::class)
                    ->navigationLabel(fn () => trans('admin/log.navigation.panel_logs'))
                    ->navigationGroup(fn () => trans('admin/dashboard.advanced'))
                    ->navigationIcon('tabler-file-info'),
            ]);

        /** @var PluginService $pluginService */
        $pluginService = app(PluginService::class); // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions

        $pluginService->loadPanelPlugins($panel);

        return $panel;
    }
}
