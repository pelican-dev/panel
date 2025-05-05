<?php

namespace App\Filament\Admin\Resources\DatabaseHostResource\Pages;

use App\Filament\Admin\Resources\DatabaseHostResource;
use App\Services\Databases\Hosts\HostCreationService;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use PDOException;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class CreateDatabaseHost extends CreateRecord
{
    use HasWizard;

    protected static string $resource = DatabaseHostResource::class;

    protected static bool $canCreateAnother = false;

    private HostCreationService $service;

    public function boot(HostCreationService $service): void
    {
        $this->service = $service;
    }

    /** @return Step[] */
    public function getSteps(): array
    {
        return [
            Step::make(trans('admin/databasehost.setup.preparations'))
                ->columns()
                ->schema([
                    Placeholder::make('')
                        ->content(trans('admin/databasehost.setup.note')),
                    Toggle::make('different_server')
                        ->label(new HtmlString(trans('admin/databasehost.setup.different_server')))
                        ->dehydrated(false)
                        ->live()
                        ->columnSpanFull()
                        ->afterStateUpdated(fn ($state, Set $set) => $state ? $set('panel_ip', gethostbyname(str(config('app.url'))->replace(['http:', 'https:', '/'], ''))) : '127.0.0.1'),
                    Hidden::make('panel_ip')
                        ->default('127.0.0.1')
                        ->dehydrated(false),
                    TextInput::make('username')
                        ->label(trans('admin/databasehost.username'))
                        ->helperText(trans('admin/databasehost.username_help'))
                        ->required()
                        ->default('pelicanuser')
                        ->maxLength(255),
                    TextInput::make('password')
                        ->label(trans('admin/databasehost.password'))
                        ->helperText(trans('admin/databasehost.password_help'))
                        ->required()
                        ->default(Str::password(16))
                        ->password()
                        ->revealable()
                        ->maxLength(255),
                ])
                ->afterValidation(function (Get $get, Set $set) {
                    $set('create_user', "CREATE USER '{$get('username')}'@'{$get('panel_ip')}' IDENTIFIED BY '{$get('password')}';");
                    $set('assign_permissions', "GRANT ALL PRIVILEGES ON *.* TO '{$get('username')}'@'{$get('panel_ip')}' WITH GRANT OPTION;");
                }),
            Step::make(trans('admin/databasehost.setup.database_setup'))
                ->schema([
                    Fieldset::make(trans('admin/databasehost.setup.database_user'))
                        ->schema([
                            Placeholder::make('')
                                ->content(new HtmlString(trans('admin/databasehost.setup.cli_login')))
                                ->columnSpanFull(),
                            TextInput::make('create_user')
                                ->label(trans('admin/databasehost.setup.command_create_user'))
                                ->default(fn (Get $get) => "CREATE USER '{$get('username')}'@'{$get('panel_ip')}' IDENTIFIED BY '{$get('password')}';")
                                ->disabled()
                                ->dehydrated(false)
                                ->suffixAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null)
                                ->columnSpanFull(),
                            TextInput::make('assign_permissions')
                                ->label(trans('admin/databasehost.setup.command_assign_permissions'))
                                ->default(fn (Get $get) => "GRANT ALL PRIVILEGES ON *.* TO '{$get('username')}'@'{$get('panel_ip')}' WITH GRANT OPTION;")
                                ->disabled()
                                ->dehydrated(false)
                                ->suffixAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null)
                                ->columnSpanFull(),
                            Placeholder::make('')
                                ->content(new HtmlString(trans('admin/databasehost.setup.cli_exit')))
                                ->columnSpanFull(),
                        ]),
                    Fieldset::make(trans('admin/databasehost.setup.external_access'))
                        ->schema([
                            Placeholder::make('')
                                ->content(new HtmlString(trans('admin/databasehost.setup.allow_external_access')))
                                ->columnSpanFull(),
                        ]),
                ]),
            Step::make(trans('admin/databasehost.setup.panel_setup'))
                ->columns([
                    'default' => 2,
                    'lg' => 3,
                ])
                ->schema([
                    TextInput::make('host')
                        ->columnSpan(2)
                        ->label(trans('admin/databasehost.host'))
                        ->helperText(trans('admin/databasehost.host_help'))
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Set $set) => $set('name', $state))
                        ->maxLength(255),
                    TextInput::make('port')
                        ->label(trans('admin/databasehost.port'))
                        ->helperText(trans('admin/databasehost.port_help'))
                        ->required()
                        ->numeric()
                        ->default(3306)
                        ->minValue(0)
                        ->maxValue(65535),
                    TextInput::make('max_databases')
                        ->label(trans('admin/databasehost.max_database'))
                        ->helpertext(trans('admin/databasehost.max_databases_help'))
                        ->placeholder(trans('admin/databasehost.unlimited'))
                        ->numeric(),
                    TextInput::make('name')
                        ->label(trans('admin/databasehost.display_name'))
                        ->helperText(trans('admin/databasehost.display_name_help'))
                        ->required()
                        ->maxLength(60),
                    Select::make('node_ids')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->helperText(trans('admin/databasehost.linked_nodes_help'))
                        ->label(trans('admin/databasehost.linked_nodes'))
                        ->relationship('nodes', 'name', fn (Builder $query) => $query->whereIn('nodes.id', auth()->user()->accessibleNodes()->pluck('id'))),
                ]),
        ];
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
