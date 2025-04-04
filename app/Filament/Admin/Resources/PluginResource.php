<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PluginResource\Pages\ListPlugins;
use App\Models\Plugin;
use App\Services\Helpers\PluginService;
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
            ->searchable(false)
            ->openRecordUrlInNewTab()
            ->columns([
                TextColumn::make('name')
                    ->description(fn (Plugin $plugin) => (strlen($plugin->description) > 60) ? substr($plugin->description, 0, 60).'...' : $plugin->description)
                    ->icon(fn (Plugin $plugin) => $plugin->isCompatible() ? 'tabler-versions' : 'tabler-versions-off')
                    ->iconColor(fn (Plugin $plugin) => $plugin->isCompatible() ? 'success' : 'danger')
                    ->tooltip(fn (Plugin $plugin) => !$plugin->isCompatible() ? 'This Plugin is only compatible with Panel version ' . $plugin->panel_version . ' but you are using version ' . config('app.version') . '!' : null)
                    ->sortable(),
                TextColumn::make('author')
                    ->sortable(),
                TextColumn::make('version')
                    ->sortable(),
                TextColumn::make('category')
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->tooltip(fn (Plugin $plugin) => $plugin->status_message)
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->icon('tabler-eye-share')
                    ->color('primary')
                    ->visible(fn (Plugin $plugin) => $plugin->url !== null)
                    ->url(fn (Plugin $plugin) => $plugin->url, true),
                Action::make('settings')
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('update plugin', $plugin))
                    ->icon('tabler-settings')
                    ->color('primary')
                    ->visible(fn (Plugin $plugin) => !$plugin->isDisabled() && $plugin->hasSettings())
                    ->form(fn (Plugin $plugin) => $plugin->getSettingsForm())
                    ->action(fn (array $data, Plugin $plugin) => $plugin->saveSettings($data))
                    ->slideOver(),
                Action::make('enable')
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('update plugin', $plugin))
                    ->icon('tabler-check')
                    ->color('success')
                    ->hidden(fn (Plugin $plugin) => !$plugin->isDisabled())
                    ->action(function (Plugin $plugin, PluginService $service) {
                        $service->enablePlugin($plugin);

                        redirect(ListPlugins::getUrl());

                        Notification::make()
                            ->success()
                            ->title('Plugin enabled')
                            ->send();
                    }),
                Action::make('disable')
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('update plugin', $plugin))
                    ->icon('tabler-x')
                    ->color('danger')
                    ->hidden(fn (Plugin $plugin) => $plugin->isDisabled())
                    ->action(function (Plugin $plugin, PluginService $service) {
                        $service->disablePlugin($plugin);

                        redirect(ListPlugins::getUrl());

                        Notification::make()
                            ->success()
                            ->title('Plugin disabled')
                            ->send();
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
