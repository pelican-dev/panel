<?php

namespace App\Filament\Admin\Resources\DatabaseHostResource\Pages;

use App\Filament\Admin\Resources\DatabaseHostResource;
use App\Filament\Admin\Resources\DatabaseHostResource\RelationManagers\DatabasesRelationManager;
use App\Models\DatabaseHost;
use App\Services\Databases\Hosts\HostUpdateService;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use PDOException;

class EditDatabaseHost extends EditRecord
{
    protected static string $resource = DatabaseHostResource::class;

    private HostUpdateService $hostUpdateService;

    public function boot(HostUpdateService $hostUpdateService): void
    {
        $this->hostUpdateService = $hostUpdateService;
    }

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
                        Select::make('nodes')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->helperText('This setting only defaults to this database host when adding a database to a server on the selected node.')
                            ->label('Linked Nodes')
                            ->relationship('nodes', 'name'),
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
        if (DatabasesRelationManager::canViewForRecord($this->getRecord(), static::class)) {
            return [
                DatabasesRelationManager::class,
            ];
        }

        return [];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (!$record instanceof DatabaseHost) {
            return $record;
        }

        try {
            return $this->hostUpdateService->handle($record, $data);
        } catch (PDOException $exception) {
            Notification::make()
                ->title('Error connecting to database host')
                ->body($exception->getMessage())
                ->color('danger')
                ->icon('tabler-database')
                ->danger()
                ->send();

            throw new Halt();
        }
    }
}
