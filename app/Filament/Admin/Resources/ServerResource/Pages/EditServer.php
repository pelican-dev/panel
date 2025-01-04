<?php

namespace App\Filament\Admin\Resources\ServerResource\Pages;

use App\Enums\ContainerStatus;
use App\Enums\ServerState;
use App\Filament\Admin\Resources\ServerResource;
use App\Filament\Admin\Resources\ServerResource\RelationManagers\AllocationsRelationManager;
use App\Filament\Components\Forms\Actions\RotateDatabasePasswordAction;
use App\Filament\Server\Pages\Console;
use App\Models\Database;
use App\Models\DatabaseHost;
use App\Models\Egg;
use App\Models\Mount;
use App\Models\Server;
use App\Models\ServerVariable;
use App\Services\Databases\DatabaseManagementService;
use App\Services\Eggs\EggChangerService;
use App\Services\Servers\RandomWordService;
use App\Services\Servers\ReinstallServerService;
use App\Services\Servers\ServerDeletionService;
use App\Services\Servers\SuspensionService;
use App\Services\Servers\ToggleInstallService;
use App\Services\Servers\TransferServerService;
use Closure;
use Exception;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use LogicException;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class EditServer extends EditRecord
{
    protected static string $resource = ServerResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->persistTabInQueryString()
                    ->columns([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 4,
                        'lg' => 6,
                    ])
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Information')
                            ->icon('tabler-info-circle')
                            ->schema([
                                TextInput::make('name')
                                    ->prefixIcon('tabler-server')
                                    ->label('Display Name')
                                    ->suffixAction(Action::make('random')
                                        ->icon('tabler-dice-' . random_int(1, 6))
                                        ->action(function (Set $set, Get $get) {
                                            $egg = Egg::find($get('egg_id'));
                                            $prefix = $egg ? str($egg->name)->lower()->kebab() . '-' : '';

                                            $word = (new RandomWordService())->word();

                                            $set('name', $prefix . $word);
                                        }))
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 2,
                                        'lg' => 3,
                                    ])
                                    ->required()
                                    ->maxLength(255),

                                Select::make('owner_id')
                                    ->prefixIcon('tabler-user')
                                    ->label('Owner')
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 2,
                                        'lg' => 2,
                                    ])
                                    ->relationship('user', 'username')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                ToggleButtons::make('condition')
                                    ->label('Server Status')
                                    ->formatStateUsing(fn (Server $server) => $server->condition)
                                    ->options(fn ($state) => collect(array_merge(ContainerStatus::cases(), ServerState::cases()))
                                        ->filter(fn ($condition) => $condition->value === $state)
                                        ->mapWithKeys(fn ($state) => [$state->value => str($state->value)->replace('_', ' ')->ucwords()])
                                    )
                                    ->colors(collect(array_merge(ContainerStatus::cases(), ServerState::cases()))->mapWithKeys(
                                        fn ($status) => [$status->value => $status->color()]
                                    ))
                                    ->icons(collect(array_merge(ContainerStatus::cases(), ServerState::cases()))->mapWithKeys(
                                        fn ($status) => [$status->value => $status->icon()]
                                    ))
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 1,
                                        'lg' => 1,
                                    ]),

                                Textarea::make('description')
                                    ->label('Description')
                                    ->columnSpanFull(),

                                TextInput::make('uuid')
                                    ->hintAction(CopyAction::make())
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 2,
                                        'lg' => 3,
                                    ])
                                    ->readOnly()
                                    ->dehydrated(false),
                                TextInput::make('uuid_short')
                                    ->label('Short UUID')
                                    ->hintAction(CopyAction::make())
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 2,
                                        'lg' => 3,
                                    ])
                                    ->readOnly()
                                    ->dehydrated(false),
                                TextInput::make('external_id')
                                    ->label('External ID')
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 2,
                                        'lg' => 3,
                                    ])
                                    ->maxLength(255),
                                Select::make('node_id')
                                    ->label('Node')
                                    ->relationship('node', 'name')
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 2,
                                        'lg' => 3,
                                    ])
                                    ->disabled(),
                            ]),
                        Tab::make('Environment')
                            ->icon('tabler-brand-docker')
                            ->schema([
                                Fieldset::make('Resource Limits')
                                    ->columns([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 3,
                                        'lg' => 3,
                                    ])
                                    ->schema([
                                        Grid::make()
                                            ->columns(4)
                                            ->columnSpanFull()
                                            ->schema([
                                                ToggleButtons::make('unlimited_cpu')
                                                    ->label('CPU')->inlineLabel()->inline()
                                                    ->afterStateUpdated(fn (Set $set) => $set('cpu', 0))
                                                    ->formatStateUsing(fn (Get $get) => $get('cpu') == 0)
                                                    ->live()
                                                    ->options([
                                                        true => 'Unlimited',
                                                        false => 'Limited',
                                                    ])
                                                    ->colors([
                                                        true => 'primary',
                                                        false => 'warning',
                                                    ])
                                                    ->columnSpan(2),

                                                TextInput::make('cpu')
                                                    ->dehydratedWhenHidden()
                                                    ->hidden(fn (Get $get) => $get('unlimited_cpu'))
                                                    ->label('CPU Limit')->inlineLabel()
                                                    ->suffix('%')
                                                    ->required()
                                                    ->columnSpan(2)
                                                    ->numeric()
                                                    ->minValue(0),
                                            ]),
                                        Grid::make()
                                            ->columns(4)
                                            ->columnSpanFull()
                                            ->schema([
                                                ToggleButtons::make('unlimited_mem')
                                                    ->label('Memory')->inlineLabel()->inline()
                                                    ->afterStateUpdated(fn (Set $set) => $set('memory', 0))
                                                    ->formatStateUsing(fn (Get $get) => $get('memory') == 0)
                                                    ->live()
                                                    ->options([
                                                        true => 'Unlimited',
                                                        false => 'Limited',
                                                    ])
                                                    ->colors([
                                                        true => 'primary',
                                                        false => 'warning',
                                                    ])
                                                    ->columnSpan(2),

                                                TextInput::make('memory')
                                                    ->dehydratedWhenHidden()
                                                    ->hidden(fn (Get $get) => $get('unlimited_mem'))
                                                    ->label('Memory Limit')->inlineLabel()
                                                    ->suffix(config('panel.use_binary_prefix') ? 'MiB' : 'MB')
                                                    ->required()
                                                    ->columnSpan(2)
                                                    ->numeric()
                                                    ->minValue(0),
                                            ]),

                                        Grid::make()
                                            ->columns(4)
                                            ->columnSpanFull()
                                            ->schema([
                                                ToggleButtons::make('unlimited_disk')
                                                    ->label('Disk Space')->inlineLabel()->inline()
                                                    ->live()
                                                    ->afterStateUpdated(fn (Set $set) => $set('disk', 0))
                                                    ->formatStateUsing(fn (Get $get) => $get('disk') == 0)
                                                    ->options([
                                                        true => 'Unlimited',
                                                        false => 'Limited',
                                                    ])
                                                    ->colors([
                                                        true => 'primary',
                                                        false => 'warning',
                                                    ])
                                                    ->columnSpan(2),

                                                TextInput::make('disk')
                                                    ->dehydratedWhenHidden()
                                                    ->hidden(fn (Get $get) => $get('unlimited_disk'))
                                                    ->label('Disk Space Limit')->inlineLabel()
                                                    ->suffix(config('panel.use_binary_prefix') ? 'MiB' : 'MB')
                                                    ->required()
                                                    ->columnSpan(2)
                                                    ->numeric()
                                                    ->minValue(0),
                                            ]),
                                    ]),

                                Fieldset::make('Advanced Limits')
                                    ->columns([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 3,
                                        'lg' => 3,
                                    ])
                                    ->schema([
                                        Grid::make()
                                            ->columns(4)
                                            ->columnSpanFull()
                                            ->schema([
                                                Grid::make()
                                                    ->columns(4)
                                                    ->columnSpanFull()
                                                    ->schema([
                                                        ToggleButtons::make('cpu_pinning')
                                                            ->label('CPU Pinning')->inlineLabel()->inline()
                                                            ->default(false)
                                                            ->afterStateUpdated(fn (Set $set) => $set('threads', []))
                                                            ->formatStateUsing(fn (Get $get) => !empty($get('threads')))
                                                            ->live()
                                                            ->options([
                                                                false => 'Disabled',
                                                                true => 'Enabled',
                                                            ])
                                                            ->colors([
                                                                false => 'success',
                                                                true => 'warning',
                                                            ])
                                                            ->columnSpan(2),

                                                        TagsInput::make('threads')
                                                            ->dehydratedWhenHidden()
                                                            ->hidden(fn (Get $get) => !$get('cpu_pinning'))
                                                            ->label('Pinned Threads')->inlineLabel()
                                                            ->required(fn (Get $get) => $get('cpu_pinning'))
                                                            ->columnSpan(2)
                                                            ->separator()
                                                            ->splitKeys([','])
                                                            ->placeholder('Add pinned thread, e.g. 0 or 2-4'),
                                                    ]),
                                                ToggleButtons::make('swap_support')
                                                    ->live()
                                                    ->label('Swap Memory')->inlineLabel()->inline()
                                                    ->columnSpan(2)
                                                    ->afterStateUpdated(function ($state, Set $set) {
                                                        $value = match ($state) {
                                                            'unlimited' => -1,
                                                            'disabled' => 0,
                                                            'limited' => 128,
                                                            default => throw new LogicException('Invalid state')
                                                        };

                                                        $set('swap', $value);
                                                    })
                                                    ->formatStateUsing(function (Get $get) {
                                                        return match (true) {
                                                            $get('swap') > 0 => 'limited',
                                                            $get('swap') == 0 => 'disabled',
                                                            $get('swap') < 0 => 'unlimited',
                                                            default => throw new LogicException('Invalid state')
                                                        };
                                                    })
                                                    ->options([
                                                        'unlimited' => 'Unlimited',
                                                        'limited' => 'Limited',
                                                        'disabled' => 'Disabled',
                                                    ])
                                                    ->colors([
                                                        'unlimited' => 'primary',
                                                        'limited' => 'warning',
                                                        'disabled' => 'danger',
                                                    ]),

                                                TextInput::make('swap')
                                                    ->dehydratedWhenHidden()
                                                    ->hidden(fn (Get $get) => match ($get('swap_support')) {
                                                        'disabled', 'unlimited', true => true,
                                                        default => false,
                                                    })
                                                    ->label('Swap Memory')->inlineLabel()
                                                    ->suffix(config('panel.use_binary_prefix') ? 'MiB' : 'MB')
                                                    ->minValue(-1)
                                                    ->columnSpan(2)
                                                    ->required()
                                                    ->integer(),
                                            ]),

                                        Hidden::make('io')
                                            ->helperText('The IO performance relative to other running containers')
                                            ->label('Block IO Proportion'),

                                        Grid::make()
                                            ->columns(4)
                                            ->columnSpanFull()
                                            ->schema([
                                                ToggleButtons::make('oom_killer')
                                                    ->label('OOM Killer')->inlineLabel()->inline()
                                                    ->columnSpan(2)
                                                    ->options([
                                                        false => 'Disabled',
                                                        true => 'Enabled',
                                                    ])
                                                    ->colors([
                                                        false => 'success',
                                                        true => 'danger',
                                                    ]),

                                                TextInput::make('oom_disabled_hidden')
                                                    ->hidden(),
                                            ]),
                                    ]),

                                Fieldset::make('Feature Limits')
                                    ->inlineLabel()
                                    ->columns([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 3,
                                        'lg' => 3,
                                    ])
                                    ->schema([
                                        TextInput::make('allocation_limit')
                                            ->label('Allocations')
                                            ->suffixIcon('tabler-network')
                                            ->required()
                                            ->minValue(0)
                                            ->numeric(),
                                        TextInput::make('database_limit')
                                            ->label('Databases')
                                            ->suffixIcon('tabler-database')
                                            ->required()
                                            ->minValue(0)
                                            ->numeric(),
                                        TextInput::make('backup_limit')
                                            ->label('Backups')
                                            ->suffixIcon('tabler-copy-check')
                                            ->required()
                                            ->minValue(0)
                                            ->numeric(),
                                    ]),
                                Fieldset::make('Docker Settings')
                                    ->columns([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 3,
                                        'lg' => 4,
                                    ])
                                    ->schema([
                                        Select::make('select_image')
                                            ->label('Image Name')
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set, $state) => $set('image', $state))
                                            ->options(function ($state, Get $get, Set $set) {
                                                $egg = Egg::query()->find($get('egg_id'));
                                                $images = $egg->docker_images ?? [];

                                                $currentImage = $get('image');
                                                if (!$currentImage && $images) {
                                                    $defaultImage = collect($images)->first();
                                                    $set('image', $defaultImage);
                                                    $set('select_image', $defaultImage);
                                                }

                                                return array_flip($images) + ['ghcr.io/custom-image' => 'Custom Image'];
                                            })
                                            ->selectablePlaceholder(false)
                                            ->columnSpan([
                                                'default' => 1,
                                                'sm' => 2,
                                                'md' => 3,
                                                'lg' => 2,
                                            ]),

                                        TextInput::make('image')
                                            ->label('Image')
                                            ->required()
                                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                                $egg = Egg::query()->find($get('egg_id'));
                                                $images = $egg->docker_images ?? [];

                                                if (in_array($state, $images)) {
                                                    $set('select_image', $state);
                                                } else {
                                                    $set('select_image', 'ghcr.io/custom-image');
                                                }
                                            })
                                            ->placeholder('Enter a custom Image')
                                            ->columnSpan([
                                                'default' => 1,
                                                'sm' => 2,
                                                'md' => 3,
                                                'lg' => 2,
                                            ]),

                                        KeyValue::make('docker_labels')
                                            ->label('Container Labels')
                                            ->keyLabel('Label Name')
                                            ->valueLabel('Label Description')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('Egg')
                            ->icon('tabler-egg')
                            ->columns([
                                'default' => 1,
                                'sm' => 3,
                                'md' => 3,
                                'lg' => 5,
                            ])
                            ->schema([
                                Select::make('egg_id')
                                    ->disabled()
                                    ->prefixIcon('tabler-egg')
                                    ->columnSpan([
                                        'default' => 6,
                                        'sm' => 3,
                                        'md' => 3,
                                        'lg' => 4,
                                    ])
                                    ->relationship('egg', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->hintAction(
                                        Action::make('change_egg')
                                            ->action(function (array $data, Server $server, EggChangerService $service) {
                                                $service->handle($server, $data['egg_id'], $data['keepOldVariables']);

                                                // Use redirect instead of fillForm to prevent server variables from duplicating
                                                $this->redirect($this->getUrl(['record' => $server, 'tab' => '-egg-tab']), true);
                                            })
                                            ->form(fn (Server $server) => [
                                                Select::make('egg_id')
                                                    ->label('New Egg')
                                                    ->prefixIcon('tabler-egg')
                                                    ->options(fn () => Egg::all()->filter(fn (Egg $egg) => $egg->id !== $server->egg->id)->mapWithKeys(fn (Egg $egg) => [$egg->id => $egg->name]))
                                                    ->searchable()
                                                    ->preload()
                                                    ->required(),
                                                Toggle::make('keepOldVariables')
                                                    ->label('Keep old variables if possible?')
                                                    ->default(true),
                                            ])
                                    ),

                                ToggleButtons::make('skip_scripts')
                                    ->label('Run Egg Install Script?')->inline()
                                    ->columnSpan([
                                        'default' => 6,
                                        'sm' => 1,
                                        'md' => 1,
                                        'lg' => 2,
                                    ])
                                    ->options([
                                        false => 'Yes',
                                        true => 'Skip',
                                    ])
                                    ->colors([
                                        false => 'primary',
                                        true => 'danger',
                                    ])
                                    ->icons([
                                        false => 'tabler-code',
                                        true => 'tabler-code-off',
                                    ])
                                    ->required(),

                                Textarea::make('startup')
                                    ->label('Startup Command')
                                    ->required()
                                    ->columnSpan(6)
                                    ->autosize(),

                                Textarea::make('defaultStartup')
                                    ->hintAction(CopyAction::make())
                                    ->label('Default Startup Command')
                                    ->disabled()
                                    ->autosize()
                                    ->columnSpan(6)
                                    ->formatStateUsing(function ($state, Get $get) {
                                        $egg = Egg::query()->find($get('egg_id'));

                                        return $egg->startup;
                                    }),

                                Repeater::make('server_variables')
                                    ->relationship('serverVariables', function (Builder $query) {
                                        /** @var Server $server */
                                        $server = $this->getRecord();

                                        foreach ($server->variables as $variable) {
                                            ServerVariable::query()->firstOrCreate([
                                                'server_id' => $server->id,
                                                'variable_id' => $variable->id,
                                            ], [
                                                'variable_value' => $variable->server_value ?? '',
                                            ]);
                                        }

                                        return $query;
                                    })
                                    ->grid()
                                    ->mutateRelationshipDataBeforeSaveUsing(function (array &$data): array {
                                        foreach ($data as $key => $value) {
                                            if (!isset($data['variable_value'])) {
                                                $data['variable_value'] = '';
                                            }
                                        }

                                        return $data;
                                    })
                                    ->reorderable(false)->addable(false)->deletable(false)
                                    ->schema(function () {

                                        $text = TextInput::make('variable_value')
                                            ->hidden($this->shouldHideComponent(...))
                                            ->required(fn (ServerVariable $serverVariable) => $serverVariable->variable->getRequiredAttribute())
                                            ->rules([
                                                fn (ServerVariable $serverVariable): Closure => function (string $attribute, $value, Closure $fail) use ($serverVariable) {
                                                    $validator = Validator::make(['validatorkey' => $value], [
                                                        'validatorkey' => $serverVariable->variable->rules,
                                                    ]);

                                                    if ($validator->fails()) {
                                                        $message = str($validator->errors()->first())->replace('validatorkey', $serverVariable->variable->name);

                                                        $fail($message);
                                                    }
                                                },
                                            ]);

                                        $select = Select::make('variable_value')
                                            ->hidden($this->shouldHideComponent(...))
                                            ->options($this->getSelectOptionsFromRules(...))
                                            ->selectablePlaceholder(false);

                                        $components = [$text, $select];

                                        foreach ($components as &$component) {
                                            $component = $component
                                                ->live(onBlur: true)
                                                ->hintIcon('tabler-code')
                                                ->label(fn (ServerVariable $serverVariable) => $serverVariable->variable->name)
                                                ->hintIconTooltip(fn (ServerVariable $serverVariable) => implode('|', $serverVariable->variable->rules))
                                                ->prefix(fn (ServerVariable $serverVariable) => '{{' . $serverVariable->variable->env_variable . '}}')
                                                ->helperText(fn (ServerVariable $serverVariable) => empty($serverVariable->variable->description) ? '—' : $serverVariable->variable->description);
                                        }

                                        return $components;
                                    })
                                    ->columnSpan(6),
                            ]),
                        Tab::make('Mounts')
                            ->icon('tabler-layers-linked')
                            ->schema([
                                CheckboxList::make('mounts')
                                    ->relationship('mounts')
                                    ->options(fn (Server $server) => $server->node->mounts->filter(fn (Mount $mount) => $mount->eggs->contains($server->egg))->mapWithKeys(fn (Mount $mount) => [$mount->id => $mount->name]))
                                    ->descriptions(fn (Server $server) => $server->node->mounts->mapWithKeys(fn (Mount $mount) => [$mount->id => "$mount->source -> $mount->target"]))
                                    ->label('Mounts')
                                    ->helperText(fn (Server $server) => $server->node->mounts->isNotEmpty() ? '' : 'No Mounts exist for this Node')
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Databases')
                            ->hidden(fn () => !auth()->user()->can('viewList database'))
                            ->icon('tabler-database')
                            ->columns(4)
                            ->schema([
                                Repeater::make('databases')
                                    ->grid()
                                    ->helperText(fn (Server $server) => $server->databases->isNotEmpty() ? '' : 'No Databases exist for this Server')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('database')
                                            ->columnSpan(2)
                                            ->label('Database Name')
                                            ->disabled()
                                            ->formatStateUsing(fn ($record) => $record->database)
                                            ->hintAction(
                                                Action::make('Delete')
                                                    ->authorize(fn (Database $database) => auth()->user()->can('delete database', $database))
                                                    ->color('danger')
                                                    ->icon('tabler-trash')
                                                    ->requiresConfirmation()
                                                    ->modalIcon('tabler-database-x')
                                                    ->modalHeading('Delete Database?')
                                                    ->modalSubmitActionLabel(fn (Get $get) => 'Delete ' . $get('database') . '?')
                                                    ->modalDescription(fn (Get $get) => 'Are you sure you want to delete ' . $get('database') . '?')
                                                    ->action(function (DatabaseManagementService $databaseManagementService, $record) {
                                                        $databaseManagementService->delete($record);
                                                        $this->fillForm();
                                                    })
                                            ),
                                        TextInput::make('username')
                                            ->disabled()
                                            ->formatStateUsing(fn ($record) => $record->username)
                                            ->columnSpan(1),
                                        TextInput::make('password')
                                            ->disabled()
                                            ->password()
                                            ->revealable()
                                            ->columnSpan(1)
                                            ->hintAction(RotateDatabasePasswordAction::make())
                                            ->formatStateUsing(fn (Database $database) => $database->password),
                                        TextInput::make('remote')
                                            ->disabled()
                                            ->formatStateUsing(fn (Database $record) => $record->remote === '%' ? 'Anywhere ( % )' : $record->remote)
                                            ->columnSpan(1)
                                            ->label('Connections From'),
                                        TextInput::make('max_connections')
                                            ->disabled()
                                            ->formatStateUsing(fn (Database $record) => $record->max_connections === 0 ? 'Unlimited' : $record->max_connections)
                                            ->columnSpan(1),
                                        TextInput::make('jdbc')
                                            ->disabled()
                                            ->password()
                                            ->revealable()
                                            ->label('JDBC Connection String')
                                            ->columnSpan(2)
                                            ->formatStateUsing(fn (Database $record) => $record->jdbc),
                                    ])
                                    ->relationship('databases')
                                    ->deletable(false)
                                    ->addable(false)
                                    ->columnSpan(4),
                                Forms\Components\Actions::make([
                                    Action::make('createDatabase')
                                        ->authorize(fn () => auth()->user()->can('create database'))
                                        ->disabled(fn () => DatabaseHost::query()->count() < 1)
                                        ->label(fn () => DatabaseHost::query()->count() < 1 ? 'No Database Hosts' : 'Create Database')
                                        ->color(fn () => DatabaseHost::query()->count() < 1 ? 'danger' : 'primary')
                                        ->modalSubmitActionLabel('Create Database')
                                        ->action(function (array $data, DatabaseManagementService $service, Server $server, RandomWordService $randomWordService) {
                                            if (empty($data['database'])) {
                                                $data['database'] = $randomWordService->word() . random_int(1, 420);
                                            }
                                            if (empty($data['remote'])) {
                                                $data['remote'] = '%';
                                            }

                                            $data['database'] = $service->generateUniqueDatabaseName($data['database'], $server->id);

                                            try {
                                                $service->setValidateDatabaseLimit(false)->create($server, $data);
                                            } catch (Exception $e) {
                                                Notification::make()
                                                    ->title('Failed to Create Database')
                                                    ->body($e->getMessage())
                                                    ->danger()
                                                    ->persistent()->send();
                                            }
                                            $this->fillForm();
                                        })
                                        ->form([
                                            Select::make('database_host_id')
                                                ->label('Database Host')
                                                ->required()
                                                ->placeholder('Select Database Host')
                                                ->options(fn (Server $server) => DatabaseHost::query()
                                                    ->whereHas('nodes', fn ($query) => $query->where('nodes.id', $server->node_id))
                                                    ->pluck('name', 'id')
                                                )
                                                ->default(fn () => (DatabaseHost::query()->first())?->id)
                                                ->selectablePlaceholder(false),
                                            TextInput::make('database')
                                                ->label('Database Name')
                                                ->alphaDash()
                                                ->prefix(fn (Server $server) => 's' . $server->id . '_')
                                                ->hintIcon('tabler-question-mark')
                                                ->hintIconTooltip('Leaving this blank will auto generate a random name'),
                                            TextInput::make('remote')
                                                ->columnSpan(1)
                                                ->regex('/^[\w\-\/.%:]+$/')
                                                ->label('Connections From')
                                                ->hintIcon('tabler-question-mark')
                                                ->hintIconTooltip('Where connections should be allowed from. Leave blank to allow connections from anywhere.'),
                                        ]),
                                ])->alignCenter()->columnSpanFull(),
                            ]),
                        Tab::make('Actions')
                            ->icon('tabler-settings')
                            ->schema([
                                Fieldset::make('Server Actions')
                                    ->columns([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 2,
                                        'lg' => 6,
                                    ])
                                    ->schema([
                                        Grid::make()
                                            ->columnSpan(3)
                                            ->schema([
                                                Forms\Components\Actions::make([
                                                    Action::make('toggleInstall')
                                                        ->label('Toggle Install Status')
                                                        ->disabled(fn (Server $server) => $server->isSuspended())
                                                        ->action(function (ToggleInstallService $service, Server $server) {
                                                            $service->handle($server);

                                                            $this->refreshFormData(['status', 'docker']);
                                                        }),
                                                ])->fullWidth(),
                                                ToggleButtons::make('')
                                                    ->hint('If you need to change the install status from uninstalled to installed, or vice versa, you may do so with this button.'),
                                            ]),
                                        Grid::make()
                                            ->columnSpan(3)
                                            ->schema([
                                                Forms\Components\Actions::make([
                                                    Action::make('toggleSuspend')
                                                        ->label('Suspend')
                                                        ->color('warning')
                                                        ->hidden(fn (Server $server) => $server->isSuspended())
                                                        ->action(function (SuspensionService $suspensionService, Server $server) {
                                                            $suspensionService->toggle($server, 'suspend');
                                                            Notification::make()->success()->title('Server Suspended!')->send();

                                                            $this->refreshFormData(['status', 'docker']);
                                                        }),
                                                    Action::make('toggleUnsuspend')
                                                        ->label('Unsuspend')
                                                        ->color('success')
                                                        ->hidden(fn (Server $server) => !$server->isSuspended())
                                                        ->action(function (SuspensionService $suspensionService, Server $server) {
                                                            $suspensionService->toggle($server, 'unsuspend');
                                                            Notification::make()->success()->title('Server Unsuspended!')->send();

                                                            $this->refreshFormData(['status', 'docker']);
                                                        }),
                                                ])->fullWidth(),
                                                ToggleButtons::make('')
                                                    ->hidden(fn (Server $server) => $server->isSuspended())
                                                    ->hint('This will suspend the server, stop any running processes, and immediately block the user from being able to access their files or otherwise manage the server through the panel or API.'),
                                                ToggleButtons::make('')
                                                    ->hidden(fn (Server $server) => !$server->isSuspended())
                                                    ->hint('This will unsuspend the server and restore normal user access.'),
                                            ]),
                                        Grid::make()
                                            ->columnSpan(3)
                                            ->schema([
                                                Forms\Components\Actions::make([
                                                    Action::make('transfer')
                                                        ->label('Transfer Soon™')
                                                        ->action(fn (TransferServerService $transfer, Server $server) => $transfer->handle($server, []))
                                                        ->disabled() //TODO!
                                                        ->form([ //TODO!
                                                            Select::make('newNode')
                                                                ->label('New Node')
                                                                ->required()
                                                                ->options([
                                                                    true => 'on',
                                                                    false => 'off',
                                                                ]),
                                                            Select::make('newMainAllocation')
                                                                ->label('New Main Allocation')
                                                                ->required()
                                                                ->options([
                                                                    true => 'on',
                                                                    false => 'off',
                                                                ]),
                                                            Select::make('newAdditionalAllocation')
                                                                ->label('New Additional Allocations')
                                                                ->options([
                                                                    true => 'on',
                                                                    false => 'off',
                                                                ]),
                                                        ])
                                                        ->modalHeading('Transfer'),
                                                ])->fullWidth(),
                                                ToggleButtons::make('')
                                                    ->hint('Transfer this server to another node connected to this panel. Warning! This feature has not been fully tested and may have bugs.'),
                                            ]),
                                        Grid::make()
                                            ->columnSpan(3)
                                            ->schema([
                                                Forms\Components\Actions::make([
                                                    Action::make('reinstall')
                                                        ->label('Reinstall')
                                                        ->color('danger')
                                                        ->requiresConfirmation()
                                                        ->modalHeading('Are you sure you want to reinstall this server?')
                                                        ->modalDescription('!! This can result in unrecoverable data loss !!')
                                                        ->disabled(fn (Server $server) => $server->isSuspended())
                                                        ->action(fn (ReinstallServerService $service, Server $server) => $service->handle($server)),
                                                ])->fullWidth(),
                                                ToggleButtons::make('')
                                                    ->hint('This will reinstall the server with the assigned egg install script.'),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    protected function transferServer(Form $form): Form
    {
        return $form
            ->columns()
            ->schema([
                Select::make('toNode')
                    ->label('New Node'),
                TextInput::make('newAllocation')
                    ->label('Allocation'),
            ]);

    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Delete')
                ->successRedirectUrl(route('filament.admin.resources.servers.index'))
                ->color('danger')
                ->label('Delete')
                ->requiresConfirmation()
                ->action(function (Server $server, ServerDeletionService $service) {
                    $service->handle($server);

                    return redirect(ListServers::getUrl(panel: 'admin'));
                })
                ->authorize(fn (Server $server) => auth()->user()->can('delete server', $server)),
            Actions\Action::make('console')
                ->label('Console')
                ->icon('tabler-terminal')
                ->url(fn (Server $server) => Console::getUrl(panel: 'server', tenant: $server)),
            $this->getSaveFormAction()->formId('form'),
        ];

    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!isset($data['description'])) {
            $data['description'] = '';
        }

        unset($data['docker'], $data['status']);

        return $data;
    }

    public function getRelationManagers(): array
    {
        return [
            AllocationsRelationManager::class,
        ];
    }

    private function shouldHideComponent(ServerVariable $serverVariable, Forms\Components\Component $component): bool
    {
        $containsRuleIn = array_first($serverVariable->variable->rules, fn ($value) => str($value)->startsWith('in:'), false);

        if ($component instanceof Select) {
            return !$containsRuleIn;
        }

        if ($component instanceof TextInput) {
            return $containsRuleIn;
        }

        throw new Exception('Component type not supported: ' . $component::class);
    }

    private function getSelectOptionsFromRules(ServerVariable $serverVariable): array
    {
        $inRule = array_first($serverVariable->variable->rules, fn ($value) => str($value)->startsWith('in:'));

        return str($inRule)
            ->after('in:')
            ->explode(',')
            ->each(fn ($value) => str($value)->trim())
            ->mapWithKeys(fn ($value) => [$value => $value])
            ->all();
    }
}
