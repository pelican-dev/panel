<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getLabel(): string
    {
        return trans_choice('strings.users', 1);
    }

    protected static ?string $navigationIcon = 'tabler-users';

    protected static ?string $recordTitleAttribute = 'username';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('username')->required()->maxLength(191)->label(trans('strings.username')),
                    Forms\Components\TextInput::make('email')->email()->required()->maxLength(191)->label(trans('strings.email')),

                    Forms\Components\TextInput::make('name_first')
                        ->maxLength(191)
                        ->hidden(fn (string $operation): bool => $operation === 'create')
                        ->label(trans('strings.first_name')),
                    Forms\Components\TextInput::make('name_last')
                        ->maxLength(191)
                        ->hidden(fn (string $operation): bool => $operation === 'create')
                        ->label(trans('strings.last_name')),

                    Forms\Components\TextInput::make('password')
                        ->label(trans('strings.password'))
                        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->password(),

                    Forms\Components\ToggleButtons::make('root_admin')
                        ->label(trans('admin/user.root_admin'))
                        ->options([
                            false => trans('strings.no'),
                            true => trans('strings.admin'),
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
                        ->hint(fn (User $user) => $user->isLastRootAdmin() ? trans('admin/user.last_admin.hint') : '')
                        ->helperText(fn (User $user) => $user->isLastRootAdmin() ? trans('admin/user.last_admin.helper_text') : '')
                        ->hintColor('warning')
                        ->inline()
                        ->required()
                        ->default(false),

                    Forms\Components\Hidden::make('skipValidation')->default(true),
                    Forms\Components\Select::make('language')
                        ->label(trans('strings.language'))
                        ->required()
                        //->hidden()
                        ->default('en')
                        ->options(fn (User $user) => $user->getAvailableLanguages()),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->columns([
                Tables\Columns\ImageColumn::make('picture')
                    ->visibleFrom('lg')
                    ->label('')
                    ->extraImgAttributes(['class' => 'rounded-full'])
                    ->defaultImageUrl(fn (User $user) => 'https://gravatar.com/avatar/' . md5(strtolower($user->email))),
                Tables\Columns\TextColumn::make('external_id')
                    ->searchable()
                    ->hidden(),
                Tables\Columns\TextColumn::make('uuid')
                    ->label(trans('strings.uuid'))
                    ->hidden()
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->label(trans('strings.username'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(trans('strings.email'))
                    ->searchable()
                    ->icon('tabler-mail'),
                Tables\Columns\IconColumn::make('root_admin')
                    ->visibleFrom('md')
                    ->label(trans('strings.admin'))
                    ->boolean()
                    ->trueIcon('tabler-star')
                    ->falseIcon('tabler-star-off')
                    ->sortable(),
                Tables\Columns\IconColumn::make('use_totp')
                    ->label(trans('strings.2fa'))
                    ->visibleFrom('lg')
                    ->icon(fn (User $user) => $user->use_totp ? 'tabler-lock' : 'tabler-lock-open-off')
                    ->boolean()->sortable(),
                Tables\Columns\TextColumn::make('servers_count')
                    ->counts('servers')
                    ->icon('tabler-server')
                    ->label(trans_choice('strings.servers', 2)),
                Tables\Columns\TextColumn::make('subusers_count')
                    ->visibleFrom('sm')
                    ->counts('subusers')
                    ->icon('tabler-users')
                    // ->formatStateUsing(fn (string $state, $record): string => (string) ($record->servers_count + $record->subusers_count))
                    ->label('Subuser Accounts'),
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
            RelationManagers\ServersRelationManager::class,
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
