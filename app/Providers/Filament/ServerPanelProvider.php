<?php

namespace App\Providers\Filament;

use App\Filament\App\Resources\ServerResource\Pages\ListServers;
use App\Filament\Pages\Auth\Login;
use App\Filament\Admin\Resources\ServerResource\Pages\EditServer;
use App\Filament\Pages\Auth\EditProfile;
use App\Http\Middleware\Activity\ServerSubject;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\RequireTwoFactorAuthentication;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ServerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('server')
            ->path('server')
            ->homeUrl('/')
            ->spa()
            ->databaseNotifications()
            ->tenant(Server::class)
            ->brandName(config('app.name', 'Pelican'))
            ->brandLogo(config('app.logo'))
            ->brandLogoHeight('2rem')
            ->favicon(config('app.favicon', '/pelican.ico'))
            ->topNavigation(config('panel.filament.top-navigation', false))
            ->maxContentWidth(config('panel.filament.display-width', 'screen-2xl'))
            ->login(Login::class)
            ->passwordReset()
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn () => trans('filament-panels::pages/auth/edit-profile.label'))
                    ->url(fn () => EditProfile::getUrl(panel: 'app')),
                MenuItem::make()
                    ->label('Server List')
                    ->icon('tabler-brand-docker')
                    ->url(fn () => ListServers::getUrl(panel: 'app'))
                    ->sort(6),
                MenuItem::make()
                    ->label('Admin')
                    ->icon('tabler-arrow-forward')
                    ->url(fn () => Filament::getPanel('admin')->getUrl())
                    ->sort(5)
                    ->visible(fn (): bool => auth()->user()->canAccessPanel(Filament::getPanel('admin'))),
            ])
            ->navigationItems([
                NavigationItem::make('Open in Admin')
                    ->url(fn () => EditServer::getUrl(['record' => Filament::getTenant()], panel: 'admin'))
                    ->visible(fn () => auth()->user()->canAccessPanel(Filament::getPanel('admin')) && auth()->user()->can('view server', Filament::getTenant()))
                    ->icon('tabler-arrow-back')
                    ->sort(99),
            ])
            ->discoverResources(in: app_path('Filament/Server/Resources'), for: 'App\\Filament\\Server\\Resources')
            ->discoverPages(in: app_path('Filament/Server/Pages'), for: 'App\\Filament\\Server\\Pages')
            ->discoverWidgets(in: app_path('Filament/Server/Widgets'), for: 'App\\Filament\\Server\\Widgets')
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
                RequireTwoFactorAuthentication::class,
                ServerSubject::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
