<?php

namespace App\Filament\Resources\DatabaseHostResource\Pages;

use App\Filament\Resources\DatabaseHostResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;

class CreateDatabaseHost extends CreateRecord
{
    protected static string $resource = DatabaseHostResource::class;

    protected ?string $heading = 'Database Hosts';

    protected static bool $canCreateAnother = false;

    protected ?string $subheading = '(database servers that can have individual databases)';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('host')
                        ->helperText('The IP address or Domain name that should be used when attempting to connect to this MySQL host from this Panel to create new databases.')
                        ->required()
                        ->live()
                        ->debounce(500)
                        ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('name', $state))
                        ->maxLength(191),
                    Forms\Components\TextInput::make('port')
                        ->helperText('The port that MySQL is running on for this host.')
                        ->required()
                        ->numeric()
                        ->default(3306)
                        ->minValue(0)
                        ->maxValue(65535),
                    Forms\Components\TextInput::make('username')
                        ->helperText('The username of an account that has enough permissions to create new users and databases on the system.')
                        ->required()
                        ->maxLength(191),
                    Forms\Components\TextInput::make('password')
                        ->helperText('The password for the database user.')
                        ->password()
                        ->revealable()
                        ->maxLength(191)
                        ->required(),
                    Forms\Components\TextInput::make('name')
                        ->helperText('A short identifier used to distinguish this location from others. Must be between 1 and 60 characters, for example, us.nyc.lvl3.')
                        ->required()
                        ->maxLength(60),
                    Forms\Components\Select::make('node_id')
                        ->searchable()
                        ->preload()
                        ->helperText('This setting only defaults to this database host when adding a database to a server on the selected node.')
                        ->label('Linked Node')
                        ->relationship('node', 'name'),
                ])->columns([
                    'default' => 1,
                    'lg' => 2,
                ]),
            ]);
    }
}
