<?php

namespace App\Filament\Admin\Resources;

use App\Facades\Plugins;
use App\Filament\Admin\Resources\PluginResource\Pages\ListPlugins;
use App\Models\Plugin;
use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Http\UploadedFile;

class PluginResource extends Resource
{
    protected static ?string $model = Plugin::class;

    protected static ?string $navigationIcon = 'tabler-packages';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->openRecordUrlInNewTab()
            ->reorderable('load_order', fn () => auth()->user()->can('update plugin'))
            ->defaultSort('load_order')
            ->columns([
                TextColumn::make('name')
                    ->description(fn (Plugin $plugin) => (strlen($plugin->description) > 80) ? substr($plugin->description, 0, 80).'...' : $plugin->description)
                    ->icon(fn (Plugin $plugin) => $plugin->isUpdateAvailable() ? 'tabler-versions-off' : 'tabler-versions')
                    ->iconColor(fn (Plugin $plugin) => $plugin->isUpdateAvailable() ? 'danger' : 'success')
                    ->tooltip(fn (Plugin $plugin) => $plugin->isUpdateAvailable() ? 'An update for this plugin is available' : null)
                    ->sortable(),
                TextColumn::make('author')
                    ->sortable(),
                TextColumn::make('version')
                    ->sortable(),
                TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->tooltip(fn (Plugin $plugin) => $plugin->status_message)
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->icon('tabler-eye-share')
                    ->color('gray')
                    ->visible(fn (Plugin $plugin) => $plugin->url)
                    ->url(fn (Plugin $plugin) => $plugin->url, true),
                Action::make('settings')
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('update', $plugin))
                    ->icon('tabler-settings')
                    ->color('primary')
                    ->visible(fn (Plugin $plugin) => $plugin->isEnabled() && $plugin->hasSettings())
                    ->form(fn (Plugin $plugin) => $plugin->getSettingsForm())
                    ->action(fn (array $data, Plugin $plugin) => $plugin->saveSettings($data))
                    ->slideOver(),
                Action::make('install')
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('update', $plugin))
                    ->icon('tabler-terminal')
                    ->color('success')
                    ->hidden(fn (Plugin $plugin) => $plugin->isInstalled())
                    ->action(function (Plugin $plugin) {
                        Plugins::installPlugin($plugin);

                        redirect(ListPlugins::getUrl());

                        Notification::make()
                            ->success()
                            ->title('Plugin installed')
                            ->send();
                    }),
                Action::make('update')
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('update', $plugin))
                    ->icon('tabler-download')
                    ->color('success')
                    ->visible(fn (Plugin $plugin) => $plugin->isUpdateAvailable())
                    ->action(function (Plugin $plugin) {
                        Plugins::updatePlugin($plugin);

                        redirect(ListPlugins::getUrl());

                        Notification::make()
                            ->success()
                            ->title('Plugin updated')
                            ->send();
                    }),
                Action::make('enable')
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('update', $plugin))
                    ->icon('tabler-check')
                    ->color('success')
                    ->visible(fn (Plugin $plugin) => $plugin->canEnable())
                    ->requiresConfirmation(fn (Plugin $plugin) => $plugin->isTheme() && Plugins::hasThemePluginEnabled())
                    ->modalHeading('Theme already enabled')
                    ->modalDescription('You already have a theme enabled. Enabling multiple themes can result in visual bugs. Do you want to continue?')
                    ->action(function (Plugin $plugin) {
                        Plugins::enablePlugin($plugin);

                        redirect(ListPlugins::getUrl());

                        Notification::make()
                            ->success()
                            ->title('Plugin enabled')
                            ->send();
                    }),
                Action::make('disable')
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('update', $plugin))
                    ->icon('tabler-x')
                    ->color('danger')
                    ->visible(fn (Plugin $plugin) => $plugin->canDisable())
                    ->action(function (Plugin $plugin) {
                        Plugins::disablePlugin($plugin);

                        redirect(ListPlugins::getUrl());

                        Notification::make()
                            ->success()
                            ->title('Plugin disabled')
                            ->send();
                    }),
            ])
            ->headerActions([
                Action::make('download')
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('create', $plugin))
                    ->icon('tabler-download')
                    ->form([
                        Tabs::make('Tabs')
                            ->contained(false)
                            ->tabs([
                                Tab::make('From File')
                                    ->icon('tabler-file-upload')
                                    ->schema([
                                        FileUpload::make('file')
                                            ->acceptedFileTypes(['application/zip', 'application/zip-compressed', 'application/x-zip-compressed'])
                                            ->preserveFilenames()
                                            ->previewable(false)
                                            ->storeFiles(false),
                                    ]),
                                Tab::make('From URL')
                                    ->icon('tabler-world-upload')
                                    ->schema([
                                        TextInput::make('url')
                                            ->url()
                                            ->endsWith('.zip'),
                                    ]),
                            ]),
                    ])
                    ->action(function ($data) {
                        try {
                            if ($data['file'] instanceof UploadedFile) {
                                Plugins::downloadPluginFromFile($data['file']);
                            }

                            if (is_string($data['url'])) {
                                Plugins::downloadPluginFromUrl($data['url']);
                            }

                            redirect(ListPlugins::getUrl());

                            Notification::make()
                                ->success()
                                ->title('Plugin downloaded')
                                ->send();
                        } catch (Exception $exception) {
                            report($exception);

                            Notification::make()
                                ->danger()
                                ->title('Could not download plugin.')
                                ->body($exception->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->emptyStateIcon('tabler-packages')
            ->emptyStateDescription('')
            ->emptyStateHeading('No Plugins');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlugins::route('/'),
        ];
    }
}
