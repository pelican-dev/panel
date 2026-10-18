<?php

namespace App\Providers\Filament;

use App\Enums\TablerIcon;
use App\Services\Helpers\PluginService;
use Boquizo\FilamentLogViewer\FilamentLogViewerPlugin;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Panel;

class AppPanelProvider extends PanelProvider
{
    protected function panelId(): string
    {
        return 'app';
    }

    protected function isDefaultPanel(): bool
    {
        return true;
    }

    protected function configurePanel(Panel $panel): Panel
    {
        $panel = $panel
            ->id($this->panelId())
            ->default()
            ->breadcrumbs(false)
            ->navigation(false)
            ->topbar(true)
            ->userMenuItems([
                Action::make('to_admin')
                    ->label(trans('profile.admin'))
                    ->url(fn () => Filament::getPanel('admin')->getUrl())
                    ->icon(TablerIcon::ArrowForward)
                    ->visible(fn () => user()?->canAccessPanel(Filament::getPanel('admin'))),
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->plugins([
                FilamentLogViewerPlugin::make()
                    ->authorize(false),
            ]);

        /** @var PluginService $pluginService */
        $pluginService = app(PluginService::class); // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions

        $pluginService->loadPanelPlugins($panel);

        return $panel;
    }
}
