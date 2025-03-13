<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
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
        return trans('admin/dashboard.user');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('picture')
                    ->visibleFrom('lg')
                    ->label('')
                    ->extraImgAttributes(['class' => 'rounded-full'])
                    ->defaultImageUrl(fn (User $user) => 'https://gravatar.com/avatar/' . md5(strtolower($user->email))),
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

    public static function form(Form $form): Form
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
                    ->disableOptionWhen(fn (string $value): bool => $value == Role::getRootAdmin()->id)
                    ->relationship('roles', 'name')
                    ->dehydrated()
                    ->label(trans('admin/user.admin_roles'))
                    ->columnSpanFull()
                    ->bulkToggleable(false),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ServersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
