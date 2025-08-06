<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\Role;
use App\Models\User;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use App\Traits\Filament\CanModifyForm;
use App\Traits\Filament\CanModifyTable;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    use CanCustomizePages;
    use CanCustomizeRelations;
    use CanModifyForm;
    use CanModifyTable;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'tabler-users';

    protected static ?string $recordTitleAttribute = 'username';

    public static function getNavigationLabel(): string
    {
        return trans('admin/user.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/user.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/user.model_label_plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return !empty(auth()->user()->getCustomization()['top_navigation']) ? false : trans('admin/dashboard.user');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function defaultTable(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('picture')
                    ->visibleFrom('lg')
                    ->label('')
                    ->circular()
                    ->alignCenter()
                    ->defaultImageUrl(fn (User $user) => Filament::getUserAvatarUrl($user)),
                TextColumn::make('username')
                    ->label(trans('admin/user.username')),
                TextColumn::make('email')
                    ->label(trans('admin/user.email'))
                    ->icon('tabler-mail'),
                IconColumn::make('use_totp')
                    ->label('2FA')
                    ->visibleFrom('lg')
                    ->icon(fn (User $user) => $user->use_totp ? 'tabler-lock' : 'tabler-lock-open-off')
                    ->boolean(),
                TextColumn::make('roles.name')
                    ->label(trans('admin/user.roles'))
                    ->badge()
                    ->icon('tabler-users-group')
                    ->placeholder(trans('admin/user.no_roles')),
                TextColumn::make('servers_count')
                    ->counts('servers')
                    ->icon('tabler-server')
                    ->label(trans('admin/user.servers')),
                TextColumn::make('subusers_count')
                    ->visibleFrom('sm')
                    ->label(trans('admin/user.subusers'))
                    ->counts('subusers')
                    ->icon('tabler-users'),
            ])
            ->actions([
                ViewAction::make()
                    ->hidden(fn ($record) => static::canEdit($record)),
                EditAction::make(),
            ])
            ->checkIfRecordIsSelectableUsing(fn (User $user) => auth()->user()->id !== $user->id && !$user->servers_count)
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function defaultForm(Form $form): Form
    {
        return $form
            ->columns(['default' => 1, 'lg' => 3])
            ->schema([
                TextInput::make('username')
                    ->label(trans('admin/user.username'))
                    ->alphaNum()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->minLength(3)
                    ->maxLength(255),
                TextInput::make('email')
                    ->label(trans('admin/user.email'))
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->label(trans('admin/user.password'))
                    ->hintIcon(fn ($operation) => $operation === 'create' ? 'tabler-question-mark' : null)
                    ->hintIconTooltip(fn ($operation) => $operation === 'create' ? trans('admin/user.password_help') : null)
                    ->password(),
                CheckboxList::make('roles')
                    ->hidden(fn (User $user) => $user->isRootAdmin())
                    ->relationship('roles', 'name', fn (Builder $query) => $query->whereNot('id', Role::getRootAdmin()->id))
                    ->saveRelationshipsUsing(fn (User $user, array $state) => $user->syncRoles(collect($state)->map(fn ($role) => Role::findById($role))))
                    ->dehydrated()
                    ->label(trans('admin/user.admin_roles'))
                    ->columnSpanFull()
                    ->bulkToggleable(false),
                CheckboxList::make('root_admin_role')
                    ->visible(fn (User $user) => $user->isRootAdmin())
                    ->disabled()
                    ->options([
                        'root_admin' => Role::ROOT_ADMIN,
                    ])
                    ->descriptions([
                        'root_admin' => trans('admin/role.root_admin', ['role' => Role::ROOT_ADMIN]),
                    ])
                    ->formatStateUsing(fn () => ['root_admin'])
                    ->dehydrated(false)
                    ->label(trans('admin/user.admin_roles'))
                    ->columnSpanFull(),
            ]);
    }

    /** @return class-string<RelationManager>[] */
    public static function getDefaultRelations(): array
    {
        return [
            RelationManagers\ServersRelationManager::class,
        ];
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
