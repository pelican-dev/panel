<?php

namespace App\Filament\Admin\Resources;

use App\Facades\Plugins;
use App\Filament\Admin\Resources\PluginResource\Pages\ListPlugins;
use App\Models\Plugin;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                // TODO: "update" button
                Action::make('enable')
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('update', $plugin))
                    ->icon('tabler-check')
                    ->color('success')
                    ->visible(fn (Plugin $plugin) => $plugin->canEnable())
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
                // TODO: "import" button
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
