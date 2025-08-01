<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\UserResource\Pages;
use App\Models\Permission;
use App\Models\Server;
use App\Models\User;
use App\Services\Subusers\SubuserDeletionService;
use App\Services\Subusers\SubuserUpdateService;
use App\Traits\Filament\BlockAccessInConflict;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use App\Traits\Filament\CanModifyTable;
use App\Traits\Filament\HasLimitBadge;
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
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    use BlockAccessInConflict;
    use CanCustomizePages;
    use CanCustomizeRelations;
    use CanModifyTable;
    use HasLimitBadge;

    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'tabler-users';

    protected static ?string $tenantOwnershipRelationshipName = 'subServers';

    protected static function getBadgeCount(): int
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $server->subusers->count();
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

    public static function defaultTable(Table $table): Table
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $tabs = [];
        $permissionsArray = [];

        foreach (Permission::permissionData() as $data) {
            $options = [];
            $descriptions = [];

            foreach ($data['permissions'] as $permission) {
                $options[$permission] = str($permission)->headline();
                $descriptions[$permission] = trans('server/user.permissions.' . $data['name'] . '_' . str($permission)->replace('-', '_'));
                $permissionsArray[$data['name']][] = $permission;
            }

            $tabs[] = Tab::make(str($data['name'])->headline())
                ->schema([
                    Section::make()
                        ->description(trans('server/user.permissions.' . $data['name'] . '_desc'))
                        ->icon($data['icon'])
                        ->schema([
                            CheckboxList::make($data['name'])
                                ->label('')
                                ->bulkToggleable()
                                ->columns(2)
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
                    ->label(trans('server/user.username'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(trans('server/user.email'))
                    ->searchable(),
                TextColumn::make('permissions')
                    ->label(trans('server/user.permissions.title'))
                    ->state(fn (User $user) => count($server->subusers->where('user_id', $user->id)->first()->permissions)),
            ])
            ->actions([
                DeleteAction::make()
                    ->label(trans('server/user.delete'))
                    ->hidden(fn (User $user) => auth()->user()->id === $user->id)
                    ->action(function (User $user, SubuserDeletionService $subuserDeletionService) use ($server) {
                        $subuser = $server->subusers->where('user_id', $user->id)->first();
                        $subuserDeletionService->handle($subuser, $server);

                        Notification::make()
                            ->title(trans('server/user.notification_delete'))
                            ->success()
                            ->send();
                    }),
                EditAction::make()
                    ->label(trans('server/user.edit'))
                    ->hidden(fn (User $user) => auth()->user()->id === $user->id)
                    ->authorize(fn () => auth()->user()->can(Permission::ACTION_USER_UPDATE, $server))
                    ->modalHeading(fn (User $user) => trans('server/user.editing', ['user' => $user->email]))
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
                            ->title(trans('server/user.notification_edit'))
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
                                        ->label(trans('server/user.assign_all'))
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
                    ])
                    ->mutateRecordDataUsing(function ($data, User $user) use ($server) {
                        $permissionsArray = $server->subusers->where('user_id', $user->id)->first()->permissions;

                        $transformedPermissions = [];

                        foreach ($permissionsArray as $permission) {
                            [$group, $action] = explode('.', $permission, 2);
                            $transformedPermissions[$group][] = $action;
                        }

                        foreach ($transformedPermissions as $key => $value) {
                            $data[$key] = $value;
                        }

                        return $data;
                    }),
            ]);
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return trans('server/user.title');
    }
}
