<?php

namespace App\Filament\Resources\DatabaseHostResource\RelationManagers;

use App\Models\Database;
use App\Models\DatabaseHost;
use App\Services\Databases\DatabasePasswordService;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DatabasesRelationManager extends RelationManager
{
    protected static string $relationship = 'databases';

    protected $listeners = ['refresh'=>'refreshForm'];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('database')->columnSpanFull(),
                Forms\Components\TextInput::make('username'),
                Forms\Components\TextInput::make('password')
                    ->hintAction(
                        Action::make('rotate')
                            ->icon('tabler-refresh')
                            ->requiresConfirmation()
                            ->action(fn (DatabasePasswordService $service, Database $db) => $service->handle($db))
                    )
                    ->formatStateUsing(fn (Database $database) => decrypt($database->password)),
                Forms\Components\TextInput::make('remote')->label('Connections From'),
                Forms\Components\TextInput::make('max_connections'),
                Forms\Components\TextInput::make('JDBC')
                    ->label('JDBC Connection String')
                    ->columnSpanFull()
                    ->formatStateUsing(fn (Forms\Get $get, Database $database) => 'jdbc:mysql://' . $get('username') . ':' . urlencode(decrypt($database->password)) . '@' . $database->host->host . ':' . $database->host->port . '/' . $get('database')),
                Forms\Components\TextInput::make('created_at'),
                Forms\Components\TextInput::make('updated_at'),
            ]);
    }
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('servers')
            ->columns([
                Tables\Columns\TextColumn::make('database'),
                Tables\Columns\TextColumn::make('username'),
                //Tables\Columns\TextColumn::make('password'),
                Tables\Columns\TextColumn::make('remote'),
                Tables\Columns\TextColumn::make('server.name'),
                // TODO ->url(route('filament.admin.resources.servers.edit', ['record', ''])),
                Tables\Columns\TextColumn::make('max_connections'),
                Tables\Columns\TextColumn::make('created_at'),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
                //Tables\Actions\EditAction::make(),
            ]);
    }
}
