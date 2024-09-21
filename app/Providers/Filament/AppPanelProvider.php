<?php

namespace App\Providers\Filament;

use App\Filament\App\Pages;
use App\Filament\Resources\UserResource\Pages\EditProfile;
use App\Models\Server;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    public function boot()
    {
        FilamentAsset::registerCssVariables([
            'sidebar-width' => '20rem !important',
        ]);
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('app')
            ->topNavigation(config('panel.filament.top-navigation', true))
            ->tenant(Server::class)
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Sky,
                'primary' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'blurple' => Color::hex('#5865F2'),
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Admin')
                    ->url('/admin')
                    ->icon('tabler-arrow-forward')
                    ->sort(5)
                    ->visible(fn (): bool => auth()->user()->isRootAdmin()),
            ])
            ->favicon(config('app.favicon', '/pelican.ico'))
            ->brandName(config('app.name', 'Pelican'))
            ->brandLogo(config('app.logo'))
            ->brandLogoHeight('2rem')
            ->profile(EditProfile::class, true)
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
