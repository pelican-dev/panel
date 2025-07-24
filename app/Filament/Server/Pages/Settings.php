<?php

namespace App\Filament\Server\Pages;

use App\Facades\Activity;
use App\Models\Permission;
use App\Models\Server;
use App\Services\Servers\ReinstallServerService;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Number;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class Settings extends ServerFormPage
{
    protected static ?string $navigationIcon = 'tabler-settings';

    protected static ?int $navigationSort = 10;

    public function form(Form $form): Form
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $form
            ->columns([
                'default' => 1,
                'sm' => 2,
                'md' => 4,
                'lg' => 6,
            ])
            ->schema([
                Section::make(trans('server/setting.server_info.title'))
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 4,
                        'lg' => 6,
                    ])
                    ->schema([
                        Fieldset::make()
                            ->label(trans('server/setting.server_info.information'))
                            ->schema([
                                TextInput::make('name')
                                    ->label(trans('server/setting.server_info.name'))
                                    ->disabled(fn () => !auth()->user()->can(Permission::ACTION_SETTINGS_RENAME, $server))
                                    ->required()
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 4,
                                        'lg' => 6,
                                    ])
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Server $server) => $this->updateName($state, $server)),
                                Textarea::make('description')
                                    ->label(trans('server/setting.server_info.description'))
                                    ->hidden(!config('panel.editable_server_descriptions'))
                                    ->disabled(fn () => !auth()->user()->can(Permission::ACTION_SETTINGS_RENAME, $server))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 4,
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
                                        'md' => 3,
                                        'lg' => 5,
                                    ])
                                    ->disabled(),
                                TextInput::make('id')
                                    ->label(trans('server/setting.server_info.id'))
                                    ->disabled()
                                    ->columnSpan(1),
                            ]),
                        Fieldset::make()
                            ->label(trans('server/setting.server_info.limits.title'))
                            ->columns([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 3,
                                'lg' => 3,
                            ])
                            ->schema([
                                TextInput::make('cpu')
                                    ->label('')
                                    ->prefix(trans('server/setting.server_info.limits.cpu'))
                                    ->prefixIcon('tabler-cpu')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('server/setting.server_info.limits.unlimited') : Number::format($server->cpu, locale: auth()->user()->language) . '%'),
                                TextInput::make('memory')
                                    ->label('')
                                    ->prefix(trans('server/setting.server_info.limits.memory'))
                                    ->prefixIcon('tabler-device-desktop-analytics')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('server/setting.server_info.limits.unlimited') : convert_bytes_to_readable($server->memory * 2 ** 20)),
                                TextInput::make('disk')
                                    ->label('')
                                    ->prefix(trans('server/setting.server_info.limits.disk'))
                                    ->prefixIcon('tabler-device-sd-card')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('server/setting.server_info.limits.unlimited') : convert_bytes_to_readable($server->disk * 2 ** 20)),
                                TextInput::make('backup_limit')
                                    ->label('')
                                    ->prefix(trans('server/setting.server_info.limits.backups'))
                                    ->prefixIcon('tabler-file-zip')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? 'No Backups' : $server->backups->count() . ' ' .trans('server/setting.server_info.limits.of') . ' ' . $state),
                                TextInput::make('database_limit')
                                    ->label('')
                                    ->prefix(trans('server/setting.server_info.limits.databases'))
                                    ->prefixIcon('tabler-database')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? 'No Databases' : $server->databases->count() . ' ' . trans('server/setting.server_info.limits.of') . ' ' .$state),
                                TextInput::make('allocation_limit')
                                    ->label('')
                                    ->prefix(trans('server/setting.server_info.limits.allocations'))
                                    ->prefixIcon('tabler-network')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('server/setting.server_info.limits.no_allocations') : $server->allocations->count() . ' ' .trans('server/setting.server_info.limits.of') . ' ' . $state),
                            ]),
                    ]),
                Section::make(trans('server/setting.node_info.title'))
                    ->schema([
                        TextInput::make('node.name')
                            ->label(trans('server/setting.node_info.name'))
                            ->formatStateUsing(fn (Server $server) => $server->node->name)
                            ->disabled(),
                        Fieldset::make(trans('server/setting.node_info.sftp.title'))
                            ->hidden(fn () => !auth()->user()->can(Permission::ACTION_FILE_SFTP, $server))
                            ->columns([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 3,
                                'lg' => 3,
                            ])
                            ->schema([
                                TextInput::make('connection')
                                    ->label(trans('server/setting.node_info.sftp.connection'))
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->suffixAction(fn () => request()->isSecure() ? CopyAction::make() : null)
                                    ->hintAction(
                                        Action::make('connect_sftp')
                                            ->label(trans('server/setting.node_info.sftp.action'))
                                            ->color('success')
                                            ->icon('tabler-plug')
                                            ->url(function (Server $server) {
                                                $fqdn = $server->node->daemon_sftp_alias ?? $server->node->fqdn;

                                                return 'sftp://' . auth()->user()->username . '.' . $server->uuid_short . '@' . $fqdn . ':' . $server->node->daemon_sftp;
                                            }),
                                    )
                                    ->formatStateUsing(function (Server $server) {
                                        $fqdn = $server->node->daemon_sftp_alias ?? $server->node->fqdn;

                                        return 'sftp://' . auth()->user()->username . '.' . $server->uuid_short . '@' . $fqdn . ':' . $server->node->daemon_sftp;
                                    }),
                                TextInput::make('username')
                                    ->label(trans('server/setting.node_info.sftp.username'))
                                    ->columnSpan(1)
                                    ->suffixAction(fn () => request()->isSecure() ? CopyAction::make() : null)
                                    ->disabled()
                                    ->formatStateUsing(fn (Server $server) => auth()->user()->username . '.' . $server->uuid_short),
                                Placeholder::make('password')
                                    ->label(trans('server/setting.node_info.sftp.password'))
                                    ->columnSpan(1)
                                    ->content(trans('server/setting.node_info.sftp.password_body')),
                            ]),
                    ]),
                Section::make(trans('server/setting.reinstall.title'))
                    ->hidden(fn () => !auth()->user()->can(Permission::ACTION_SETTINGS_REINSTALL, $server))
                    ->collapsible()
                    ->footerActions([
                        Action::make('reinstall')
                            ->label(trans('server/setting.reinstall.action'))
                            ->color('danger')
                            ->disabled(fn () => !auth()->user()->can(Permission::ACTION_SETTINGS_REINSTALL, $server))
                            ->requiresConfirmation()
                            ->modalHeading(trans('server/setting.reinstall.modal'))
                            ->modalDescription(trans('server/setting.reinstall.modal_description'))
                            ->modalSubmitActionLabel(trans('server/setting.reinstall.yes'))
                            ->action(function (Server $server, ReinstallServerService $reinstallService) {
                                abort_unless(auth()->user()->can(Permission::ACTION_SETTINGS_REINSTALL, $server), 403);

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
                        Placeholder::make('')
                            ->label(trans('server/setting.reinstall.body')),
                        Placeholder::make('')
                            ->label(trans('server/setting.reinstall.body2')),
                    ]),
            ]);
    }

    public function updateName(string $name, Server $server): void
    {
        abort_unless(auth()->user()->can(Permission::ACTION_SETTINGS_RENAME, $server), 403);

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
                ->title(trans('server/setting.notification_name'))
                ->body(fn () => $original . ' -> ' . $name)
                ->success()
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->title(trans('server/setting.failed'))
                ->body($exception->getMessage())
                ->danger()
                ->send();
        }
    }

    public function updateDescription(string $description, Server $server): void
    {
        abort_unless(auth()->user()->can(Permission::ACTION_SETTINGS_RENAME, $server) && config('panel.editable_server_descriptions'), 403);

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
                ->title(trans('server/setting.notification_description'))
                ->body(fn () => $original . ' -> ' . $description)
                ->success()
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->title(trans('server/setting.failed'))
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
