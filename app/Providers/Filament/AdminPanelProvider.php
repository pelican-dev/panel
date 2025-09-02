<?php

namespace App\Providers\Filament;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->id('admin')
            ->path('admin')
            ->homeUrl('/')
            ->breadcrumbs(false)
            ->sidebarCollapsibleOnDesktop()
            ->userMenuItems([
                'profile' => fn (Action $action) => $action->label(auth()->user()->username),
                Action::make('exitAdmin')
                    ->label(fn () => trans('profile.exit_admin'))
                    ->url(fn () => Filament::getPanel('app')->getUrl())
                    ->icon('tabler-arrow-back')
                    ->sort(24),
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
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets');
    }
}
