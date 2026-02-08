<?php

namespace App\Providers\Filament;

use App\Enums\CustomizationKey;
use App\Enums\TablerIcon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\View\ActionsIconAlias;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput\Actions\CopyAction;
use Filament\Forms\Components\TextInput\Actions\HidePasswordAction;
use Filament\Forms\Components\TextInput\Actions\ShowPasswordAction;
use Filament\Forms\View\FormsIconAlias;
use Filament\Notifications\View\NotificationsIconAlias;
use Filament\Schemas\View\SchemaIconAlias;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconSize;
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

        KeyValue::configureUsing(fn (KeyValue $keyValue) => $keyValue->deleteAction(function (Action $action) {
            $action->tooltip(fn () => $action->getLabel());
            $action->iconSize(IconSize::Large);
        }));

        Repeater::configureUsing(fn (Repeater $repeater) => $repeater->deleteAction(function (Action $action) {
            $action->tooltip(fn () => $action->getLabel());
            $action->iconSize(IconSize::Large);
        }));

        ShowPasswordAction::configureUsing(function (ShowPasswordAction $action) {
            $action->tooltip(fn () => $action->getLabel());
            $action->iconSize(IconSize::Large);
        });

        HidePasswordAction::configureUsing(function (HidePasswordAction $action) {
            $action->tooltip(fn () => $action->getLabel());
            $action->iconSize(IconSize::Large);
        });

        CopyAction::configureUsing(function (CopyAction $action) {
            $action->tooltip(fn () => $action->getLabel());
            $action->iconSize(IconSize::Large);
        });

        DeleteAction::configureUsing(function (DeleteAction $action) {
            $action->icon(TablerIcon::Trash);
            $action->tooltip(fn () => $action->getLabel());
            $action->hiddenLabel();
            $action->iconSize(IconSize::Large);

            if (user()?->getCustomization(CustomizationKey::ButtonStyle)) {
                $action->iconButton();
                $action->iconSize(IconSize::ExtraLarge);
            }
        });

        CreateAction::configureUsing(function (CreateAction $action) {
            $action->icon(TablerIcon::Plus);
            $action->tooltip(fn () => $action->getLabel());
            $action->hiddenLabel();
            $action->iconSize(IconSize::Large);

            if (user()?->getCustomization(CustomizationKey::ButtonStyle)) {
                $action->iconButton();
                $action->iconSize(IconSize::ExtraLarge);
            }
        });

        EditAction::configureUsing(function (EditAction $action) {
            $action->icon(TablerIcon::Pencil);
            $action->tooltip(fn () => $action->getLabel());
            $action->hiddenLabel();
            $action->iconSize(IconSize::Large);

            if (user()?->getCustomization(CustomizationKey::ButtonStyle)) {
                $action->iconButton();
                $action->iconSize(IconSize::ExtraLarge);
            }
        });

        ViewAction::configureUsing(function (ViewAction $action) {
            $action->icon(TablerIcon::Eye);
            $action->tooltip(fn () => $action->getLabel());
            $action->hiddenLabel();
            $action->iconSize(IconSize::Large);

            if (user()?->getCustomization(CustomizationKey::ButtonStyle)) {
                $action->iconButton();
                $action->iconSize(IconSize::ExtraLarge);
            }
        });

        Action::configureUsing(function (Action $action) {
            $action->iconSize(IconSize::Large);

            if (user()?->getCustomization(CustomizationKey::ButtonStyle)) {
                $name = $action->getName();

                $excludedPrefixes = [
                    'enable_oauth_',
                    'disable_oauth_',
                    'enable_captcha_',
                    'disable_captcha_',
                    'oauth_',
                    'db_', // dashboard
                    'fm_', // file manager
                    'hint_', // hint actions
                    'exclude_', // exclude actions
                ];

                $excludeActions = [
                    'profile',
                    'logout',
                    'start',
                    'stop',
                    'restart',
                    'kill',
                    'fileUpload',
                ];

                foreach ($excludedPrefixes as $prefix) {
                    if (str_starts_with($name, $prefix)) {
                        return;
                    }
                }

                if (in_array($name, $excludeActions, true)) {
                    return;
                }

                $action->iconButton();
                $action->iconSize(IconSize::ExtraLarge);
            }
        });

        FilamentIcon::register([
            ActionsIconAlias::DELETE_ACTION => TablerIcon::Trash,
            ActionsIconAlias::DETACH_ACTION => TablerIcon::Trash,
            ActionsIconAlias::EDIT_ACTION => TablerIcon::Pencil,
            ActionsIconAlias::VIEW_ACTION => TablerIcon::Eye,
            ActionsIconAlias::REPLICATE_ACTION => TablerIcon::CopyPlus,

            PanelsIconAlias::USER_MENU_LOGOUT_BUTTON => TablerIcon::Logout2,
            PanelsIconAlias::USER_MENU_PROFILE_ITEM => TablerIcon::User,
            PanelsIconAlias::THEME_SWITCHER_LIGHT_BUTTON => TablerIcon::Sun,
            PanelsIconAlias::THEME_SWITCHER_DARK_BUTTON => TablerIcon::Moon,
            PanelsIconAlias::THEME_SWITCHER_SYSTEM_BUTTON => TablerIcon::DeviceDesktop,
            PanelsIconAlias::SIDEBAR_OPEN_DATABASE_NOTIFICATIONS_BUTTON => TablerIcon::Bell,
            PanelsIconAlias::TOPBAR_OPEN_DATABASE_NOTIFICATIONS_BUTTON => TablerIcon::Bell,
            PanelsIconAlias::GLOBAL_SEARCH_FIELD => TablerIcon::Search,
            PanelsIconAlias::SIDEBAR_EXPAND_BUTTON => TablerIcon::ArrowRightDashed,
            PanelsIconAlias::SIDEBAR_COLLAPSE_BUTTON => TablerIcon::ArrowLeftDashed,

            TablesIconAlias::ACTIONS_FILTER => TablerIcon::Filters,
            TablesIconAlias::SEARCH_FIELD => TablerIcon::Search,
            TablesIconAlias::ACTIONS_COLUMN_MANAGER => TablerIcon::Columns,
            TablesIconAlias::ACTIONS_OPEN_BULK_ACTIONS => TablerIcon::BoxMultiple,

            NotificationsIconAlias::DATABASE_MODAL_EMPTY_STATE => TablerIcon::BellOff,
            NotificationsIconAlias::NOTIFICATION_CLOSE_BUTTON => TablerIcon::X,
            NotificationsIconAlias::NOTIFICATION_INFO => TablerIcon::InfoCircle,
            NotificationsIconAlias::NOTIFICATION_SUCCESS => TablerIcon::CircleCheck,
            NotificationsIconAlias::NOTIFICATION_WARNING => TablerIcon::AlertTriangle,
            NotificationsIconAlias::NOTIFICATION_DANGER => TablerIcon::AlertCircle,

            SupportIconAlias::MODAL_CLOSE_BUTTON => TablerIcon::X,
            SupportIconAlias::BREADCRUMBS_SEPARATOR => TablerIcon::ChevronsRight,
            SupportIconAlias::PAGINATION_NEXT_BUTTON => TablerIcon::ArrowRight,
            SupportIconAlias::PAGINATION_PREVIOUS_BUTTON => TablerIcon::ArrowLeft,
            SupportIconAlias::SECTION_COLLAPSE_BUTTON => TablerIcon::ChevronUp,

            FormsIconAlias::COMPONENTS_KEY_VALUE_ACTIONS_DELETE => TablerIcon::Trash,
            FormsIconAlias::COMPONENTS_REPEATER_ACTIONS_DELETE => TablerIcon::Trash,
            FormsIconAlias::COMPONENTS_REPEATER_ACTIONS_EXPAND => TablerIcon::ChevronDown,
            FormsIconAlias::COMPONENTS_REPEATER_ACTIONS_COLLAPSE => TablerIcon::ChevronUp,
            FormsIconAlias::COMPONENTS_REPEATER_ACTIONS_REORDER => TablerIcon::ArrowsSort,

            SchemaIconAlias::COMPONENTS_WIZARD_COMPLETED_STEP => TablerIcon::Check,
        ]);
    }

    public function register(): void {}
}
