<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use Filament\Navigation\MenuItem;
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
                'profile' => MenuItem::make()
                    ->label(fn () => trans('filament-panels::pages/auth/edit-profile.label'))
                    ->url(fn () => EditProfile::getUrl(panel: 'app')),
                MenuItem::make()
                    ->label(fn () => trans('profile.exit_admin'))
                    ->url('/')
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
