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
                            ->label(trans('admin/databasehost.host'))
                            ->helperText(trans('admin/databasehost.host_help'))
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('name', $state))
                            ->maxLength(255),
                        TextInput::make('port')
                            ->columnSpan(1)
                            ->label(trans('admin/databasehost.port'))
                            ->helperText(trans('admin/databasehost.port_help'))
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(65535),
                        TextInput::make('max_databases')
                            ->label(trans('admin/databasehost.max_database'))
                            ->helpertext(trans('admin/databasehost.max_databases_help'))
                            ->numeric(),
                        TextInput::make('name')
                            ->label(trans('admin/databasehost.display_name'))
                            ->helperText(trans('admin/databasehost.display_name_help'))
                            ->required()
                            ->maxLength(60),
                        TextInput::make('username')
                            ->label(trans('admin/databasehost.username'))
                            ->helperText(trans('admin/databasehost.username_help'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label(trans('admin/databasehost.password'))
                            ->helperText(trans('admin/databasehost.password_help'))
                            ->password()
                            ->revealable()
                            ->required()
                            ->maxLength(255),
                        Select::make('nodes')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->helperText(trans('admin/databasehost.linked_nodes_help'))
                            ->label(trans('admin/databasehost.linked_nodes'))
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
                ->title(trans('admin/databasehost.error'))
                ->body($exception->getMessage())
                ->color('danger')
                ->icon('tabler-database')
                ->danger()
                ->send();

            throw new Halt();
        }
    }
}
