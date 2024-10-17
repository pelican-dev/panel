<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\UserResource\Pages;
use App\Models\Permission;
use App\Models\Server;
use App\Models\Subuser;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions as assignAll;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Tables\Actions\DeleteAction;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?int $navigationSort = 7;

    protected static ?string $navigationIcon = 'tabler-users';

    protected static ?string $tenantOwnershipRelationshipName = 'subServers';

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->searchable(false)
            ->columns([
                ImageColumn::make('picture')
                    ->visibleFrom('lg')
                    ->label('')
                    ->extraImgAttributes(['class' => 'rounded-full'])
                    ->defaultImageUrl(fn (User $user) => 'https://gravatar.com/avatar/' . md5(strtolower($user->email))),
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('permissions')
                    ->state(function (User $user) {
                        /** @var Server $server */
                        $server = Filament::getTenant();

                        $permissions = Subuser::query()->where('user_id', $user->id)->where('server_id', $server->id)->first()->permissions;

                        return count($permissions);
                    }),
            ])
            ->actions([
                DeleteAction::make()
                    ->label('Remove User')
                    ->requiresConfirmation(),
                EditAction::make()
                    ->label('Edit User')
                    ->authorize(auth()->user()->can(Permission::ACTION_USER_UPDATE, Filament::getTenant()))
                    ->modalHeading(fn (User $user) => 'Editing ' . $user->email)
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
                                assignAll::make([
                                    Action::make('assignAll')
                                        ->label('Assign All')
                                        ->action(function (Set $set) {
                                            $permissions = [
                                                'control' => [
                                                    'console',
                                                    'start',
                                                    'stop',
                                                    'restart',
                                                    'kill',
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
                                                    'activity',
                                                ],
                                            ];

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
                                    ->schema([
                                        Tabs\Tab::make('Console')
                                            ->schema([
                                                Section::make()
                                                    ->description(trans('server/users.permissions.control_desc'))
                                                    ->icon('tabler-terminal-2')
                                                    ->schema([
                                                        CheckboxList::make('control')
                                                            ->formatStateUsing(function (User $user, Set $set) {
                                                                /** @var Server $server */
                                                                $server = Filament::getTenant();

                                                                $permissionsArray = Subuser::query()
                                                                    ->where('user_id', $user->id)
                                                                    ->where('server_id', $server->id)
                                                                    ->first()
                                                                    ->permissions;

                                                                $transformedPermissions = [];

                                                                foreach ($permissionsArray as $permission) {
                                                                    [$group, $action] = explode('.', $permission, 2);
                                                                    $transformedPermissions[$group][] = $action;
                                                                }

                                                                foreach ($transformedPermissions as $key => $value) {
                                                                    $set($key, $value);
                                                                }

                                                                return $transformedPermissions['control'];
                                                            })
                                                            ->bulkToggleable()
                                                            ->label('')
                                                            ->options([
                                                                'console' => 'Console',
                                                                'start' => 'Start',
                                                                'stop' => 'Stop',
                                                                'restart' => 'Restart',
                                                                'kill' => 'Kill',
                                                            ])
                                                            ->descriptions([
                                                                'console' => trans('server/users.permissions.control_console'),
                                                                'start' => trans('server/users.permissions.control_start'),
                                                                'stop' => trans('server/users.permissions.control_stop'),
                                                                'restart' => trans('server/users.permissions.control_restart'),
                                                                'kill' => trans('server/users.permissions.control_kill'),
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
                                                            ->options([
                                                                'rename' => 'Rename',
                                                                'reinstall' => 'Reinstall',
                                                                'activity' => 'Activity',
                                                            ])
                                                            ->descriptions([
                                                                'rename' => trans('server/users.permissions.setting_rename'),
                                                                'reinstall' => trans('server/users.permissions.setting_reinstall'),
                                                                'activity' => trans('server/users.permissions.setting_activity'),
                                                            ]),
                                                    ]),
                                            ]),
                                    ]),
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
