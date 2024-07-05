<?php

namespace App\Filament\Resources\DatabaseHostResource\Pages;

use App\Filament\Resources\DatabaseHostResource;
use App\Filament\Resources\DatabaseHostResource\RelationManagers\DatabasesRelationManager;
use App\Models\DatabaseHost;
use App\Services\Databases\Hosts\HostUpdateService;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use PDOException;

class EditDatabaseHost extends EditRecord
{
    protected static string $resource = DatabaseHostResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'default' => 2,
                        'sm' => 3,
                        'md' => 3,
                        'lg' => 4,
                    ])
                    ->schema([
                        TextInput::make('host')
                            ->columnSpan(2)
                            ->helperText('The IP address or Domain name that should be used when attempting to connect to this MySQL host from this Panel to create new databases.')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('name', $state))
                            ->maxLength(255),
                        TextInput::make('port')
                            ->columnSpan(1)
                            ->helperText('The port that MySQL is running on for this host.')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(65535),
                        TextInput::make('max_databases')
                            ->label('Max databases')
                            ->helpertext('Blank is unlimited.')
                            ->numeric(),
                        TextInput::make('name')
                            ->label('Display Name')
                            ->helperText('A short identifier used to distinguish this location from others. Must be between 1 and 60 characters, for example, us.nyc.lvl3.')
                            ->required()
                            ->maxLength(60),
                        TextInput::make('username')
                            ->helperText('The username of an account that has enough permissions to create new users and databases on the system.')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->helperText('The password for the database user.')
                            ->password()
                            ->revealable()
                            ->maxLength(255),
                        Select::make('node_id')
                            ->searchable()
                            ->preload()
                            ->helperText('This setting only defaults to this database host when adding a database to a server on the selected node.')
                            ->label('Linked Node')
                            ->relationship('node', 'name'),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label(fn (DatabaseHost $databaseHost) => $databaseHost->databases()->count() > 0 ? 'Database Host Has Databases' : 'Delete')
                ->disabled(fn (DatabaseHost $databaseHost) => $databaseHost->databases()->count() > 0),
            $this->getSaveFormAction()->formId('form'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    public function getRelationManagers(): array
    {
        return [
            DatabasesRelationManager::class,
        ];
    }

    protected function handleRecordUpdate($record, array $data): Model
    {
        return resolve(HostUpdateService::class)->handle($record->id, $data);
    }

    public function exception($e, $stopPropagation): void
    {
        if ($e instanceof PDOException) {
            Notification::make()
                ->title('Error connecting to database host')
                ->body($e->getMessage())
                ->color('danger')
                ->icon('tabler-database')
                ->danger()
                ->send();

            $stopPropagation();
        }
    }
}
