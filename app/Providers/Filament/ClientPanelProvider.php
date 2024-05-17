<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Resources\UserResource\Pages\EditProfile;
use App\Http\Middleware\LanguageMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;

class ClientPanelProvider extends PanelProvider
{
    /* TODO FIX: right now this only appears in the admin side and not the client side.
    public function boot()
    {
        Filament::serving(function () {
            Filament::registerNavigationItems([
                NavigationItem::make('Admin')
                    ->url('/admin', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-presentation-chart-line')
                    ->visible(auth()->user()->root_admin)
                    ->sort(3),
            ]);
        });

    }
    */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('client')
            ->path('client')
            ->topNavigation(config('panel.filament.top-navigation', false))
            ->login()
            ->favicon('/pelican.ico')
            ->brandName('Pelican')
            ->profile(EditProfile::class, false)
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Sky,
                'primary' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'blurple' => Color::hex('#5865F2'),
            ])
            ->renderHook(
                'panels::body.end',
                fn () => view('filament.Footer'),
            )
            ->discoverResources(in: app_path('Filament/Client/Resources'), for: 'App\\Filament\\Client\\Resources')
            ->discoverPages(in: app_path('Filament/Client/Pages'), for: 'App\\Filament\\Client\\Pages')
            ->discoverWidgets(in: app_path('Filament/Client/Widgets'), for: 'App\\Filament\\Client\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
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
