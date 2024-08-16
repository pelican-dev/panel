<?php

namespace App\Filament\App\Resources\DatabaseResource\Pages;

use App\Filament\App\Resources\DatabaseResource;
use App\Models\Database;
use App\Models\DatabaseHost;
use App\Models\Server;
use App\Services\Databases\DatabaseManagementService;
use App\Services\Databases\DatabasePasswordService;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class ListDatabases extends ListRecords
{
    protected static string $resource = DatabaseResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('database')->columnSpanFull()->suffixAction(CopyAction::make()),
                TextInput::make('username')->suffixAction(CopyAction::make()),
                TextInput::make('password')
                    ->hintAction(
                        Action::make('rotate')
                            ->icon('tabler-refresh')
                            ->requiresConfirmation()
                            ->action(fn (DatabasePasswordService $service, Database $database, $set, $get) => $this->rotatePassword($service, $database, $set, $get))
                    )
                    ->suffixAction(CopyAction::make())
                    ->formatStateUsing(fn (Database $database) => $database->password),
                TextInput::make('remote')->label('Connections From'),
                TextInput::make('max_connections')
                    ->formatStateUsing(fn (Database $database) => $database->max_connections === 0 ? $database->max_connections : 'Unlimited'),
                TextInput::make('JDBC')
                    ->label('JDBC Connection String')
                    ->suffixAction(CopyAction::make())
                    ->columnSpanFull()
                    ->formatStateUsing(fn (Get $get, Database $database) => 'jdbc:mysql://' . $get('username') . ':' . urlencode($database->password) . '@' . $database->host->host . ':' . $database->host->port . '/' . $get('database')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('database'),
                TextColumn::make('username'),
                TextColumn::make('remote'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                ViewAction::make(),
                DeleteAction::make(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return [
            CreateAction::make('new')
                ->label(fn () => $server->databases()->count() >= $server->database_limit ? 'Database Limit Reached' : 'Create Database')
                ->disabled(fn () => $server->databases()->count() >= $server->database_limit)
                ->color(fn () => $server->databases()->count() >= $server->database_limit ? 'danger' : 'primary')
                ->createAnother(false)
                ->form([
                    Grid::make()
                        ->columns(3)
                        ->schema([
                            TextInput::make('database')
                                ->columnSpan(2)
                                ->label('Database Name')
                                ->prefix('s'. $server->id . '_')
                                ->hintIcon('tabler-question-mark')
                                ->hintIconTooltip('Leaving this blank will auto generate a random name'),
                            TextInput::make('remote')
                                ->columnSpan(1)
                                ->label('Connections From')
                                ->default('%'),
                        ]),
                ])
                ->action(function ($data) use ($server) {
                    if (empty($data['database'])) {
                        $data['database'] = str_random(12);
                    }

                    $data['database_host_id'] = DatabaseHost::where('node_id', $server->node_id)->first()->id;
                    $data['database'] = 's'. $server->id . '_' . $data['database'];

                    resolve(DatabaseManagementService::class)->create($server, $data);
                }),
        ];
    }

    protected function rotatePassword(DatabasePasswordService $service, Database $database, $set, $get): void
    {
        $newPassword = $service->handle($database);
        $jdbcString = 'jdbc:mysql://' . $get('username') . ':' . urlencode($newPassword) . '@' . $database->host->host . ':' . $database->host->port . '/' . $get('database');

        $set('password', $newPassword);
        $set('JDBC', $jdbcString);
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
