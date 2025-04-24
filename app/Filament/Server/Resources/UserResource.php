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
                                        ->action(function (Set $set) {
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
                                        Tab::make('Console')
                                            ->schema([
                                                Section::make()
                                                    ->description(trans('server/users.permissions.control_desc'))
                                                    ->icon('tabler-terminal-2')
                                                    ->schema([
                                                        CheckboxList::make('control')
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
                                        Tab::make('User')
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
                                        Tab::make('File')
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
                                        Tab::make('Backup')
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
                                        Tab::make('Allocation')
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
                                        Tab::make('Startup')
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
                                        Tab::make('Database')
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
                                        Tab::make('Schedule')
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
                                        Tab::make('Settings')
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
                                                            ])
                                                            ->descriptions([
                                                                'rename' => trans('server/users.permissions.setting_rename'),
                                                                'reinstall' => trans('server/users.permissions.setting_reinstall'),
                                                            ]),
                                                    ]),
                                            ]),
                                        Tab::make('Activity')
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
