<?php

namespace App\Filament\Admin\Resources\Plugins;

use App\Enums\PluginStatus;
use App\Filament\Admin\Resources\Plugins\Pages\ListPlugins;
use App\Models\Plugin;
use App\Services\Helpers\PluginService;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Http\UploadedFile;

class PluginResource extends Resource
{
    protected static ?string $model = Plugin::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-packages';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return trans('admin/plugin.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/plugin.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/plugin.model_label_plural');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count() ?: null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->openRecordUrlInNewTab()
            ->reorderable('load_order')
            ->authorizeReorder(fn () => user()?->can('update plugin'))
            ->reorderRecordsTriggerAction(fn (Action $action, bool $isReordering) => $action->hiddenLabel()->tooltip($isReordering ? trans('admin/plugin.apply_load_order') : trans('admin/plugin.change_load_order')))
            ->defaultSort('load_order')
            ->columns([
                TextColumn::make('name')
                    ->label(trans('admin/plugin.name'))
                    ->description(fn (Plugin $plugin) => (strlen($plugin->description) > 80) ? substr($plugin->description, 0, 80).'...' : $plugin->description)
                    ->icon(fn (Plugin $plugin) => $plugin->isUpdateAvailable() ? 'tabler-versions-off' : 'tabler-versions')
                    ->iconColor(fn (Plugin $plugin) => $plugin->isUpdateAvailable() ? 'danger' : 'success')
                    ->tooltip(fn (Plugin $plugin) => $plugin->isUpdateAvailable() ? trans('admin/plugin.update_available') : null)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('author')
                    ->label(trans('admin/plugin.author'))
                    ->sortable(),
                TextColumn::make('version')
                    ->label(trans('admin/plugin.version'))
                    ->sortable(),
                TextColumn::make('category')
                    ->label(trans('admin/plugin.category'))
                    ->badge()
                    ->sortable()
                    ->visible(fn ($livewire) => $livewire->activeTab === 'all'),
                TextColumn::make('status')
                    ->label(trans('admin/plugin.status'))
                    ->badge()
                    ->tooltip(fn (Plugin $plugin) => $plugin->status_message)
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label(trans('filament-actions::view.single.label'))
                    ->icon(fn (Plugin $plugin) => $plugin->getReadme() ? 'tabler-eye' : 'tabler-eye-share')
                    ->color('gray')
                    ->visible(fn (Plugin $plugin) => $plugin->getReadme() || $plugin->url)
                    ->url(fn (Plugin $plugin) => !$plugin->getReadme() ? $plugin->url : null, true)
                    ->slideOver(true)
                    ->modalHeading('Readme')
                    ->modalSubmitAction(fn (Plugin $plugin) => Action::make('visit_website')
                        ->label(trans('admin/plugin.visit_website'))
                        ->visible(!is_null($plugin->url))
                        ->url($plugin->url, true)
                    )
                    ->modalCancelActionLabel(trans('filament::components/modal.actions.close.label'))
                    ->schema(fn (Plugin $plugin) => $plugin->getReadme() ? [
                        TextEntry::make('readme')
                            ->hiddenLabel()
                            ->markdown()
                            ->state(fn (Plugin $plugin) => $plugin->getReadme()),
                    ] : null),
                Action::make('settings')
                    ->label(trans('admin/plugin.settings'))
                    ->authorize(fn (Plugin $plugin) => user()?->can('update', $plugin))
                    ->icon('tabler-settings')
                    ->color('primary')
                    ->visible(fn (Plugin $plugin) => $plugin->status === PluginStatus::Enabled && $plugin->hasSettings())
                    ->schema(fn (Plugin $plugin) => $plugin->getSettingsForm())
                    ->action(fn (array $data, Plugin $plugin) => $plugin->saveSettings($data))
                    ->slideOver(),
                ActionGroup::make([
                    Action::make('install')
                        ->label(trans('admin/plugin.install'))
                        ->authorize(fn (Plugin $plugin) => user()?->can('update', $plugin))
                        ->icon('tabler-terminal')
                        ->color('success')
                        ->hidden(fn (Plugin $plugin) => $plugin->status !== PluginStatus::NotInstalled)
                        ->action(function (Plugin $plugin, $livewire, PluginService $pluginService) {
                            try {
                                $pluginService->installPlugin($plugin, !$plugin->isTheme() || !$pluginService->hasThemePluginEnabled());

                                redirect(ListPlugins::getUrl(['tab' => $livewire->activeTab]));

                                Notification::make()
                                    ->success()
                                    ->title(trans('admin/plugin.notifications.installed'))
                                    ->send();
                            } catch (Exception $exception) {
                                Notification::make()
                                    ->danger()
                                    ->title(trans('admin/plugin.notifications.install_error'))
                                    ->body($exception->getMessage())
                                    ->send();
                            }
                        }),
                    Action::make('update')
                        ->label(trans('admin/plugin.update'))
                        ->authorize(fn (Plugin $plugin) => user()?->can('update', $plugin))
                        ->icon('tabler-download')
                        ->color('success')
                        ->visible(fn (Plugin $plugin) => $plugin->status !== PluginStatus::NotInstalled && $plugin->isUpdateAvailable())
                        ->action(function (Plugin $plugin, $livewire, PluginService $pluginService) {
                            try {
                                $pluginService->updatePlugin($plugin);

                                redirect(ListPlugins::getUrl(['tab' => $livewire->activeTab]));

                                Notification::make()
                                    ->success()
                                    ->title(trans('admin/plugin.notifications.updated'))
                                    ->send();
                            } catch (Exception $exception) {
                                Notification::make()
                                    ->danger()
                                    ->title(trans('admin/plugin.notifications.update_error'))
                                    ->body($exception->getMessage())
                                    ->send();
                            }
                        }),
                    Action::make('enable')
                        ->label(trans('admin/plugin.enable'))
                        ->authorize(fn (Plugin $plugin) => user()?->can('update', $plugin))
                        ->icon('tabler-check')
                        ->color('success')
                        ->visible(fn (Plugin $plugin) => $plugin->canEnable())
                        ->requiresConfirmation(fn (Plugin $plugin, PluginService $pluginService) => $plugin->isTheme() && $pluginService->hasThemePluginEnabled())
                        ->modalHeading(fn (Plugin $plugin, PluginService $pluginService) => $plugin->isTheme() && $pluginService->hasThemePluginEnabled() ? trans('admin/plugin.enable_theme_modal.heading') : null)
                        ->modalDescription(fn (Plugin $plugin, PluginService $pluginService) => $plugin->isTheme() && $pluginService->hasThemePluginEnabled() ? trans('admin/plugin.enable_theme_modal.description') : null)
                        ->action(function (Plugin $plugin, $livewire, PluginService $pluginService) {
                            $pluginService->enablePlugin($plugin);

                            redirect(ListPlugins::getUrl(['tab' => $livewire->activeTab]));

                            Notification::make()
                                ->success()
                                ->title(trans('admin/plugin.notifications.enabled'))
                                ->send();
                        }),
                    Action::make('disable')
                        ->label(trans('admin/plugin.disable'))
                        ->authorize(fn (Plugin $plugin) => user()?->can('update', $plugin))
                        ->icon('tabler-x')
                        ->color('warning')
                        ->visible(fn (Plugin $plugin) => $plugin->canDisable())
                        ->action(function (Plugin $plugin, $livewire, PluginService $pluginService) {
                            $pluginService->disablePlugin($plugin);

                            redirect(ListPlugins::getUrl(['tab' => $livewire->activeTab]));

                            Notification::make()
                                ->success()
                                ->title(trans('admin/plugin.notifications.disabled'))
                                ->send();
                        }),
                    Action::make('delete')
                        ->label(trans('filament-actions::delete.single.label'))
                        ->authorize(fn (Plugin $plugin) => user()?->can('delete', $plugin))
                        ->icon('tabler-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (Plugin $plugin) => $plugin->status === PluginStatus::NotInstalled)
                        ->action(function (Plugin $plugin, $livewire, PluginService $pluginService) {
                            $pluginService->deletePlugin($plugin);

                            redirect(ListPlugins::getUrl(['tab' => $livewire->activeTab]));

                            Notification::make()
                                ->success()
                                ->title(trans('admin/plugin.notifications.deleted'))
                                ->send();
                        }),
                    Action::make('uninstall')
                        ->label(trans('admin/plugin.uninstall'))
                        ->authorize(fn (Plugin $plugin) => user()?->can('update', $plugin))
                        ->icon('tabler-terminal')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->hidden(fn (Plugin $plugin) => $plugin->status === PluginStatus::NotInstalled || $plugin->status === PluginStatus::Errored)
                        ->action(function (Plugin $plugin, $livewire, PluginService $pluginService) {
                            try {
                                $pluginService->uninstallPlugin($plugin);

                                redirect(ListPlugins::getUrl(['tab' => $livewire->activeTab]));

                                Notification::make()
                                    ->success()
                                    ->title(trans('admin/plugin.notifications.uninstalled'))
                                    ->send();
                            } catch (Exception $exception) {
                                Notification::make()
                                    ->danger()
                                    ->title(trans('admin/plugin.notifications.uninstall_error'))
                                    ->body($exception->getMessage())
                                    ->send();
                            }
                        }),
                ]),
            ])
            ->headerActions([
                Action::make('import_from_file')
                    ->label(trans('admin/plugin.import_from_file'))
                    ->authorize(fn () => user()?->can('create', Plugin::class))
                    ->icon('tabler-file-download')
                    ->iconButton()
                    ->iconSize(IconSize::ExtraLarge)
                    ->schema([
                        // TODO: switch to new file upload
                        FileUpload::make('file')
                            ->required()
                            ->acceptedFileTypes(['application/zip', 'application/zip-compressed', 'application/x-zip-compressed'])
                            ->preserveFilenames()
                            ->previewable(false)
                            ->storeFiles(false),
                    ])
                    ->action(function ($data, $livewire, PluginService $pluginService) {
                        try {
                            /** @var UploadedFile $file */
                            $file = $data['file'];

                            $pluginName = str($file->getClientOriginalName())->before('.zip')->toString();

                            if (Plugin::where('id', $pluginName)->exists()) {
                                throw new Exception(trans('admin/plugin.notifications.import_exists'));
                            }

                            $pluginService->downloadPluginFromFile($file);

                            Notification::make()
                                ->success()
                                ->title(trans('admin/plugin.notifications.imported'))
                                ->send();

                            redirect(ListPlugins::getUrl(['tab' => $livewire->activeTab]));
                        } catch (Exception $exception) {
                            report($exception);

                            Notification::make()
                                ->danger()
                                ->title(trans('admin/plugin.notifications.import_failed'))
                                ->body($exception->getMessage())
                                ->send();
                        }
                    }),
                Action::make('import_from_url')
                    ->label(trans('admin/plugin.import_from_url'))
                    ->authorize(fn () => user()?->can('create', Plugin::class))
                    ->icon('tabler-world-download')
                    ->iconButton()
                    ->iconSize(IconSize::ExtraLarge)
                    ->schema([
                        TextInput::make('url')
                            ->required()
                            ->url()
                            ->endsWith('.zip'),
                    ])
                    ->action(function ($data, $livewire, PluginService $pluginService) {
                        try {
                            $pluginName = str($data['url'])->before('.zip')->explode('/')->last();

                            if (Plugin::where('id', $pluginName)->exists()) {
                                throw new Exception(trans('admin/plugin.notifications.import_exists'));
                            }

                            $pluginService->downloadPluginFromUrl($data['url']);

                            Notification::make()
                                ->success()
                                ->title(trans('admin/plugin.notifications.imported'))
                                ->send();

                            redirect(ListPlugins::getUrl(['tab' => $livewire->activeTab]));
                        } catch (Exception $exception) {
                            report($exception);

                            Notification::make()
                                ->danger()
                                ->title(trans('admin/plugin.notifications.import_failed'))
                                ->body($exception->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->emptyStateIcon('tabler-packages')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/plugin.no_plugins'));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlugins::route('/'),
        ];
    }
}
