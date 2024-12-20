<?php

namespace App\Filament\Admin\Resources\DatabaseHostResource\Pages;

use App\Filament\Admin\Resources\DatabaseHostResource;
use App\Services\Databases\Hosts\HostCreationService;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use PDOException;

class CreateDatabaseHost extends CreateRecord
{
    protected static string $resource = DatabaseHostResource::class;

    protected static bool $canCreateAnother = false;

    private HostCreationService $service;

    public function boot(HostCreationService $service): void
    {
        $this->service = $service;
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
                            ->default(3306)
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
                            ->maxLength(255)
                            ->required(),
                        Select::make('node_ids')
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
            $this->getCreateFormAction()->formId('form'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return $this->service->handle($data);
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
