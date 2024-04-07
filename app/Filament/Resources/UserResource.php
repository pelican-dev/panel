<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'tabler-users';

    protected static ?string $recordTitleAttribute = 'username';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('username')->required()->maxLength(191),
                Forms\Components\TextInput::make('email')->email()->required()->maxLength(191),

                Forms\Components\TextInput::make('name_first')
                    ->maxLength(191)
                    ->hidden(fn (string $operation): bool => $operation === 'create')
                    ->label('First Name'),
                Forms\Components\TextInput::make('name_last')
                    ->maxLength(191)
                    ->hidden(fn (string $operation): bool => $operation === 'create')
                    ->label('Last Name'),

                Forms\Components\TextInput::make('password')
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->password(),

                Forms\Components\ToggleButtons::make('root_admin')
                    ->label('Administrator (Root)')
                    ->options([
                        false => 'No',
                        true => 'Admin',
                    ])
                    ->colors([
                        false => 'primary',
                        true => 'danger',
                    ])
                    ->disableOptionWhen(function (string $operation, $value, User $user) {
                        if ($operation !== 'edit' || $value) {
                            return false;
                        }

                        return $user->isLastRootAdmin();
                    })
                    ->hint(fn (User $user) => $user->isLastRootAdmin() ? 'This is the last root administrator!' : '')
                    ->helperText(fn (User $user) => $user->isLastRootAdmin() ? 'You must have at least one root administrator in your system.' : '')
                    ->hintColor('warning')
                    ->inline()
                    ->required()
                    ->default(false),

                Forms\Components\Hidden::make('skipValidation')->default(true),
                Forms\Components\Select::make('language')
                    ->required()
                    ->hidden()
                    ->default('en')
                    ->options(fn (User $user) => $user->getAvailableLanguages()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('picture')
                    ->label('')
                    ->defaultImageUrl(fn (User $user) => 'https://gravatar.com/avatar/' . md5(strtolower($user->email))),
                Tables\Columns\TextColumn::make('external_id')
                    ->searchable()
                    ->hidden(),
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->hidden()
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->icon('tabler-mail'),
                Tables\Columns\TextColumn::make('name_first')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('First Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_last')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Last Name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('root_admin')
                    ->label('Admin')
                    ->boolean()
                    ->trueIcon('tabler-adjustments-check')
                    ->falseIcon('tabler-adjustments-cancel')
                    ->sortable(),
                Tables\Columns\IconColumn::make('use_totp')->label('2FA')
                    ->icon(fn (User $user) => $user->use_totp ? 'tabler-lock' : 'tabler-lock-open-off')
                    ->boolean()->sortable(),
                Tables\Columns\TextColumn::make('servers_count')
                    ->counts('servers')
                    ->icon('tabler-server')
                    ->label('Servers'),
                Tables\Columns\TextColumn::make('subusers_count')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->counts('subusers')
                    ->icon('tabler-users')
                    // ->formatStateUsing(fn (string $state, $record): string => (string) ($record->servers_count + $record->subusers_count))
                    ->label('Subuser Accounts'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
