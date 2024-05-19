<?php

namespace App\Providers\Filament;

use App\Filament\Resources\UserResource\Pages\EditProfile;
use App\Http\Middleware\LanguageMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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
use Filament\Navigation\NavigationItem;

class AdminPanelProvider extends PanelProvider
{
    public function boot()
    {
        FilamentAsset::registerCssVariables([
            'sidebar-width' => '14rem !important',
        ]);
    }

    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->topNavigation(config('panel.filament.top-navigation', false))
            ->login()
            ->sidebarCollapsibleOnDesktop(config('panel.filament.sidebar-collapsible', true))
            ->homeUrl('/')
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
            ->navigationItems([
                NavigationItem::make('client')
                    ->label('Exit Admin')
                    ->url('/')
                    ->icon('tabler-arrow-back')
                    ->sort(12),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->pages([
                // Pages\Dashboard::class,
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

        if (config('panel.filament.top-navigation') === false) {
            $panel->renderHook('panels::sidebar.footer', fn () => view('filament.footer'));
        } else {
            $panel->renderHook('panels::topbar.start', fn () => view('filament.footer')); // TODO find a fix for the footer if topbar is enabled
        }

        return $panel;
    }
}
