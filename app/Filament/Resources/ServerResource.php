<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServerResource\Pages;
use App\Filament\Resources\ServerResource\RelationManagers;
use App\Models\Server;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    protected static ?string $recordTitleAttribute = 'name';

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
                Forms\Components\TextInput::make('uuidShort')
                    ->required()
                    ->maxLength(8),
                Forms\Components\Select::make('node_id')
                    ->relationship('node', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(191),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status')
                    ->maxLength(191),
                Forms\Components\Toggle::make('skip_scripts')
                    ->required(),
                Forms\Components\TextInput::make('owner_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('memory')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('swap')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('disk')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('io')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cpu')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('threads')
                    ->maxLength(191),
                Forms\Components\TextInput::make('oom_disabled')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('allocation_id')
                    ->relationship('allocation', 'id')
                    ->required(),
                Forms\Components\TextInput::make('egg_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('startup')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->required(),
                Forms\Components\TextInput::make('allocation_limit')
                    ->numeric(),
                Forms\Components\TextInput::make('database_limit')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('backup_limit')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\DateTimePicker::make('installed_at'),
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
                Tables\Columns\TextColumn::make('uuidShort')
                    ->searchable(),
                Tables\Columns\TextColumn::make('node.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\IconColumn::make('skip_scripts')
                    ->boolean(),
                Tables\Columns\TextColumn::make('owner_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('memory')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('swap')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('disk')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('io')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cpu')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('threads')
                    ->searchable(),
                Tables\Columns\TextColumn::make('oom_disabled')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('allocation.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('egg_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('allocation_limit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('database_limit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('backup_limit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('installed_at')
                    ->dateTime()
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
            'index' => Pages\ListServers::route('/'),
            'create' => Pages\CreateServer::route('/create'),
            'edit' => Pages\EditServer::route('/{record}/edit'),
        ];
    }
}
