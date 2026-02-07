<?php

declare(strict_types=1);

namespace App\Filament;

use App\Livewire\Passkeys;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\View\View;
use Livewire\Livewire;

final class PasskeysPlugin implements Plugin
{
    public static function make(): static
    {
        // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions
        return app(self::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-passkeys';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn (): View => view('passkeys.login'),
        );

        Livewire::component('filament-passkeys', Passkeys::class);
    }
}
