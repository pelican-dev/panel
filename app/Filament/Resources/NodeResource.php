<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NodeResource\Pages;
use App\Filament\Resources\NodeResource\RelationManagers;
use App\Models\Node;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NodeResource extends Resource
{
    protected static ?string $model = Node::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('public')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(191),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('location_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('fqdn')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('scheme')
                    ->required()
                    ->maxLength(191)
                    ->default('https'),
                Forms\Components\Toggle::make('behind_proxy')
                    ->required(),
                Forms\Components\Toggle::make('maintenance_mode')
                    ->required(),
                Forms\Components\TextInput::make('memory')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('memory_overallocate')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('disk')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('disk_overallocate')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('upload_size')
                    ->required()
                    ->numeric()
                    ->default(100),
                Forms\Components\TextInput::make('daemon_token_id')
                    ->required()
                    ->maxLength(16),
                Forms\Components\TextInput::make('daemonListen')
                    ->required()
                    ->numeric()
                    ->default(8080),
                Forms\Components\TextInput::make('daemonSFTP')
                    ->required()
                    ->numeric()
                    ->default(2022),
                Forms\Components\TextInput::make('daemonBase')
                    ->required()
                    ->maxLength(191)
                    ->default('/home/daemon-files'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('public')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fqdn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('scheme')
                    ->searchable(),
                Tables\Columns\IconColumn::make('behind_proxy')
                    ->boolean(),
                Tables\Columns\IconColumn::make('maintenance_mode')
                    ->boolean(),
                Tables\Columns\TextColumn::make('memory')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('memory_overallocate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('disk')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('disk_overallocate')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('upload_size')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('daemon_token_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('daemonListen')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('daemonSFTP')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('daemonBase')
                    ->searchable(),
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
            'index' => Pages\ListNodes::route('/'),
            'create' => Pages\CreateNode::route('/create'),
            'edit' => Pages\EditNode::route('/{record}/edit'),
        ];
    }
}
