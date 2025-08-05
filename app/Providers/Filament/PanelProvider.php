<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\EditProfile;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\RequireTwoFactorAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider as BasePanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

abstract class PanelProvider extends BasePanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->spa()
            ->databaseNotifications()
            ->brandName(config('app.name', 'Pelican'))
            ->brandLogo(config('app.logo'))
            ->brandLogoHeight('2rem')
            ->favicon(config('app.favicon', '/pelican.ico'))
            ->topNavigation(fn () => auth()->user()->getCustomization()['top_navigation'] ?? false)
            ->maxContentWidth(config('panel.filament.display-width', 'screen-2xl'))
            ->profile(EditProfile::class, false)
            ->login(Login::class)
            ->passwordReset()
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
