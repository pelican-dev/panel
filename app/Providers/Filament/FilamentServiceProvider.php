<?php

namespace App\Providers\Filament;

use Filament\Actions\DeleteAction;
use Filament\Actions\View\ActionsIconAlias;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput\Actions\CopyAction;
use Filament\Forms\View\FormsIconAlias;
use Filament\Notifications\View\NotificationsIconAlias;
use Filament\Schemas\View\SchemaIconAlias;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentView;
use Filament\Support\View\SupportIconAlias;
use Filament\Tables\View\TablesIconAlias;
use Filament\View\PanelsIconAlias;
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
        DeleteAction::configureUsing(fn (DeleteAction $action) => $action->icon('tabler-trash'));

        FilamentIcon::register([
            ActionsIconAlias::DELETE_ACTION => 'tabler-trash',
            ActionsIconAlias::DETACH_ACTION => 'tabler-trash',
            ActionsIconAlias::EDIT_ACTION => 'tabler-pencil',
            ActionsIconAlias::VIEW_ACTION => 'tabler-eye',
            ActionsIconAlias::REPLICATE_ACTION => 'tabler-copy-plus',

            PanelsIconAlias::USER_MENU_LOGOUT_BUTTON => 'tabler-logout-2',
            PanelsIconAlias::USER_MENU_PROFILE_ITEM => 'tabler-user',
            PanelsIconAlias::THEME_SWITCHER_LIGHT_BUTTON => 'tabler-sun',
            PanelsIconAlias::THEME_SWITCHER_DARK_BUTTON => 'tabler-moon',
            PanelsIconAlias::THEME_SWITCHER_SYSTEM_BUTTON => 'tabler-device-desktop',
            PanelsIconAlias::SIDEBAR_OPEN_DATABASE_NOTIFICATIONS_BUTTON => 'tabler-bell',
            PanelsIconAlias::TOPBAR_OPEN_DATABASE_NOTIFICATIONS_BUTTON => 'tabler-bell',
            PanelsIconAlias::GLOBAL_SEARCH_FIELD => 'tabler-search',
            PanelsIconAlias::SIDEBAR_EXPAND_BUTTON => 'tabler-arrow-right-dashed',
            PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON => 'tabler-arrow-left-dashed',

            TablesIconAlias::ACTIONS_FILTER => 'tabler-filters',
            TablesIconAlias::SEARCH_FIELD => 'tabler-search',
            TablesIconAlias::ACTIONS_COLUMN_MANAGER => 'tabler-columns',
            TablesIconAlias::ACTIONS_OPEN_BULK_ACTIONS => 'tabler-box-multiple',

            NotificationsIconAlias::DATABASE_MODAL_EMPTY_STATE => 'tabler-bell-off',
            NotificationsIconAlias::NOTIFICATION_CLOSE_BUTTON => 'tabler-x',
            NotificationsIconAlias::NOTIFICATION_INFO => 'tabler-info-circle',
            NotificationsIconAlias::NOTIFICATION_SUCCESS => 'tabler-circle-check',
            NotificationsIconAlias::NOTIFICATION_WARNING => 'tabler-alert-triangle',
            NotificationsIconAlias::NOTIFICATION_DANGER => 'tabler-alert-circle',

            SupportIconAlias::MODAL_CLOSE_BUTTON => 'tabler-x',
            SupportIconAlias::BREADCRUMBS_SEPARATOR => 'tabler-chevrons-right',
            SupportIconAlias::PAGINATION_NEXT_BUTTON => 'tabler-arrow-right',
            SupportIconAlias::PAGINATION_PREVIOUS_BUTTON => 'tabler-arrow-left',
            SupportIconAlias::SECTION_COLLAPSE_BUTTON => 'tabler-chevron-up',

            FormsIconAlias::COMPONENTS_KEY_VALUE_ACTIONS_DELETE => 'tabler-trash',
            FormsIconAlias::COMPONENTS_REPEATER_ACTIONS_DELETE => 'tabler-trash',
            FormsIconAlias::COMPONENTS_REPEATER_ACTIONS_EXPAND => 'tabler-chevron-down',
            FormsIconAlias::COMPONENTS_REPEATER_ACTIONS_COLLAPSE => 'tabler-chevron-up',
            FormsIconAlias::COMPONENTS_REPEATER_ACTIONS_REORDER => 'tabler-arrows-sort',

            SchemaIconAlias::COMPONENTS_WIZARD_COMPLETED_STEP => 'tabler-check',
        ]);
    }

    public function register(): void {}
}
