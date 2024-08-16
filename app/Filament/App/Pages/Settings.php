<?php

namespace App\Filament\App\Pages;

use App\Enums\ServerState;
use App\Exceptions\Http\Connection\DaemonConnectionException;
use App\Facades\Activity;
use App\Models\Permission;
use App\Models\Server;
use Exception;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Support\Facades\Http;

class Settings extends ServerFormPage
{
    protected static ?string $navigationIcon = 'tabler-settings';
    protected static ?int $navigationSort = 9;

    public function form(Form $form): Form
    {
        return $form
            ->columns([
                'default' => 1,
                'sm' => 2,
                'md' => 4,
                'lg' => 6,
            ])
            ->schema([
                Section::make('Server Information')
                    ->columns([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 4,
                        'lg' => 6,
                    ])
                    ->schema([
                        Fieldset::make('Server')
                            ->label('Information')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Server Name')
                                    ->disabled(auth()->user()->can(Permission::ACTION_SETTINGS_RENAME))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 4,
                                        'lg' => 6,
                                    ])
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Server $server) => $this->updateName($state, $server)),
                                Textarea::make('description')
                                    ->label('Server Description')
                                    ->disabled(auth()->user()->can(Permission::ACTION_SETTINGS_RENAME))
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 4,
                                        'lg' => 6,
                                    ])
                                    ->autosize()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Server $server) => $this->updateDescription($state, $server)),
                                TextInput::make('uuid')
                                    ->label('Server UUID')
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'md' => 3,
                                        'lg' => 5,
                                    ])
                                    ->disabled(),
                                TextInput::make('id')
                                    ->label('Server ID')
                                    ->disabled()
                                    ->columnSpan(1),
                            ]),
                        Fieldset::make('Limits')
                            ->label('Limits')
                            ->columns([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 3,
                                'lg' => 3,
                            ])
                            ->schema([
                                TextInput::make('backup_limit')
                                    ->label('Backup Limit')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? 'No Backups can be created' : $server->backups->count() . ' of ' . $state),
                                TextInput::make('database_limit')
                                    ->label('Database Limit')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? 'No Databases can be created' : $server->databases->count() . ' of ' . $state),
                                TextInput::make('allocation_limit')
                                    ->label('Allocation Limit')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Server $server) => !$state ? 'No additional Allocations can be created' : $server->allocations->count() . ' of ' . ($state + 1)),
                            ]),
                    ]),
                Section::make('Node Information')
                    ->schema([
                        TextInput::make('node.name') // TODO: not working :(
                            ->label('Node Name')
                            ->disabled(),
                        Fieldset::make('SFTP Information')
                            ->label('SFTP Information')
                            ->columns([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 3,
                                'lg' => 3,
                            ])
                            ->schema([
                                TextInput::make('connection')
                                    ->label('Connection')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->hintActions([
                                        Action::make('connect_sftp')
                                            ->label('Connect to SFTP')
                                            ->color('success')
                                            ->icon('tabler-plug')
                                            ->url(function (Server $server) {
                                                $fqdn = $server->node->daemon_sftp_alias ?? $server->node->fqdn;

                                                return 'sftp://' . auth()->user()->username . '.' . $server->uuid_short . '@' . $fqdn . ':' . $server->node->daemon_sftp;
                                            }),
                                    ])
                                    ->formatStateUsing(function (Server $server) {
                                        $fqdn = $server->node->daemon_sftp_alias ?? $server->node->fqdn;

                                        return 'sftp://' . auth()->user()->username . '.' . $server->uuid_short . '@' . $fqdn . ':' . $server->node->daemon_sftp;
                                    }),
                                TextInput::make('username')
                                    ->label('Username')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn (Server $server) => auth()->user()->username . '.' . $server->uuid_short),
                                Placeholder::make('password')
                                    ->columnSpan(1)
                                    ->content('Your SFTP password is the same as the password you use to access this panel.'),
                            ]),
                    ]),
                Section::make('Reinstall Server')
                    ->collapsible()->collapsed()
                    ->footerActions([
                        Action::make('reinstall')
                            ->color('danger')
                            ->disabled(auth()->user()->can(Permission::ACTION_SETTINGS_REINSTALL))
                            ->label('Reinstall')
                            ->requiresConfirmation()
                            ->modalHeading('Are you sure you want to reinstall the server?')
                            ->modalDescription('Some files may be deleted or modified during this process, please back up your data before continuing.')
                            ->modalSubmitActionLabel('Yes, Reinstall')
                            ->action(function (Server $server) {
                                abort_unless(!auth()->user()->can(Permission::ACTION_SETTINGS_REINSTALL), 403);

                                $server->fill(['status' => ServerState::Installing])->save();
                                try {
                                    Http::daemon($server->node)->post(sprintf(
                                        '/api/servers/%s/reinstall',
                                        $server->uuid
                                    ));
                                } catch (TransferException $exception) {
                                    throw new DaemonConnectionException($exception);
                                }

                                Activity::event('server:settings.reinstall')
                                    ->log();

                                Notification::make()
                                    ->success()
                                    ->title('Server Reinstall Started')
                                    ->send();
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
        abort_unless(!auth()->user()->can(Permission::ACTION_SETTINGS_RENAME), 403);

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
                ->duration(5000) // 5 seconds
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
        abort_unless(!auth()->user()->can(Permission::ACTION_SETTINGS_RENAME), 403);

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
                ->duration(5000) // 5 seconds
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
