<?php

namespace App\Filament\Admin\Resources\Users;

use App\Enums\CustomizationKey;
use App\Filament\Admin\Resources\Users\RelationManagers\ServersRelationManager;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Filament\Admin\Resources\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Users\Pages\ViewUser;
use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Models\Role;
use App\Models\User;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use App\Traits\Filament\CanModifyForm;
use App\Traits\Filament\CanModifyTable;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;

class UserResource extends Resource
{
    use CanCustomizePages;
    use CanCustomizeRelations;
    use CanModifyForm;
    use CanModifyTable;

    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-users';

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
        return auth()->user()->getCustomization(CustomizationKey::TopNavigation) ? false : trans('admin/dashboard.user');
    }

    public static function getNavigationBadge(): ?string
    {
        return ($count = static::getModel()::count()) > 0 ? (string) $count : null;
    }

    /**
     * @throws Exception
     */
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
                    ->label(trans('admin/user.username'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(trans('admin/user.email'))
                    ->searchable(),
                IconColumn::make('mfa_email_enabled')
                    ->label(trans('profile.tabs.2fa'))
                    ->visibleFrom('lg')
                    ->icon(fn (User $user) => filled($user->mfa_app_secret) ? 'tabler-qrcode' : ($user->mfa_email_enabled ? 'tabler-mail' : 'tabler-lock-open-off'))
                    ->tooltip(fn (User $user) => filled($user->mfa_app_secret) ? 'App' : ($user->mfa_email_enabled ? 'E-Mail' : 'None')),
                TextColumn::make('roles.name')
                    ->label(trans('admin/user.roles'))
                    ->badge()
                    ->placeholder(trans('admin/user.no_roles')),
                TextColumn::make('servers_count')
                    ->counts('servers')
                    ->label(trans('admin/user.servers')),
                TextColumn::make('subusers_count')
                    ->visibleFrom('sm')
                    ->label(trans('admin/user.subusers'))
                    ->counts('subusers'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->hidden(fn ($record) => static::canEdit($record)),
                EditAction::make(),
            ])
            ->checkIfRecordIsSelectableUsing(fn (User $user) => auth()->user()->id !== $user->id && !$user->servers_count)
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                TextInput::make('username')
                    ->label(trans('admin/user.username'))
                    ->required()
                    ->unique()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label(trans('admin/user.email'))
                    ->email()
                    ->required()
                    ->unique()
                    ->maxLength(255),
                TextInput::make('password')
                    ->label(trans('admin/user.password'))
                    ->hintIcon(fn ($operation) => $operation === 'create' ? 'tabler-question-mark' : null, fn ($operation) => $operation === 'create' ? trans('admin/user.password_help') : null)
                    ->password(),
                CheckboxList::make('roles')
                    ->hidden(fn (?User $user) => $user && $user->isRootAdmin())
                    ->relationship('roles', 'name', fn (Builder $query) => $query->whereNot('id', Role::getRootAdmin()->id))
                    ->saveRelationshipsUsing(fn (User $user, array $state) => $user->syncRoles(collect($state)->map(fn ($role) => Role::findById($role))))
                    ->dehydrated()
                    ->label(trans('admin/user.admin_roles'))
                    ->columnSpanFull()
                    ->bulkToggleable(false),
                CheckboxList::make('root_admin_role')
                    ->visible(fn (?User $user) => $user && $user->isRootAdmin())
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
            ServersRelationManager::class,
        ];
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
