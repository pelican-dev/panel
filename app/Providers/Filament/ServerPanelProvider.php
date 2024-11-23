<?php

namespace App\Providers\Filament;

use App\Filament\App\Resources\ServerResource\Pages\ListServers;
use App\Filament\Pages\Auth\Login;
use App\Filament\Resources\UserResource\Pages\EditProfile;
use App\Http\Middleware\Activity\ServerSubject;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\MaxWidth;
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
            ->path('app/server')
            ->homeUrl('/app')
            ->spa()
            ->tenant(Server::class)
            ->brandName(config('app.name', 'Pelican'))
            ->brandLogo(config('app.logo'))
            ->brandLogoHeight('2rem')
            ->favicon(config('app.favicon', '/pelican.ico'))
            ->topNavigation(config('panel.filament.top-navigation', true))
            ->maxContentWidth(MaxWidth::ScreenTwoExtraLarge)
            ->login(Login::class)
            ->userMenuItems([
                'profile' => MenuItem::make()->label('Profile')->url(fn () => EditProfile::getUrl(panel: 'app')),
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
                ServerSubject::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
