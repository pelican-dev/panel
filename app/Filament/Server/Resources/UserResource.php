<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\UserResource\Pages;
use App\Models\Permission;
use App\Models\Server;
use App\Models\User;
use App\Services\Subusers\SubuserDeletionService;
use App\Services\Subusers\SubuserUpdateService;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\DeleteAction;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'tabler-users';

    protected static ?string $tenantOwnershipRelationshipName = 'subServers';

    public static function getNavigationBadge(): string
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return (string) $server->subusers->count();
    }

    // TODO: find better way handle server conflict state
    public static function canAccess(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        if ($server->isInConflictState()) {
            return false;
        }

        return parent::canAccess();
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::ACTION_USER_READ, Filament::getTenant());
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::ACTION_USER_CREATE, Filament::getTenant());
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_USER_UPDATE, Filament::getTenant());
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_USER_DELETE, Filament::getTenant());
    }

    public static function table(Table $table): Table
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $tabs = array();

        $tabArray = [
            [
                'name' => 'Console',
                'description' => trans('server/users.permissions.control_desc'),
                'icon' => 'tabler-terminal-2',
                'checkboxList' => [
                    'name' => 'control',
                    'columns' => 2,
                    'options' => [
                        [
                            'name' => 'Console',
                            'label' => 'console',
                            'description' => trans('server/users.permissions.control_console'),
                        ],
                        [
                            'name' => 'Start',
                            'label' => 'start',
                            'description' => trans('server/users.permissions.control_start'),
                        ],
                        [
                            'name' => 'Stop',
                            'label' => 'stop',
                            'description' => trans('server/users.permissions.control_stop'),
                        ],
                        [
                            'name' => 'Restart',
                            'label' => 'restart',
                            'description' => trans('server/users.permissions.control_restart'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'File',
                'description' => trans('server/users.permissions.file_desc'),
                'icon' => 'tabler-folders',
                'checkboxList' => [
                    'name' => 'file',
                    'columns' => 2,
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.file_create'),
                        ],
                        [
                            'name' => 'Read Content',
                            'label' => 'read-content',
                            'description' => trans('server/users.permissions.file_read_content'),
                        ],
                        [
                            'name' => 'Create',
                            'label' => 'create',
                            'description' => trans('server/users.permissions.backup_create'),
                        ],
                        [
                            'name' => 'Update',
                            'label' => 'update',
                            'description' => trans('server/users.permissions.file_update'),
                        ],
                        [
                            'name' => 'Delete',
                            'label' => 'delete',
                            'description' => trans('server/users.permissions.file_delete'),
                        ],
                        [
                            'name' => 'Archive',
                            'label' => 'archive',
                            'description' => trans('server/users.permissions.file_archive'),
                        ],
                        [
                            'name' => 'SFTP',
                            'label' => 'sftp',
                            'description' => trans('server/users.permissions.file_sftp'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Backup',
                'description' => trans('server/users.permissions.backup_desc'),
                'icon' => 'tabler-download',
                'checkboxList' => [
                    'name' => 'backup',
                    'columns' => 2,
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.backup_read'),
                        ],
                        [
                            'name' => 'Create',
                            'label' => 'create',
                            'description' => trans('server/users.permissions.backup_create'),
                        ],
                        [
                            'name' => 'Delete',
                            'label' => 'delete',
                            'description' => trans('server/users.permissions.backup_delete'),
                        ],
                        [
                            'name' => 'Download',
                            'label' => 'download',
                            'description' => trans('server/users.permissions.backup_download'),
                        ],
                        [
                            'name' => 'Restore',
                            'label' => 'restore',
                            'description' => trans('server/users.permissions.backup_restore'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Allocation',
                'description' => trans('server/users.permissions.allocation_desc'),
                'icon' => 'tabler-network',
                'checkboxList' => [
                    'name' => 'allocation',
                    'columns' => 2,
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.allocation_read'),
                        ],
                        [
                            'name' => 'Create',
                            'label' => 'create',
                            'description' => trans('server/users.permissions.allocation_create'),
                        ],
                        [
                            'name' => 'Update',
                            'label' => 'update',
                            'description' => trans('server/users.permissions.allocation_update'),
                        ],
                        [
                            'name' => 'Delete',
                            'label' => 'delete',
                            'description' => trans('server/users.permissions.allocation_delete'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Startup',
                'description' => trans('server/users.permissions.startup_desc'),
                'icon' => 'tabler-question-mark',
                'checkboxList' => [
                    'name' => 'startup',
                    'columns' => 2,
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.startup_read'),
                        ],
                        [
                            'name' => 'Update',
                            'label' => 'update',
                            'description' => trans('server/users.permissions.startup_update'),
                        ],
                        [
                            'name' => 'Docker Image',
                            'label' => 'docker-image',
                            'description' => trans('server/users.permissions.startup_docker_image'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Database',
                'description' => trans('server/users.permissions.database_desc'),
                'icon' => 'tabler-database',
                'checkboxList' => [
                    'name' => 'database',
                    'columns' => 2,
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.database_read'),
                        ],
                        [
                            'name' => 'Create',
                            'label' => 'create',
                            'description' => trans('server/users.permissions.database_create'),
                        ],
                        [
                            'name' => 'Update',
                            'label' => 'update',
                            'description' => trans('server/users.permissions.database_update'),
                        ],
                        [
                            'name' => 'Delete',
                            'label' => 'delete',
                            'description' => trans('server/users.permissions.database_delete'),
                        ],
                        [
                            'name' => 'View Password',
                            'label' => 'view_password',
                            'description' => trans('server/users.permissions.database_view_password'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Schedule',
                'description' => trans('server/users.permissions.schedule_desc'),
                'icon' => 'tabler-clock',
                'checkboxList' => [
                    'name' => 'schedule',
                    'columns' => 2,
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.schedule_read'),
                        ],
                        [
                            'name' => 'Create',
                            'label' => 'create',
                            'description' => trans('server/users.permissions.schedule_create'),
                        ],
                        [
                            'name' => 'Update',
                            'label' => 'update',
                            'description' => trans('server/users.permissions.schedule_update'),
                        ],
                        [
                            'name' => 'Delete',
                            'label' => 'delete',
                            'description' => trans('server/users.permissions.schedule_delete'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Settings',
                'description' => trans('server/users.permissions.settings_desc'),
                'icon' => 'tabler-settings',
                'checkboxList' => [
                    'name' => 'settings',
                    'columns' => 2,
                    'options' => [
                        [
                            'name' => 'Rename',
                            'label' => 'rename',
                            'description' => trans('server/users.permissions.setting_rename'),
                        ],
                        [
                            'name' => 'Reinstall',
                            'label' => 'reinstall',
                            'description' => trans('server/users.permissions.setting_reinstall'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Activity',
                'description' => trans('server/users.permissions.activity_desc'),
                'icon' => 'tabler-stack',
                'checkboxList' => [
                    'name' => 'activity',
                    'columns' => 2,
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.activity_read'),
                        ],
                    ],
                ],
            ],
        ];

        $permissionsArray = [];
        foreach ($tabArray as $tab) {
            $options = [];
            $descriptions = [];
            foreach ($tab['checkboxList']['options'] as $option) {
                $options[$option['label']] = $option['name'];
                $descriptions[$option['label']] = $option['description'];
                $permissionsArray[$tab['checkboxList']['name']][] = $option['label'];
            }

            if ($tab['name'] == 'control') {
                $tabs[] = Tab::make($tab['name'])
                    ->schema([
                        Section::make()
                            ->description($tab['description'])
                            ->icon($tab['icon'])
                            ->schema([
                                CheckboxList::make($tab['checkboxList']['name'])
                                    ->formatStateUsing(function (User $user, Set $set) use ($server) {
                                        $permissionsArray = $server->subusers->where('user_id', $user->id)->first()->permissions;

                                        $transformedPermissions = [];

                                        foreach ($permissionsArray as $permission) {
                                            [$group, $action] = explode('.', $permission, 2);
                                            $transformedPermissions[$group][] = $action;
                                        }

                                        foreach ($transformedPermissions as $key => $value) {
                                            $set($key, $value);
                                        }

                                        return $transformedPermissions['control'] ?? [];
                                    })
                                    ->bulkToggleable()
                                    ->label('')
                                    ->columns($tab['checkboxList']['columns'])
                                    ->options($options)
                                    ->descriptions($descriptions),
                            ]),
                    ]);
                continue;
            }
            $tabs[] = Tab::make($tab['name'])
                ->schema([
                    Section::make()
                        ->description($tab['description'])
                        ->icon($tab['icon'])
                        ->schema([
                            CheckboxList::make($tab['checkboxList']['name'])
                                ->bulkToggleable()
                                ->label('')
                                ->columns($tab['checkboxList']['columns'])
                                ->options($options)
                                ->descriptions($descriptions),
                        ]),
                ]);
        }


        return $table
            ->paginated(false)
            ->searchable(false)
            ->columns([
                ImageColumn::make('picture')
                    ->visibleFrom('lg')
                    ->label('')
                    ->alignCenter()->circular()
                    ->defaultImageUrl(fn (User $user) => Filament::getUserAvatarUrl($user)),
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('permissions')
                    ->state(fn (User $user) => count($server->subusers->where('user_id', $user->id)->first()->permissions)),
            ])
            ->actions([
                DeleteAction::make()
                    ->label('Remove User')
                    ->hidden(fn (User $user) => auth()->user()->id === $user->id)
                    ->action(function (User $user, SubuserDeletionService $subuserDeletionService) use ($server) {
                        $subuser = $server->subusers->where('user_id', $user->id)->first();
                        $subuserDeletionService->handle($subuser, $server);

                        Notification::make()
                            ->title('User Deleted!')
                            ->success()
                            ->send();
                    }),
                EditAction::make()
                    ->label('Edit User')
                    ->hidden(fn (User $user) => auth()->user()->id === $user->id)
                    ->authorize(fn () => auth()->user()->can(Permission::ACTION_USER_UPDATE, $server))
                    ->modalHeading(fn (User $user) => 'Editing ' . $user->email)
                    ->action(function (array $data, SubuserUpdateService $subuserUpdateService, User $user) use ($server) {
                        $subuser = $server->subusers->where('user_id', $user->id)->first();

                        $permissions = collect($data)
                            ->forget('email')
                            ->flatMap(fn ($permissions, $key) => collect($permissions)->map(fn ($permission) => "$key.$permission"))
                            ->push(Permission::ACTION_WEBSOCKET_CONNECT)
                            ->unique()
                            ->all();

                        $subuserUpdateService->handle($subuser, $server, $permissions);

                        Notification::make()
                            ->title('User Updated!')
                            ->success()
                            ->send();

                        return redirect(self::getUrl(tenant: $server));
                    })
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
                                    ->inlineLabel()
                                    ->disabled()
                                    ->columnSpan([
                                        'default' => 1,
                                        'sm' => 1,
                                        'md' => 4,
                                        'lg' => 5,
                                    ]),
                                Actions::make([
                                    Action::make('assignAll')
                                        ->label('Assign All')
                                        ->action(function (Set $set) use ($permissionsArray) {
                                            $permissions = $permissionsArray;
                                            foreach ($permissions as $key => $value) {
                                                $allValues = array_unique($value);
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
                                    ->schema($tabs),
                            ]),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
        ];
    }
}
