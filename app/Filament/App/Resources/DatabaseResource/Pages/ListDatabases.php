<?php

namespace App\Filament\App\Resources\DatabaseResource\Pages;

use App\Filament\App\Resources\DatabaseResource;
use App\Services\Databases\DatabaseManagementService;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListDatabases extends ListRecords
{
    protected static string $resource = DatabaseResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('database')
                    ->searchable(),
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('remote')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ViewAction::make(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        $server = Filament::getTenant();

        return [
            CreateAction::make('new')
                ->label(fn () => $server->database_limit <= $server->databases_count ? 'Database Limit Reached' : 'Create Database')
                ->disabled(fn () => $server->database_limit <= $server->databases_count)
                ->createAnother(false)
                ->form([
                    TextInput::make('database')
                        ->label('Database Name')
                        ->prefix('s'. $server->id . '_')
                        ->hintIcon('tabler-question-mark')
                        ->hintIconTooltip('FUCK YOURSELF'),
                    TextInput::make('username')
                        ->label('Database Username'),
                ])
                ->action(function ($data) use ($server) {
                    if (empty($data['database'])) {
                        $data['database'] = str_random(12);
                    }

                    $data['database_host_id'] =

                    $data['database'] = 's'. $server->id . '_' . $data['database'];

                    resolve(DatabaseManagementService::class)->create($server, dd($data));
                }),
        ];
    }
}
