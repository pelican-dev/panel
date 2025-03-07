<?php

namespace App\Filament\Server\Resources\UserResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\UserResource;
use App\Models\Permission;
use App\Models\Server;
use App\Services\Subusers\SubuserCreationService;
use Exception;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions as assignAll;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return [
            Actions\CreateAction::make('invite')
                ->label('Invite User')
                ->createAnother(false)
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_USER_CREATE, $server))
                ->form([
                    Grid::make()
                        ->columnSpanFull()
                        ->columns([
                            'default' => 1,
                            'sm' => 1,
                            'md' => 5,
                            'lg' => 6,
                        ])
                        ->schema([
                            TextInput::make('email')
                                ->email()
                                ->inlineLabel()
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 4,
                                    'lg' => 5,
                                ])
                                ->required(),
                            assignAll::make([
                                Action::make('assignAll')
                                    ->label('Assign All')
                                    ->action(function (Set $set, Get $get) {
                                        $permissions = [
                                            'control' => [
                                                'console',
                                                'start',
                                                'stop',
                                                'restart',
                                            ],
                                            'user' => [
                                                'read',
                                                'create',
                                                'update',
                                                'delete',
                                            ],
                                            'file' => [
                                                'read',
                                                'read-content',
                                                'create',
                                                'update',
                                                'delete',
                                                'archive',
                                                'sftp',
                                            ],
                                            'backup' => [
                                                'read',
                                                'create',
                                                'delete',
                                                'download',
                                                'restore',
                                            ],
                                            'allocation' => [
                                                'read',
                                                'create',
                                                'update',
                                                'delete',
                                            ],
                                            'startup' => [
                                                'read',
                                                'update',
                                                'docker-image',
                                            ],
                                            'database' => [
                                                'read',
                                                'create',
                                                'update',
                                                'delete',
                                                'view_password',
                                            ],
                                            'schedule' => [
                                                'read',
                                                'create',
                                                'update',
                                                'delete',
                                            ],
                                            'settings' => [
                                                'rename',
                                                'reinstall',
                                            ],
                                            'activity' => [
                                                'read',
                                            ],
                                        ];

                                        foreach ($permissions as $key => $value) {
                                            $currentValues = $get($key) ?? [];
                                            $allValues = array_unique(array_merge($currentValues, $value));
                                            $set($key, $allValues);
                                        }
                                    }),
                            ])
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 1,
                                ]),
                            Tabs::make()
                                ->columnSpanFull()
                                ->schema([
                                    Tabs\Tab::make('Console')
                                        ->schema([
                                            Section::make()
                                                ->description(trans('server/users.permissions.control_desc'))
                                                ->icon('tabler-terminal-2')
                                                ->schema([
                                                    CheckboxList::make('control')
                                                        ->bulkToggleable()
                                                        ->label('')
                                                        ->columns(2)
                                                        ->options([
                                                            'console' => 'Console',
                                                            'start' => 'Start',
                                                            'stop' => 'Stop',
                                                            'restart' => 'Restart',
                                                        ])
                                                        ->descriptions([
                                                            'console' => trans('server/users.permissions.control_console'),
                                                            'start' => trans('server/users.permissions.control_start'),
                                                            'stop' => trans('server/users.permissions.control_stop'),
                                                            'restart' => trans('server/users.permissions.control_restart'),
                                                        ]),
                                                ]),
                                        ]),
                                    Tabs\Tab::make('User')
                                        ->schema([
                                            Section::make()
                                                ->description(trans('server/users.permissions.user_desc'))
                                                ->icon('tabler-users')
                                                ->schema([
                                                    CheckboxList::make('user')
                                                        ->bulkToggleable()
                                                        ->label('')
                                                        ->columns(2)
                                                        ->options([
                                                            'read' => 'Read',
                                                            'create' => 'Create',
                                                            'update' => 'Update',
                                                            'delete' => 'Delete',
                                                        ])
                                                        ->descriptions([
                                                            'create' => trans('server/users.permissions.user_create'),
                                                            'read' => trans('server/users.permissions.user_read'),
                                                            'update' => trans('server/users.permissions.user_update'),
                                                            'delete' => trans('server/users.permissions.user_delete'),
                                                        ]),
                                                ]),
                                        ]),
                                    Tabs\Tab::make('File')
                                        ->schema([
                                            Section::make()
                                                ->description(trans('server/users.permissions.file_desc'))
                                                ->icon('tabler-folders')
                                                ->schema([
                                                    CheckboxList::make('file')
                                                        ->bulkToggleable()
                                                        ->label('')
                                                        ->columns(2)
                                                        ->options([
                                                            'read' => 'Read',
                                                            'read-content' => 'Read Content',
                                                            'create' => 'Create',
                                                            'update' => 'Update',
                                                            'delete' => 'Delete',
                                                            'archive' => 'Archive',
                                                            'sftp' => 'SFTP',
                                                        ])
                                                        ->descriptions([
                                                            'create' => trans('server/users.permissions.file_create'),
                                                            'read' => trans('server/users.permissions.file_read'),
                                                            'read-content' => trans('server/users.permissions.file_read_content'),
                                                            'update' => trans('server/users.permissions.file_update'),
                                                            'delete' => trans('server/users.permissions.file_delete'),
                                                            'archive' => trans('server/users.permissions.file_archive'),
                                                            'sftp' => trans('server/users.permissions.file_sftp'),
                                                        ]),
                                                ]),
                                        ]),
                                    Tabs\Tab::make('Backup')
                                        ->schema([
                                            Section::make()
                                                ->description(trans('server/users.permissions.backup_desc'))
                                                ->icon('tabler-download')
                                                ->schema([
                                                    CheckboxList::make('backup')
                                                        ->bulkToggleable()
                                                        ->label('')
                                                        ->columns(2)
                                                        ->options([
                                                            'read' => 'Read',
                                                            'create' => 'Create',
                                                            'delete' => 'Delete',
                                                            'download' => 'Download',
                                                            'restore' => 'Restore',
                                                        ])
                                                        ->descriptions([
                                                            'create' => trans('server/users.permissions.backup_create'),
                                                            'read' => trans('server/users.permissions.backup_read'),
                                                            'delete' => trans('server/users.permissions.backup_delete'),
                                                            'download' => trans('server/users.permissions.backup_download'),
                                                            'restore' => trans('server/users.permissions.backup_restore'),
                                                        ]),
                                                ]),
                                        ]),
                                    Tabs\Tab::make('Allocation')
                                        ->schema([
                                            Section::make()
                                                ->description(trans('server/users.permissions.allocation_desc'))
                                                ->icon('tabler-network')
                                                ->schema([
                                                    CheckboxList::make('allocation')
                                                        ->bulkToggleable()
                                                        ->label('')
                                                        ->columns(2)
                                                        ->options([
                                                            'read' => 'Read',
                                                            'create' => 'Create',
                                                            'update' => 'Update',
                                                            'delete' => 'Delete',
                                                        ])
                                                        ->descriptions([
                                                            'read' => trans('server/users.permissions.allocation_read'),
                                                            'create' => trans('server/users.permissions.allocation_create'),
                                                            'update' => trans('server/users.permissions.allocation_update'),
                                                            'delete' => trans('server/users.permissions.allocation_delete'),
                                                        ]),
                                                ]),
                                        ]),
                                    Tabs\Tab::make('Startup')
                                        ->schema([
                                            Section::make()
                                                ->description(trans('server/users.permissions.startup_desc'))
                                                ->icon('tabler-question-mark')
                                                ->schema([
                                                    CheckboxList::make('startup')
                                                        ->bulkToggleable()
                                                        ->label('')
                                                        ->columns(2)
                                                        ->options([
                                                            'read' => 'Read',
                                                            'update' => 'Update',
                                                            'docker-image' => 'Docker Image',
                                                        ])
                                                        ->descriptions([
                                                            'read' => trans('server/users.permissions.startup_read'),
                                                            'update' => trans('server/users.permissions.startup_update'),
                                                            'docker-image' => trans('server/users.permissions.startup_docker_image'),
                                                        ]),
                                                ]),
                                        ]),
                                    Tabs\Tab::make('Database')
                                        ->schema([
                                            Section::make()
                                                ->description(trans('server/users.permissions.database_desc'))
                                                ->icon('tabler-database')
                                                ->schema([
                                                    CheckboxList::make('database')
                                                        ->bulkToggleable()
                                                        ->label('')
                                                        ->columns(2)
                                                        ->options([
                                                            'read' => 'Read',
                                                            'create' => 'Create',
                                                            'update' => 'Update',
                                                            'delete' => 'Delete',
                                                            'view_password' => 'View Password',
                                                        ])
                                                        ->descriptions([
                                                            'read' => trans('server/users.permissions.database_read'),
                                                            'create' => trans('server/users.permissions.database_create'),
                                                            'update' => trans('server/users.permissions.database_update'),
                                                            'delete' => trans('server/users.permissions.database_delete'),
                                                            'view_password' => trans('server/users.permissions.database_view_password'),
                                                        ]),
                                                ]),
                                        ]),
                                    Tabs\Tab::make('Schedule')
                                        ->schema([
                                            Section::make()
                                                ->description(trans('server/users.permissions.schedule_desc'))
                                                ->icon('tabler-clock')
                                                ->schema([
                                                    CheckboxList::make('schedule')
                                                        ->bulkToggleable()
                                                        ->label('')
                                                        ->columns(2)
                                                        ->options([
                                                            'read' => 'Read',
                                                            'create' => 'Create',
                                                            'update' => 'Update',
                                                            'delete' => 'Delete',
                                                        ])
                                                        ->descriptions([
                                                            'read' => trans('server/users.permissions.schedule_read'),
                                                            'create' => trans('server/users.permissions.schedule_create'),
                                                            'update' => trans('server/users.permissions.schedule_update'),
                                                            'delete' => trans('server/users.permissions.schedule_delete'),
                                                        ]),
                                                ]),
                                        ]),
                                    Tabs\Tab::make('Settings')
                                        ->schema([
                                            Section::make()
                                                ->description(trans('server/users.permissions.settings_desc'))
                                                ->icon('tabler-settings')
                                                ->schema([
                                                    CheckboxList::make('settings')
                                                        ->bulkToggleable()
                                                        ->label('')
                                                        ->columns(2)
                                                        ->options([
                                                            'rename' => 'Rename',
                                                            'reinstall' => 'Reinstall',
                                                            'activity' => 'Activity',
                                                        ])
                                                        ->descriptions([
                                                            'rename' => trans('server/users.permissions.setting_rename'),
                                                            'reinstall' => trans('server/users.permissions.setting_reinstall'),
                                                            'activity' => trans('server/users.permissions.activity_desc'),
                                                        ]),
                                                ]),
                                        ]),
                                    Tabs\Tab::make('Activity')
                                        ->schema([
                                            Section::make()
                                                ->description(trans('server/users.permissions.activity_desc'))
                                                ->icon('tabler-stack')
                                                ->schema([
                                                    CheckboxList::make('activity')
                                                        ->bulkToggleable()
                                                        ->label('')
                                                        ->columns(2)
                                                        ->options([
                                                            'read' => 'Read',
                                                        ])
                                                        ->descriptions([
                                                            'read' => trans('server/users.permissions.activity_read'),
                                                        ]),
                                                ]),
                                        ]),
                                ]),

                        ]),
                ])
                ->modalHeading('Invite User')
                ->modalSubmitActionLabel('Invite')
                ->action(function (array $data, SubuserCreationService $service) use ($server) {
                    $email = strtolower($data['email']);

                    $permissions = collect($data)
                        ->forget('email')
                        ->flatMap(fn ($permissions, $key) => collect($permissions)->map(fn ($permission) => "$key.$permission"))
                        ->push(Permission::ACTION_WEBSOCKET_CONNECT)
                        ->unique()
                        ->all();

                    try {
                        $subuser = $service->handle($server, $email, $permissions);

                        Activity::event('server:subuser.create')
                            ->subject($subuser->user)
                            ->property([
                                'email' => $data['email'],
                                'permissions' => $permissions,
                            ]);

                        Notification::make()
                            ->title('User Invited!')
                            ->success()
                            ->send();
                    } catch (Exception $exception) {
                        Notification::make()
                            ->title('Failed')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();
                    }

                    return redirect(self::getUrl(tenant: $server));
                }),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
