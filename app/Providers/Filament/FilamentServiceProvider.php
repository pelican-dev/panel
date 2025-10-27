<?php

namespace App\Providers\Filament;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput\Actions\CopyAction;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Component;
use Livewire\Livewire;

use function Livewire\on;
use function Livewire\store;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentColor::register([
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Sky,
            'primary' => Color::Blue,
            'success' => Color::Green,
            'warning' => Color::Amber,
            'blurple' => Color::hex('#5865F2'),
        ]);

        FilamentView::registerRenderHook(
            PanelsRenderHook::PAGE_START,
            fn () => Blade::render('@livewire(\App\Livewire\AlertBannerContainer::class)'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::FOOTER,
            fn () => Blade::render('filament.layouts.footer'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::STYLES_BEFORE,
            fn () => Blade::render("@vite(['resources/css/app.css'])")
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SCRIPTS_AFTER,
            fn () => Blade::render("@vite(['resources/js/app.js'])"),
        );

        on('dehydrate', function (Component $component) {
            if (!Livewire::isLivewireRequest()) {
                return;
            }

            if (store($component)->has('redirect')) {
                return;
            }

            if (count(session()->get('alert-banners') ?? []) <= 0) {
                return;
            }

            $component->dispatch('alertBannerSent');
        });

        Field::macro('hintCopy', function () {
            /** @var Field $this */
            return $this->hintAction(CopyAction::make()); // @phpstan-ignore varTag.nativeType
        });

        Select::configureUsing(fn (Select $select) => $select->native(false));
    }

    public function register(): void {}
}
