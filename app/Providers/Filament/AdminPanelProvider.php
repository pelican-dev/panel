<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\EditProfile;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use App\Http\Middleware\LanguageMiddleware;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->homeUrl('/')
            ->spa()
            ->databaseNotifications()
            ->breadcrumbs(false)
            ->brandName(config('app.name', 'Pelican'))
            ->brandLogo(config('app.logo'))
            ->brandLogoHeight('2rem')
            ->favicon(config('app.favicon', '/pelican.ico'))
            ->topNavigation(config('panel.filament.top-navigation', true))
            ->maxContentWidth(config('panel.filament.display-width', 'screen-2xl'))
            ->profile(EditProfile::class, false)
            ->login(Login::class)
            ->userMenuItems([
                MenuItem::make()
                    ->label(trans('profile.exit_admin'))
                    ->url('/')
                    ->icon('tabler-arrow-back')
                    ->sort(24),
            ])
            ->navigationGroups([
                NavigationGroup::make(trans('admin/dashboard.server'))
                    ->collapsible(false),
                NavigationGroup::make(trans('admin/dashboard.user'))
                    ->collapsible(false),
                NavigationGroup::make(trans('admin/dashboard.advanced')),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                LanguageMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
