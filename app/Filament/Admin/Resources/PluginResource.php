<?php

namespace App\Filament\Admin\Resources;

use App\Enums\PluginCategory;
use App\Facades\Plugins;
use App\Filament\Admin\Resources\Plugins\Pages\ListPlugins;
use App\Models\Plugin;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;

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
            ->reorderable('load_order', fn () => auth()->user()->can('update plugin'))
            ->defaultSort('load_order')
            ->columns([
                TextColumn::make('name')
                    ->label(trans('admin/plugin.name'))
                    ->description(fn (Plugin $plugin) => (strlen($plugin->description) > 80) ? substr($plugin->description, 0, 80).'...' : $plugin->description)
                    ->icon(fn (Plugin $plugin) => $plugin->isUpdateAvailable() ? 'tabler-versions-off' : 'tabler-versions')
                    ->iconColor(fn (Plugin $plugin) => $plugin->isUpdateAvailable() ? 'danger' : 'success')
                    ->tooltip(fn (Plugin $plugin) => $plugin->isUpdateAvailable() ? trans('admin/plugin.update_available') : null)
                    ->sortable(),
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
                    ->icon('tabler-eye-share')
                    ->color('gray')
                    ->visible(fn (Plugin $plugin) => $plugin->url)
                    ->url(fn (Plugin $plugin) => $plugin->url, true),
                Action::make('settings')
                    ->label(trans('admin/plugin.settings'))
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('update', $plugin))
                    ->icon('tabler-settings')
                    ->color('primary')
                    ->visible(fn (Plugin $plugin) => $plugin->isEnabled() && $plugin->hasSettings())
                    ->schema(fn (Plugin $plugin) => $plugin->getSettingsForm())
                    ->action(fn (array $data, Plugin $plugin) => $plugin->saveSettings($data))
                    ->slideOver(),
                Action::make('install')
                    ->label(trans('admin/plugin.install'))
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('update', $plugin))
                    ->icon('tabler-terminal')
                    ->color('success')
                    ->hidden(fn (Plugin $plugin) => $plugin->isInstalled())
                    ->action(function (Plugin $plugin) {
                        Plugins::installPlugin($plugin);

                        redirect(ListPlugins::getUrl());

                        Notification::make()
                            ->success()
                            ->title(trans('admin/plugin.notifications.installed'))
                            ->send();
                    }),
                Action::make('update')
                    ->label(trans('admin/plugin.update'))
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('update', $plugin))
                    ->icon('tabler-download')
                    ->color('success')
                    ->visible(fn (Plugin $plugin) => $plugin->isUpdateAvailable())
                    ->action(function (Plugin $plugin) {
                        Plugins::updatePlugin($plugin);

                        redirect(ListPlugins::getUrl());

                        Notification::make()
                            ->success()
                            ->title(trans('admin/plugin.notifications.updated'))
                            ->send();
                    }),
                Action::make('enable')
                    ->label(trans('admin/plugin.enable'))
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('update', $plugin))
                    ->icon('tabler-check')
                    ->color('success')
                    ->visible(fn (Plugin $plugin) => $plugin->canEnable())
                    ->requiresConfirmation(fn (Plugin $plugin) => $plugin->isTheme() && Plugins::hasThemePluginEnabled())
                    ->modalHeading(fn (Plugin $plugin) => $plugin->isTheme() && Plugins::hasThemePluginEnabled() ? 'Theme already enabled' : null)
                    ->modalDescription(fn (Plugin $plugin) => $plugin->isTheme() && Plugins::hasThemePluginEnabled() ? 'You already have a theme enabled. Enabling multiple themes can result in visual bugs. Do you want to continue?' : null)
                    ->action(function (Plugin $plugin) {
                        Plugins::enablePlugin($plugin);

                        redirect(ListPlugins::getUrl());

                        Notification::make()
                            ->success()
                            ->title(trans('admin/plugin.notifications.updated'))
                            ->send();
                    }),
                Action::make('disable')
                    ->label(trans('admin/plugin.disable'))
                    ->authorize(fn (Plugin $plugin) => auth()->user()->can('update', $plugin))
                    ->icon('tabler-x')
                    ->color('danger')
                    ->visible(fn (Plugin $plugin) => $plugin->canDisable())
                    ->action(function (Plugin $plugin) {
                        Plugins::disablePlugin($plugin);

                        redirect(ListPlugins::getUrl());

                        Notification::make()
                            ->success()
                            ->title(trans('admin/plugin.notifications.updated'))
                            ->send();
                    }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->createAnother(false)
                    ->visible(fn () => Plugins::isDevModeActive())
                    ->action(function ($data) {
                        $exitCode = Artisan::call('p:plugin:make', [
                            '--name' => $data['name'],
                            '--author' => $data['author'],
                            '--description' => $data['description'],
                            '--category' => $data['category'],
                            '--url' => $data['url'] ?? '',
                            '--updateUrl' => $data['update_url'] ?? '',
                            '--panels' => $data['panels'] ?? [],
                            '--composerPackages' => $data['composer_packages'] ?? [],
                        ]);

                        if ($exitCode === 0) {
                            redirect(ListPlugins::getUrl());

                            Notification::make()
                                ->success()
                                ->title(trans('admin/plugin.notifications.created'))
                                ->send();
                        } else {
                            Notification::make()
                                ->danger()
                                ->title(trans('admin/plugin.notifications.create_failed'))
                                ->send();
                        }
                    }),
                Action::make('import')
                    ->label(trans('admin/plugin.import'))
                    ->authorize(fn () => auth()->user()->can('create', Plugin::class))
                    ->icon('tabler-download')
                    ->schema([
                        Tabs::make('Tabs')
                            ->contained(false)
                            ->tabs([
                                Tab::make('from_file')
                                    ->label(trans('admin/plugin.from_file'))
                                    ->icon('tabler-file-upload')
                                    ->schema([
                                        FileUpload::make('file')
                                            ->acceptedFileTypes(['application/zip', 'application/zip-compressed', 'application/x-zip-compressed'])
                                            ->preserveFilenames()
                                            ->previewable(false)
                                            ->storeFiles(false),
                                    ]),
                                Tab::make('from_url')
                                    ->label(trans('admin/plugin.from_url'))
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
                                ->title(trans('admin/plugin.notifications.downloaded'))
                                ->send();
                        } catch (Exception $exception) {
                            report($exception);

                            Notification::make()
                                ->danger()
                                ->title(trans('admin/plugin.notifications.download_failed'))
                                ->body($exception->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->emptyStateIcon('tabler-packages')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/plugin.no_plugins'));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('author')
                    ->required()
                    ->default(fn () => auth()->user()->username),
                TextInput::make('description')
                    ->columnSpanFull(),
                Select::make('category')
                    ->selectablePlaceholder(false)
                    ->default(PluginCategory::Plugin->value)
                    ->options(PluginCategory::class),
                Select::make('panels')
                    ->multiple()
                    ->options([
                        'admin' => 'Admin Area',
                        'server' => 'Client Area',
                        'app' => 'Server List',
                    ]),
                TextInput::make('url')
                    ->url(),
                TextInput::make('update_url')
                    ->url(),
                TagsInput::make('composer_packages')
                    ->columnSpanFull()
                    ->placeholder('New Package'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlugins::route('/'),
        ];
    }
}
