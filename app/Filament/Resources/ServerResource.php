<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServerResource\Pages;
use App\Filament\Resources\ServerResource\RelationManagers;
use App\Models\Node;
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

    protected static ?string $navigationIcon = 'tabler-brand-docker';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(4)
            ->schema([
                Forms\Components\TextInput::make('external_id')->maxLength(191)->hidden(),
                Forms\Components\TextInput::make('name')
                    ->columnSpanFull()
                    ->required()
                    ->maxLength(191),
                Forms\Components\Select::make('node_id')
                    ->relationship('node', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('egg_id')
                    ->relationship('egg', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('owner_id')
                    ->relationship('user', 'username')
                    ->searchable()
                    ->preload()
                    ->default(auth()->user()->id)
                    ->required(),
                Forms\Components\Select::make('allocation_id')
                    ->relationship('allocation', 'address')
                    ->searchable()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->hidden()
                    ->default('')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('skip_scripts')
                    ->required(),
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
                    ->default(500)
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
                Forms\Components\Textarea::make('startup')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('image')
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->hidden()
                    ->label('UUID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->icon('tabler-brand-docker')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('node.name')
                    ->icon('tabler-server-2')
                    ->url(fn (Server $server): string => route('filament.admin.resources.nodes.edit', ['record' => $server->node]))
                    ->sortable(),
                Tables\Columns\TextColumn::make('egg.name')
                    ->icon('tabler-egg')
                    ->url(fn (Server $server): string => route('filament.admin.resources.eggs.edit', ['record' => $server->egg]))
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->icon('tabler-user')
                    ->url(fn (Server $server): string => route('filament.admin.resources.users.edit', ['record' => $server->user]))
                    ->sortable(),
                Tables\Columns\SelectColumn::make('allocation.id')
                    ->label('Primary Allocation')
                    ->options(fn ($state, Server $server) => [$server->allocation->id => $server->allocation->address])
                    ->selectablePlaceholder(false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('image')->hidden(),
                Tables\Columns\TextColumn::make('backups_count')
                    ->counts('backups')
                    ->label('Backups')
                    ->icon('tabler-file-download')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListServers::route('/'),
            'create' => Pages\CreateServer::route('/create'),
            'edit' => Pages\EditServer::route('/{record}/edit'),
        ];
    }
}
