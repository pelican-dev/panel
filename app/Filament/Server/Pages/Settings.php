<?php

namespace App\Filament\Server\Pages;

use App\Facades\Activity;
use App\Models\Permission;
use App\Models\Server;
use App\Services\Servers\ReinstallServerService;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;

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
            ->columns(4)
            ->components([
                Section::make(trans('server/setting.server_info.title'))
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 4,
                        'lg' => 6,
                    ])
                    ->schema([
                        Fieldset::make()
                            ->label(trans('server/setting.server_info.information'))
                            ->columnSpan([
                                'default' => 1,
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 6,
                            ])
                            ->schema([
                                TextInput::make('name')
                                    ->label(trans('server/setting.server_info.name'))
                                    ->disabled(fn (Server $server) => !user()?->can(Permission::ACTION_SETTINGS_RENAME, $server))
                                    ->required()
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 2,
                                        'lg' => 6,
                                    ])
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Server $server) => $this->updateName($state, $server)),
                                Textarea::make('description')
                                    ->label(trans('server/setting.server_info.description'))
                                    ->hidden(!config('panel.editable_server_descriptions'))
                                    ->disabled(fn (Server $server) => !user()?->can(Permission::ACTION_SETTINGS_RENAME, $server))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 2,
                                        'lg' => 6,
                                    ])
                                    ->autosize()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Server $server) => $this->updateDescription($state ?? '', $server)),
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
                                'sm' => 2,
                                'md' => 2,
                                'lg' => 6,
                            ])
                            ->columns([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 1,
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
        abort_unless(user()?->can(Permission::ACTION_SETTINGS_RENAME, $server) && config('panel.editable_server_descriptions'), 403);

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
