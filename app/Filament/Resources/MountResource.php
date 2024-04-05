<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MountResource\Pages;
use App\Filament\Resources\MountResource\RelationManagers;
use App\Models\Mount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MountResource extends Resource
{
    protected static ?string $model = Mount::class;

    protected static ?string $navigationIcon = 'tabler-layers-linked';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->helperText('Unique name used to separate this mount from another.')
                    ->maxLength(191),
                Forms\Components\ToggleButtons::make('read_only')
                    ->label('Read only?')
                    ->helperText('Is the mount read only inside the container?')
                    ->options([
                        false => 'Writeable',
                        true => 'Read only',
                    ])
                    ->icons([
                        false => 'tabler-writing',
                        true => 'tabler-writing-off',
                    ])
                    ->colors([
                        false => 'warning',
                        true => 'success',
                    ])
                    ->inline()
                    ->default(false)
                    ->required(),
                Forms\Components\TextInput::make('source')
                    ->required()
                    ->helperText('File path on the host system to mount to a container.')
                    ->maxLength(191),
                Forms\Components\TextInput::make('target')
                    ->required()
                    ->helperText('Where the mount will be accessible inside a container.')
                    ->maxLength(191),
                Forms\Components\ToggleButtons::make('user_mountable')
                    ->hidden()
                    ->label('User mountable?')
                    ->options([
                        false => 'No',
                        true => 'Yes',
                    ])
                    ->icons([
                        false => 'tabler-user-cancel',
                        true => 'tabler-user-bolt',
                    ])
                    ->colors([
                        false => 'success',
                        true => 'warning',
                    ])
                    ->default(false)
                    ->inline()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->helperText('A longer description for this mount.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target')
                    ->searchable(),
                Tables\Columns\TextColumn::make('read_only')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_mountable')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListMounts::route('/'),
            'create' => Pages\CreateMount::route('/create'),
            'edit' => Pages\EditMount::route('/{record}/edit'),
        ];
    }
}
