<?php

namespace App\Filament\Admin\Resources\DatabaseHostResource\RelationManagers;

use App\Filament\Components\Forms\Actions\RotateDatabasePasswordAction;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Models\Database;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DatabasesRelationManager extends RelationManager
{
    protected static string $relationship = 'databases';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('database')
                    ->columnSpanFull(),
                TextInput::make('username')
                    ->label(trans('admin/databasehost.table.username')),
                TextInput::make('password')
                    ->label(trans('admin/databasehost.table.password'))
                    ->password()
                    ->revealable()
                    ->hintAction(RotateDatabasePasswordAction::make())
                    ->formatStateUsing(fn (Database $database) => $database->password),
                TextInput::make('remote')
                    ->label(trans('admin/databasehost.table.remote'))
                    ->formatStateUsing(fn (Database $record) => $record->remote === '%' ? trans('admin/databasehost.anywhere'). ' ( % )' : $record->remote),
                TextInput::make('max_connections')
                    ->label(trans('admin/databasehost.table.max_connections'))
                    ->formatStateUsing(fn (Database $record) => $record->max_connections === 0 ? trans('admin/databasehost.unlimited') : $record->max_connections),
                TextInput::make('jdbc')
                    ->label(trans('admin/databasehost.table.connection_string'))
                    ->columnSpanFull()
                    ->password()
                    ->revealable()
                    ->formatStateUsing(fn (Database $database) => $database->jdbc),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('servers')
            ->heading('')
            ->columns([
                TextColumn::make('database')
                    ->icon('tabler-database'),
                TextColumn::make('username')
                    ->label(trans('admin/databasehost.table.username'))
                    ->icon('tabler-user'),
                TextColumn::make('remote')
                    ->label(trans('admin/databasehost.table.remote'))
                    ->formatStateUsing(fn (Database $record) => $record->remote === '%' ? trans('admin/databasehost.anywhere'). ' ( % )' : $record->remote),
                TextColumn::make('server.name')
                    ->icon('tabler-brand-docker')
                    ->url(fn (Database $database) => route('filament.admin.resources.servers.edit', ['record' => $database->server_id])),
                TextColumn::make('max_connections')
                    ->label(trans('admin/databasehost.table.max_connections'))
                    ->formatStateUsing(fn ($record) => $record->max_connections === 0 ? trans('admin/databasehost.unlimited') : $record->max_connections),
                DateTimeColumn::make('created_at')
                    ->label(trans('admin/databasehost.table.created_at')),
            ])
            ->actions([
                DeleteAction::make()
                    ->authorize(fn (Database $database) => auth()->user()->can('delete', $database)),
                ViewAction::make()
                    ->color('primary')
                    ->hidden(fn () => !auth()->user()->can('viewAny', Database::class)),
            ]);
    }
}
