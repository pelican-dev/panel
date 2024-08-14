<?php

namespace App\Filament\App\Pages;

use App\Enums\ServerState;
use App\Exceptions\Http\Connection\DaemonConnectionException;
use App\Facades\Activity;
use App\Models\Permission;
use App\Models\Server;
use App\Repositories\Daemon\DaemonRepository;
use App\Repositories\Daemon\DaemonServerRepository;
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
use GuzzleHttp\Exception\TransferException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class Settings extends SimplePage
{
    protected static ?string $navigationIcon = 'tabler-settings';
    protected static ?int $navigationSort = 9;

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
                Section::make('Server Information')// TODO: sftp details (read only)(name & description) debug info (node & server id)
                    ->label('Server Information')
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
                                    ->formatStateUsing(fn () => $server->name)
                                    ->afterStateUpdated(function ($state) {
                                        $data = [
                                            'name' => $state,
                                        ];
                                        $this->updateName($data);
                                    }),
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
                                    ->formatStateUsing(fn () => $server->description)
                                    ->afterStateUpdated(function ($state) {
                                        $data = [
                                            'description' => $state ?? '',
                                        ];
                                        $this->updateDescription($data);
                                    }),

                                TextInput::make('uuid')
                                    ->label('Server UUID')
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'md' => 3,
                                        'lg' => 5,
                                    ])
                                    ->disabled()
                                    ->formatStateUsing(fn () => $server->uuid),
                                TextInput::make('id')
                                    ->label('Server ID')
                                    ->disabled()
                                    ->formatStateUsing(fn () => $server->id)
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
                                    ->formatStateUsing(fn () => $server->backups->count() . ' of ' . $server->backup_limit),
                                TextInput::make('database_limit')
                                    ->label('Database Limit')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn () => $server->databases->count() . ' of ' . $server->database_limit),
                                TextInput::make('allocation_limit')
                                    ->label('Allocation Limit')
                                    ->columnSpan(1)
                                    ->disabled()
                                    ->formatStateUsing(fn () => $server->allocations->count() . ' of ' .  $server->allocation_limit + 1),
                            ]),
                        Section::make('Allocations'), //TODO
                    ]),
                Section::make('Node Information')
                    ->label('')
                    ->schema([
                        TextInput::make('node_name')
                            ->label('Node Name')
                            ->disabled()
                            ->formatStateUsing(fn () => $server->node->name),
                        Fieldset::make('SFTP Information')
                            ->label('SFTP Information')
                            ->schema([
                                TextInput::make('connection')
                                    ->label('Connection')
                                    ->disabled()
                                    ->hintActions([
                                        Action::make('connect_sftp')
                                            ->label('Connect to SFTP')
                                            ->color('success')
                                            ->icon('tabler-plug')
                                            ->url(function () use ($server) {
                                                if ($server->node->daemon_sftp_alias) {
                                                    return 'sftp://' . auth()->user()->username . '.' . $server->uuid_short . '@' . $server->node->daemon_sftp_alias . ':' . $server->node->daemon_sftp;
                                                } else {
                                                    return 'sftp://' . auth()->user()->username . '.' . $server->uuid_short . '@' . $server->node->fqdn . ':' . $server->node->daemon_sftp;
                                                }
                                            }),
                                    ])
                                    ->formatStateUsing(function () use ($server) {
                                        if ($server->node->daemon_sftp_alias) {
                                            return 'sftp://' . $server->node->daemon_sftp_alias . ':' . $server->node->daemon_sftp;
                                        } else {
                                            return 'sftp://' . $server->node->fqdn . ':' . $server->node->daemon_sftp;
                                        }
                                    }),
                                TextInput::make('username')
                                    ->label('Username')
                                    ->disabled()
                                    ->formatStateUsing(fn () => auth()->user()->username . '.' . $server->uuid_short),

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
                            ->action(function () {
                                /** @var Server $server */
                                $server = Filament::getTenant();

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
    protected function authorizeAccess(): void
    {
        // not sure we need this? we can protect the items themselves.
    }

    public function updateName(array $data): null
    {
        /** @var Server $server */
        $server = Filament::getTenant();
        $original = $server->name;
        abort_unless(!auth()->user()->can(Permission::ACTION_SETTINGS_RENAME), 403);

        try {
            $server->forceFill([
                'name' => Arr::get($data, 'name'),
            ])->saveOrFail();

            if ($server->name !== $data['name']) {
                Activity::event('server:settings.rename')
                    ->property(['old' => $original, 'new' => $data['name']])
                    ->log();
            }
            Notification::make()
                ->success()
                ->duration(5000) // 5 seconds
                ->title('Updated Server Name')
                ->body(fn () => $original . ' -> ' . $data['name'])
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->danger()
                ->title('Failed')
                ->body($e->getMessage())
                ->send();
        }

        return null;
    }
    public function updateDescription(array $data): null
    {
        /** @var Server $server */
        $server = Filament::getTenant();
        $original = $server->description;
        abort_unless(!auth()->user()->can(Permission::ACTION_SETTINGS_RENAME), 403);

        try {
            $server->forceFill([
                'description' => Arr::get($data, 'description'),
            ])->saveOrFail();

            if ($server->name !== $data['description']) {
                Activity::event('server:settings.description')
                    ->property(['old' => $original, 'new' => $data['description']])
                    ->log();
            }
            Notification::make()
                ->success()
                ->duration(5000) // 5 seconds
                ->title('Updated Server Description')
                ->body(fn () => $original . ' -> ' . $data['description'])
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->danger()
                ->title('Failed')
                ->body($e->getMessage())
                ->send();
        }

        return null;
    }
}
