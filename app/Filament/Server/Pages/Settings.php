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
                Section::make(trans('strings.console.settings.basic.heading'))
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 4,
                        'lg' => 6,
                    ])
                    ->schema([
                        Fieldset::make('Server')
                            ->label(trans('strings.console.settings.basic.title'))
                            ->schema([
                                TextInput::make('name')
                                    ->label(trans('strings.console.settings.basic.server_name'))
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
                                    ->label(trans('strings.console.settings.basic.server_descriptions'))
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
                                    ->label(trans('strings.console.settings.basic.server_uuid'))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'md' => 3,
                                        'lg' => 5,
                                    ])
                                    ->disabled(),
                                TextInput::make('id')
                                    ->label(trans('strings.console.settings.basic.server_id'))
                                    ->disabled()
                                    ->columnSpan(1),
                            ]),
                        Fieldset::make('Limits')
                            ->label(trans('strings.console.settings.basic.limits.title'))
                            ->columns([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 3,
                                'lg' => 3,
                            ])
                            ->schema([
                                TextInput::make('cpu')
                                    ->label('')
                                    ->prefix(trans('strings.console.settings.basic.limits.cpu_prefix'))
                                    ->prefixIcon('tabler-cpu')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('strings.console.settings.tag_unlimited') : Number::format($server->cpu, locale: auth()->user()->language) . '%'),
                                TextInput::make('memory')
                                    ->label('')
                                    ->prefix(trans('strings.console.settings.basic.limits.memory_prefix'))
                                    ->prefixIcon('tabler-device-desktop-analytics')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('strings.console.settings.tag_unlimited') : convert_bytes_to_readable($server->memory * 2 ** 20)),
                                TextInput::make('disk')
                                    ->label('')
                                    ->prefix(trans('strings.console.settings.basic.limits.disk_prefix'))
                                    ->prefixIcon('tabler-device-sd-card')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('strings.console.settings.tag_unlimited') : convert_bytes_to_readable($server->disk * 2 ** 20)),
                                TextInput::make('backup_limit')
                                    ->label('')
                                    ->prefix(trans('strings.console.settings.basic.limits.backups_prefix'))
                                    ->prefixIcon('tabler-file-zip')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('strings.console.settings.tag_nobackups') : $server->backups->count() . ' of ' . $state),
                                TextInput::make('database_limit')
                                    ->label('')
                                    ->prefix(trans('strings.console.settings.basic.limits.databases_prefix'))
                                    ->prefixIcon('tabler-database')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('strings.console.settings.tag_nodatabases') : $server->databases->count() . ' of ' . $state),
                                TextInput::make('allocation_limit')
                                    ->label('')
                                    ->prefix(trans('strings.console.settings.basic.limits.disk_prefix'))
                                    ->prefixIcon('tabler-network')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? trans('strings.console.settings.tag_noadditionalallocations') : $server->allocations->count() . ' of ' . $state),
                            ]),
                    ]),
                Section::make(trans('strings.console.settings.node.heading'))
                    ->schema([
                        TextInput::make('node.name')
                            ->label(trans('strings.console.settings.node.node_name'))
                            ->formatStateUsing(fn (Server $server) => $server->node->name)
                            ->disabled(),
                        Fieldset::make('SFTP Information')
                            ->hidden(fn () => !auth()->user()->can(Permission::ACTION_FILE_SFTP, $server))
                            ->label(trans('strings.console.settings.node.sftp_header'))
                            ->columns([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 3,
                                'lg' => 3,
                            ])
                            ->schema([
                                TextInput::make('connection')
                                    ->label(trans('strings.console.settings.node.sftp_connection'))
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->suffixAction(fn () => request()->isSecure() ? CopyAction::make() : null)
                                    ->hintAction(
                                        Action::make('connect_sftp')
                                            ->label(trans('strings.console.settings.node.sftp_calltoaction'))
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
                                    ->label(trans('strings.console.settings.node.sftp_username'))
                                    ->columnSpan(1)
                                    ->suffixAction(fn () => request()->isSecure() ? CopyAction::make() : null)
                                    ->disabled()
                                    ->formatStateUsing(fn (Server $server) => auth()->user()->username . '.' . $server->uuid_short),
                                Placeholder::make('password')
                                    ->columnSpan(1)
                                    ->content('Your SFTP password is the same as the password you use to access this panel.'),
                            ]),
                    ]),
                Section::make(trans('strings.console.settings.reinstall.heading'))
                    ->hidden(fn () => !auth()->user()->can(Permission::ACTION_SETTINGS_REINSTALL, $server))
                    ->collapsible()
                    ->footerActions([
                        Action::make('reinstall')
                            ->color('danger')
                            ->disabled(fn () => !auth()->user()->can(Permission::ACTION_SETTINGS_REINSTALL, $server))
                            ->label(trans('strings.console.settings.reinstall_btn'))
                            ->requiresConfirmation()
                            ->modalHeading('Are you sure you want to reinstall the server?')
                            ->modalDescription('Some files may be deleted or modified during this process, please back up your data before continuing.')
                            ->modalSubmitActionLabel('Yes, Reinstall')
                            ->action(function (Server $server, ReinstallServerService $reinstallService) {
                                abort_unless(auth()->user()->can(Permission::ACTION_SETTINGS_REINSTALL, $server), 403);

                                try {
                                    $reinstallService->handle($server);
                                } catch (Exception $exception) {
                                    report($exception);

                                    Notification::make()
                                        ->danger()
                                        ->title('Server Reinstall failed')
                                        ->body($exception->getMessage())
                                        ->send();

                                    return;
                                }

                                Activity::event('server:settings.reinstall')
                                    ->log();

                                Notification::make()
                                    ->success()
                                    ->title('Server Reinstall started')
                                    ->send();

                                redirect(Console::getUrl());
                            }),
                    ])
                    ->footerActionsAlignment(Alignment::Right)
                    ->schema([
                        Placeholder::make('')
                            ->label('Reinstalling your server will stop it, and then re-run the installation script that initially set it up.'),
                        Placeholder::make('')
                            ->label('Some files may be deleted or modified during this process, please back up your data before continuing.'),
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
                ->success()
                ->title('Updated Server Name')
                ->body(fn () => $original . ' -> ' . $name)
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->danger()
                ->title('Failed')
                ->body($exception->getMessage())
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
                ->success()
                ->title('Updated Server Description')
                ->body(fn () => $original . ' -> ' . $description)
                ->send();
        } catch (Exception $exception) {
            Notification::make()
                ->danger()
                ->title('Failed')
                ->body($exception->getMessage())
                ->send();
        }
    }
}
