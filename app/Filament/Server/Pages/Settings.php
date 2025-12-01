<?php

namespace App\Filament\Server\Pages;

use App\Facades\Activity;
use App\Models\Permission;
use App\Models\Server;
use App\Services\Servers\ReinstallServerService;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconSize;

class Settings extends ServerFormPage
{
    protected static string|\BackedEnum|null $navigationIcon = 'tabler-settings';

    protected static ?int $navigationSort = 10;

    /**
     * @throws Exception
     */
    public function form(Schema $schema): Schema
    {
        return parent::form($schema)
            ->components([
                Section::make(trans('server/setting.server_info.title'))
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 4,
                        'lg' => 6,
                    ])
                    ->schema([
                        Fieldset::make()
                            ->label(trans('server/setting.server_info.information'))
                            ->columnSpanFull()
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->columnSpan(5)
                                    ->schema([
                                        TextInput::make('name')
                                            ->columnStart(1)
                                            ->columnSpanFull()
                                            ->label(trans('server/setting.server_info.name'))
                                            ->disabled(fn (Server $server) => !user()?->can(Permission::ACTION_SETTINGS_RENAME, $server))
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, Server $server) => $this->updateName($state, $server)),
                                        Textarea::make('description')
                                            ->columnStart(1)
                                            ->columnSpanFull()
                                            ->label(trans('server/setting.server_info.description'))
                                            ->hidden(!config('panel.editable_server_descriptions'))
                                            ->disabled(fn (Server $server) => !user()?->can(Permission::ACTION_SETTINGS_DESCRIPTION, $server))
                                            ->autosize()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, Server $server) => $this->updateDescription($state ?? '', $server)),
                                    ]),
                                Grid::make()
                                    ->columns(2)
                                    ->columnStart(6)
                                    ->schema([
                                        Image::make('', 'icon')
                                            ->hidden(fn ($record) => !$record->icon && !$record->egg->image)
                                            ->url(fn ($record) => $record->icon ?: $record->egg->image)
                                            ->tooltip(fn ($record) => $record->icon ? '' : trans('server/setting.server_info.icon.tooltip'))
                                            ->columnSpan(2)
                                            ->alignJustify(),
                                        Action::make('uploadIcon')
                                            ->iconButton()->iconSize(IconSize::Large)
                                            ->icon('tabler-photo-up')
                                            ->modal()
                                            ->modalSubmitActionLabel(trans('server/setting.server_info.icon.upload'))
                                            ->schema([
                                                Tabs::make()->tabs([
                                                    Tab::make(trans('admin/egg.import.url'))
                                                        ->schema([
                                                            Hidden::make('base64Image'),
                                                            TextInput::make('image_url')
                                                                ->label(trans('admin/egg.import.image_url'))
                                                                ->reactive()
                                                                ->autocomplete(false)
                                                                ->debounce(500)
                                                                ->afterStateUpdated(function ($state, Set $set) {
                                                                    if (!$state) {
                                                                        $set('image_url_error', null);

                                                                        return;
                                                                    }

                                                                    try {
                                                                        if (!in_array(parse_url($state, PHP_URL_SCHEME), ['http', 'https'], true)) {
                                                                            throw new \Exception(trans('admin/egg.import.invalid_url'));
                                                                        }

                                                                        if (!filter_var($state, FILTER_VALIDATE_URL)) {
                                                                            throw new \Exception(trans('admin/egg.import.invalid_url'));
                                                                        }

                                                                        $allowedExtensions = [
                                                                            'png' => 'image/png',
                                                                            'jpg' => 'image/jpeg',
                                                                            'jpeg' => 'image/jpeg',
                                                                            'gif' => 'image/gif',
                                                                            'webp' => 'image/webp',
                                                                            'svg' => 'image/svg+xml',
                                                                        ];

                                                                        $extension = strtolower(pathinfo(parse_url($state, PHP_URL_PATH), PATHINFO_EXTENSION));

                                                                        if (!array_key_exists($extension, $allowedExtensions)) {
                                                                            throw new \Exception(trans('admin/egg.import.unsupported_format', ['format' => implode(', ', array_keys($allowedExtensions))]));
                                                                        }

                                                                        $host = parse_url($state, PHP_URL_HOST);
                                                                        $ip = gethostbyname($host);

                                                                        if (
                                                                            filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false
                                                                        ) {
                                                                            throw new \Exception(trans('admin/egg.import.no_local_ip'));
                                                                        }

                                                                        $context = stream_context_create([
                                                                            'http' => ['timeout' => 3],
                                                                            'https' => [
                                                                                'timeout' => 3,
                                                                                'verify_peer' => true,
                                                                                'verify_peer_name' => true,
                                                                            ],
                                                                        ]);

                                                                        $imageContent = @file_get_contents($state, false, $context, 0, 262144); //256KB

                                                                        if (!$imageContent) {
                                                                            throw new \Exception(trans('admin/egg.import.image_error'));
                                                                        }

                                                                        $mimeType = $allowedExtensions[$extension];
                                                                        $base64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);

                                                                        $set('base64Image', $base64);
                                                                        $set('image_url_error', null);

                                                                    } catch (\Exception $e) {
                                                                        $set('image_url_error', $e->getMessage());
                                                                        $set('base64Image', null);
                                                                    }
                                                                }),
                                                            TextEntry::make('image_url_error')
                                                                ->hiddenLabel()
                                                                ->visible(fn (Get $get) => $get('image_url_error') !== null)
                                                                ->afterStateHydrated(fn (Get $get) => $get('image_url_error')),
                                                            Image::make(fn (Get $get) => $get('image_url'), '')
                                                                ->imageSize(150)
                                                                ->visible(fn (Get $get) => $get('image_url') && !$get('image_url_error'))
                                                                ->alignCenter(),
                                                        ]),
                                                    Tab::make(trans('admin/egg.import.file'))
                                                        ->schema([
                                                            FileUpload::make('image')
                                                                ->hiddenLabel()
                                                                ->previewable()
                                                                ->openable(false)
                                                                ->downloadable(false)
                                                                ->maxSize(256)
                                                                ->maxFiles(1)
                                                                ->columnSpanFull()
                                                                ->alignCenter()
                                                                ->imageEditor()
                                                                ->image()
                                                                ->saveUploadedFileUsing(function ($file, Set $set) {
                                                                    $base64 = "data:{$file->getMimeType()};base64,". base64_encode(file_get_contents($file->getRealPath()));
                                                                    $set('base64Image', $base64);

                                                                    return $base64;
                                                                }),
                                                        ]),
                                                ]),
                                            ])
                                            ->action(function (array $data, $record): void {
                                                $base64 = $data['base64Image'] ?? null;

                                                if (empty($base64) && !empty($data['image'])) {
                                                    $base64 = $data['image'];
                                                }

                                                if (!empty($base64)) {
                                                    $record->update([
                                                        'icon' => $base64,
                                                    ]);

                                                    Notification::make()
                                                        ->title(trans('server/setting.server_info.icon.updated'))
                                                        ->success()
                                                        ->send();

                                                    $record->refresh();
                                                } else {
                                                    Notification::make()
                                                        ->title(trans('admin/egg.import.no_image'))
                                                        ->warning()
                                                        ->send();
                                                }
                                            }),
                                        Action::make('deleteIcon')
                                            ->visible(fn ($record) => $record->icon)
                                            ->label('')
                                            ->icon('tabler-trash')
                                            ->iconButton()->iconSize(IconSize::Large)
                                            ->color('danger')
                                            ->action(function ($record) {
                                                $record->update([
                                                    'icon' => null,
                                                ]);

                                                Notification::make()
                                                    ->title(trans('server/setting.server_info.icon.deleted'))
                                                    ->success()
                                                    ->send();

                                                $record->refresh();
                                            }),
                                    ]),
                                TextInput::make('uuid')
                                    ->label(trans('server/setting.server_info.uuid'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'md' => 2,
                                        'lg' => 4,
                                    ])
                                    ->disabled(),
                                TextInput::make('uuid_short')
                                    ->label(trans('server/setting.server_info.uuid_short'))
                                    ->disabled()
                                    ->columnSpan(1),
                                TextInput::make('node.name')
                                    ->label(trans('server/setting.server_info.node_name'))
                                    ->formatStateUsing(fn (Server $server) => $server->node->name)
                                    ->disabled()
                                    ->columnSpan(1),
                            ]),
                        Fieldset::make()
                            ->label(trans('server/setting.server_info.limits.title'))
                            ->columnSpan([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 4,
                                'lg' => 6,
                            ])
                            ->columns([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 3,
                            ])
                            ->schema([
                                TextInput::make('cpu')
                                    ->hiddenLabel()
                                    ->prefix(trans('server/setting.server_info.limits.cpu'))
                                    ->prefixIcon('tabler-cpu')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('server/setting.server_info.limits.unlimited') : format_number($server->cpu) . '%'),
                                TextInput::make('memory')
                                    ->hiddenLabel()
                                    ->prefix(trans('server/setting.server_info.limits.memory'))
                                    ->prefixIcon('tabler-device-desktop-analytics')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('server/setting.server_info.limits.unlimited') : convert_bytes_to_readable($server->memory * 2 ** 20)),
                                TextInput::make('disk')
                                    ->hiddenLabel()
                                    ->prefix(trans('server/setting.server_info.limits.disk'))
                                    ->prefixIcon('tabler-device-sd-card')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('server/setting.server_info.limits.unlimited') : convert_bytes_to_readable($server->disk * 2 ** 20)),
                                TextInput::make('backup_limit')
                                    ->hiddenLabel()
                                    ->prefix(trans('server/setting.server_info.limits.backups'))
                                    ->prefixIcon('tabler-file-zip')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('server/backup.empty') : $server->backups->count() . ' ' .trans('server/setting.server_info.limits.of', ['max' => $state])),
                                TextInput::make('database_limit')
                                    ->hiddenLabel()
                                    ->prefix(trans('server/setting.server_info.limits.databases'))
                                    ->prefixIcon('tabler-database')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('server/database.empty') : $server->databases->count() . ' ' . trans('server/setting.server_info.limits.of', ['max' => $state])),
                                TextInput::make('allocation_limit')
                                    ->hiddenLabel()
                                    ->prefix(trans('server/setting.server_info.limits.allocations'))
                                    ->prefixIcon('tabler-network')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('server/setting.server_info.limits.no_allocations') : $server->allocations->count() . ' ' .trans('server/setting.server_info.limits.of', ['max' => $state])),
                            ]),
                        Fieldset::make(trans('server/setting.server_info.sftp.title'))
                            ->columnSpanFull()
                            ->hidden(fn (Server $server) => !user()?->can(Permission::ACTION_FILE_SFTP, $server))
                            ->columns([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 3,
                                'lg' => 3,
                            ])
                            ->schema([
                                TextInput::make('connection')
                                    ->label(trans('server/setting.server_info.sftp.connection'))
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->copyable()
                                    ->hintAction(
                                        Action::make('connect_sftp')
                                            ->label(trans('server/setting.server_info.sftp.action'))
                                            ->color('success')
                                            ->icon('tabler-plug')
                                            ->url(function (Server $server) {
                                                $fqdn = $server->node->daemon_sftp_alias ?? $server->node->fqdn;

                                                return 'sftp://' . rawurlencode(user()?->username) . '.' . $server->uuid_short . '@' . $fqdn . ':' . $server->node->daemon_sftp;
                                            }),
                                    )
                                    ->formatStateUsing(function (Server $server) {
                                        $fqdn = $server->node->daemon_sftp_alias ?? $server->node->fqdn;

                                        return 'sftp://' . rawurlencode(user()?->username) . '.' . $server->uuid_short . '@' . $fqdn . ':' . $server->node->daemon_sftp;
                                    }),
                                TextInput::make('username')
                                    ->label(trans('server/setting.server_info.sftp.username'))
                                    ->columnSpan(1)
                                    ->copyable()
                                    ->disabled()
                                    ->formatStateUsing(fn (Server $server) => user()?->username . '.' . $server->uuid_short),
                                TextEntry::make('password')
                                    ->label(trans('server/setting.server_info.sftp.password'))
                                    ->columnSpan(1)
                                    ->state(trans('server/setting.server_info.sftp.password_body')),
                            ]),
                    ]),
                Section::make(trans('server/setting.reinstall.title'))
                    ->hidden(fn (Server $server) => !user()?->can(Permission::ACTION_SETTINGS_REINSTALL, $server))
                    ->columnSpanFull()
                    ->footerActions([
                        Action::make('reinstall')
                            ->label(trans('server/setting.reinstall.action'))
                            ->color('danger')
                            ->disabled(fn (Server $server) => !user()?->can(Permission::ACTION_SETTINGS_REINSTALL, $server))
                            ->requiresConfirmation()
                            ->modalHeading(trans('server/setting.reinstall.modal'))
                            ->modalDescription(trans('server/setting.reinstall.modal_description'))
                            ->modalSubmitActionLabel(trans('server/setting.reinstall.yes'))
                            ->action(function (Server $server, ReinstallServerService $reinstallService) {
                                abort_unless(user()?->can(Permission::ACTION_SETTINGS_REINSTALL, $server), 403);

                                try {
                                    $reinstallService->handle($server);
                                } catch (Exception $exception) {
                                    report($exception);

                                    Notification::make()
                                        ->title(trans('server/setting.reinstall.notification_fail'))
                                        ->body($exception->getMessage())
                                        ->danger()
                                        ->send();

                                    return;
                                }

                                Activity::event('server:settings.reinstall')
                                    ->log();

                                Notification::make()
                                    ->title(trans('server/setting.reinstall.notification_start'))
                                    ->success()
                                    ->send();

                                redirect(Console::getUrl());
                            }),
                    ])
                    ->footerActionsAlignment(Alignment::Right)
                    ->schema([
                        TextEntry::make('stop_info')
                            ->label(trans('server/setting.reinstall.body')),
                        TextEntry::make('files_info')
                            ->label(trans('server/setting.reinstall.body2')),
                    ]),
            ]);
    }

    public function updateName(string $name, Server $server): void
    {
        abort_unless(user()?->can(Permission::ACTION_SETTINGS_RENAME, $server), 403);

        $original = $server->name;

        try {
            $server->forceFill([
                'name' => $name,
            ])->saveOrFail();

            if ($original !== $name) {
                Activity::event('server:settings.rename')
                    ->property(['old' => $original, 'new' => $name])
                    ->log();
            }

            Notification::make()
                ->title(trans('server/setting.server_info.notification_name'))
                ->body(fn () => $original . ' -> ' . $name)
                ->success()
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->title(trans('server/setting.server_info.failed'))
                ->body($exception->getMessage())
                ->danger()
                ->send();
        }
    }

    public function updateDescription(string $description, Server $server): void
    {
        abort_unless(user()?->can(Permission::ACTION_SETTINGS_DESCRIPTION, $server) && config('panel.editable_server_descriptions'), 403);

        $original = $server->description;

        try {
            $server->forceFill([
                'description' => $description,
            ])->saveOrFail();

            if ($original !== $description) {
                Activity::event('server:settings.description')
                    ->property(['old' => $original, 'new' => $description])
                    ->log();
            }

            Notification::make()
                ->title(trans('server/setting.server_info.notification_description'))
                ->body(fn () => $original . ' -> ' . $description)
                ->success()
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->title(trans('server/setting.server_info.failed'))
                ->body($exception->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getTitle(): string
    {
        return trans('server/setting.title');
    }

    public static function getNavigationLabel(): string
    {
        return trans('server/setting.title');
    }
}
