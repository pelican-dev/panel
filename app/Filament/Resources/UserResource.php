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

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'username';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('external_id')
                    ->maxLength(191),
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('name_first')
                    ->maxLength(191),
                Forms\Components\TextInput::make('name_last')
                    ->maxLength(191),
                Forms\Components\Textarea::make('password')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('language')
                    ->required()
                    ->maxLength(5)
                    ->default('en'),
                Forms\Components\TextInput::make('root_admin')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('use_totp')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('totp_secret')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('totp_authenticated_at'),
                Forms\Components\Toggle::make('gravatar')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('external_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_first')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_last')
                    ->searchable(),
                Tables\Columns\TextColumn::make('language')
                    ->searchable(),
                Tables\Columns\TextColumn::make('root_admin')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('use_totp')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('totp_authenticated_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('gravatar')
                    ->boolean(),
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
