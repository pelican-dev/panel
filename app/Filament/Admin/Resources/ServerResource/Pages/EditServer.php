<?php

namespace App\Filament\Admin\Resources\ServerResource\Pages;

use App\Enums\SuspendAction;
use App\Filament\Admin\Resources\ServerResource;
use App\Filament\Admin\Resources\ServerResource\RelationManagers\AllocationsRelationManager;
use App\Filament\Components\Forms\Actions\PreviewStartupAction;
use App\Filament\Components\Forms\Actions\RotateDatabasePasswordAction;
use App\Filament\Server\Pages\Console;
use App\Models\Database;
use App\Models\DatabaseHost;
use App\Models\Egg;
use App\Models\Mount;
use App\Models\Server;
use App\Models\ServerVariable;
use App\Models\User;
use App\Repositories\Daemon\DaemonServerRepository;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Validator;
use LogicException;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class EditServer extends EditRecord
{
    protected static string $resource = ServerResource::class;

    private bool $errored = false;

    private DaemonServerRepository $daemonServerRepository;

    public function boot(DaemonServerRepository $daemonServerRepository): void
    {
        $this->daemonServerRepository = $daemonServerRepository;
    }

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
                        Tab::make(trans('admin/server.tabs.information'))
                            ->icon('tabler-info-circle')
                            ->schema([
                                TextInput::make('name')
                                    ->prefixIcon('tabler-server')
                                    ->label(trans('admin/server.name'))
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
                                    ->label(trans('admin/server.owner'))
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 2,
                                        'lg' => 2,
                                    ])
                                    ->relationship('user', 'username')
                                    ->searchable(['username', 'email'])
                                    ->getOptionLabelFromRecordUsing(fn (User $user) => "$user->username ($user->email)")
                                    ->preload()
                                    ->required(),

                                ToggleButtons::make('condition')
                                    ->label(trans('admin/server.server_status'))
                                    ->formatStateUsing(fn (Server $server) => $server->condition)
                                    ->options(fn ($state) => [$state->value => $state->getLabel()])
                                    ->colors(fn ($state) => [$state->value => $state->getColor()])
                                    ->icons(fn ($state) => [$state->value => $state->getIcon()])
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 1,
                                        'lg' => 1,
                                    ]),

                                Textarea::make('description')
                                    ->label(trans('admin/server.description'))
                                    ->columnSpanFull(),

                                TextInput::make('uuid')
                                    ->label(trans('admin/server.uuid'))
                                    ->suffixAction(fn () => request()->isSecure() ? CopyAction::make() : null)
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 2,
                                        'lg' => 3,
                                    ])
                                    ->readOnly()
                                    ->dehydrated(false),
                                TextInput::make('uuid_short')
                                    ->label(trans('admin/server.short_uuid'))
                                    ->suffixAction(fn () => request()->isSecure() ? CopyAction::make() : null)
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 2,
                                        'lg' => 3,
                                    ])
                                    ->readOnly()
                                    ->dehydrated(false),
                                TextInput::make('external_id')
                                    ->label(trans('admin/server.external_id'))
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 2,
                                        'lg' => 3,
                                    ])
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Select::make('node_id')
                                    ->label(trans('admin/server.node'))
                                    ->relationship('node', 'name')
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 2,
                                        'lg' => 3,
                                    ])
                                    ->disabled(),
                            ]),
                        Tab::make(trans('admin/server.tabs.environment_configuration'))
                            ->icon('tabler-brand-docker')
                            ->schema([
                                Fieldset::make(trans('admin/server.resource_limits'))
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
                                                    ->dehydrated()
                                                    ->label(trans('admin/server.cpu'))->inlineLabel()->inline()
                                                    ->afterStateUpdated(fn (Set $set) => $set('cpu', 0))
                                                    ->formatStateUsing(fn (Get $get) => $get('cpu') == 0)
                                                    ->live()
                                                    ->options([
                                                        true => trans('admin/server.unlimited'),
                                                        false => trans('admin/server.limited'),
                                                    ])
                                                    ->colors([
                                                        true => 'primary',
                                                        false => 'warning',
                                                    ])
                                                    ->columnSpan(2),

                                                TextInput::make('cpu')
                                                    ->dehydratedWhenHidden()
                                                    ->hidden(fn (Get $get) => $get('unlimited_cpu'))
                                                    ->label(trans('admin/server.cpu_limit'))->inlineLabel()
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
                                                    ->dehydrated()
                                                    ->label(trans('admin/server.memory'))->inlineLabel()->inline()
                                                    ->afterStateUpdated(fn (Set $set) => $set('memory', 0))
                                                    ->formatStateUsing(fn (Get $get) => $get('memory') == 0)
                                                    ->live()
                                                    ->options([
                                                        true => trans('admin/server.unlimited'),
                                                        false => trans('admin/server.limited'),
                                                    ])
                                                    ->colors([
                                                        true => 'primary',
                                                        false => 'warning',
                                                    ])
                                                    ->columnSpan(2),

                                                TextInput::make('memory')
                                                    ->dehydratedWhenHidden()
                                                    ->hidden(fn (Get $get) => $get('unlimited_mem'))
                                                    ->label(trans('admin/server.memory_limit'))->inlineLabel()
                                                    ->suffix(config('panel.use_binary_prefix') ? 'MiB' : 'MB')
                                                    ->hintIcon('tabler-question-mark')
                                                    ->hintIconToolTip(trans('admin/server.memory_helper'))
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
                                                    ->dehydrated()
                                                    ->label(trans('admin/server.disk'))->inlineLabel()->inline()
                                                    ->live()
                                                    ->afterStateUpdated(fn (Set $set) => $set('disk', 0))
                                                    ->formatStateUsing(fn (Get $get) => $get('disk') == 0)
                                                    ->options([
                                                        true => trans('admin/server.unlimited'),
                                                        false => trans('admin/server.limited'),
                                                    ])
                                                    ->colors([
                                                        true => 'primary',
                                                        false => 'warning',
                                                    ])
                                                    ->columnSpan(2),

                                                TextInput::make('disk')
                                                    ->dehydratedWhenHidden()
                                                    ->hidden(fn (Get $get) => $get('unlimited_disk'))
                                                    ->label(trans('admin/server.disk_limit'))->inlineLabel()
                                                    ->suffix(config('panel.use_binary_prefix') ? 'MiB' : 'MB')
                                                    ->required()
                                                    ->columnSpan(2)
                                                    ->numeric()
                                                    ->minValue(0),
                                            ]),
                                    ]),

                                Fieldset::make(trans('admin/server.advanced_limits'))
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
                                                            ->label(trans('admin/server.cpu_pin'))->inlineLabel()->inline()
                                                            ->default(false)
                                                            ->afterStateUpdated(fn (Set $set) => $set('threads', []))
                                                            ->formatStateUsing(fn (Get $get) => !empty($get('threads')))
                                                            ->live()
                                                            ->options([
                                                                false => trans('admin/server.disabled'),
                                                                true => trans('admin/server.enabled'),
                                                            ])
                                                            ->colors([
                                                                false => 'success',
                                                                true => 'warning',
                                                            ])
                                                            ->columnSpan(2),

                                                        TagsInput::make('threads')
                                                            ->dehydratedWhenHidden()
                                                            ->hidden(fn (Get $get) => !$get('cpu_pinning'))
                                                            ->label(trans('admin/server.threads'))->inlineLabel()
                                                            ->required(fn (Get $get) => $get('cpu_pinning'))
                                                            ->columnSpan(2)
                                                            ->separator()
                                                            ->splitKeys([','])
                                                            ->placeholder(trans('admin/server.pin_help')),
                                                    ]),
                                                ToggleButtons::make('swap_support')
                                                    ->live()
                                                    ->label(trans('admin/server.swap'))->inlineLabel()->inline()
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
                                                        'unlimited' => trans('admin/server.unlimited'),
                                                        'limited' => trans('admin/server.limited'),
                                                        'disabled' => trans('admin/server.disabled'),
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
                                                    ->label(trans('admin/server.swap'))->inlineLabel()
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
                                                    ->label(trans('admin/server.oom'))->inlineLabel()->inline()
                                                    ->columnSpan(2)
                                                    ->options([
                                                        false => trans('admin/server.disabled'),
                                                        true => trans('admin/server.enabled'),
                                                    ])
                                                    ->colors([
                                                        false => 'success',
                                                        true => 'danger',
                                                    ]),
                                            ]),
                                    ]),

                                Fieldset::make(trans('admin/server.feature_limits'))
                                    ->inlineLabel()
                                    ->columns([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 3,
                                        'lg' => 3,
                                    ])
                                    ->schema([
                                        TextInput::make('allocation_limit')
                                            ->label(trans('admin/server.allocations'))
                                            ->suffixIcon('tabler-network')
                                            ->required()
                                            ->minValue(0)
                                            ->numeric(),
                                        TextInput::make('database_limit')
                                            ->label(trans('admin/server.databases'))
                                            ->suffixIcon('tabler-database')
                                            ->required()
                                            ->minValue(0)
                                            ->numeric(),
                                        TextInput::make('backup_limit')
                                            ->label(trans('admin/server.backups'))
                                            ->suffixIcon('tabler-copy-check')
                                            ->required()
                                            ->minValue(0)
                                            ->numeric(),
                                    ]),
                                Fieldset::make(trans('admin/server.docker_settings'))
                                    ->columns([
                                        'default' => 1,
                                        'sm' => 2,
                                        'md' => 3,
                                        'lg' => 4,
                                    ])
                                    ->schema([
                                        Select::make('select_image')
                                            ->label(trans('admin/server.image_name'))
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
                                            ->label(trans('admin/server.image'))
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
                                            ->placeholder(trans('admin/server.image_placeholder'))
                                            ->columnSpan([
                                                'default' => 1,
                                                'sm' => 2,
                                                'md' => 3,
                                                'lg' => 2,
                                            ]),

                                        KeyValue::make('docker_labels')
                                            ->label(trans('admin/server.container_labels'))
                                            ->keyLabel(trans('admin/server.title'))
                                            ->valueLabel(trans('admin/server.description'))
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make(trans('admin/server.egg'))
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
                                    ->label(trans('admin/server.name'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->hintAction(
                                        Action::make('change_egg')
                                            ->label(trans('admin/server.change_egg'))
                                            ->action(function (array $data, Server $server, EggChangerService $service) {
                                                $service->handle($server, $data['egg_id'], $data['keepOldVariables']);

                                                // Use redirect instead of fillForm to prevent server variables from duplicating
                                                $this->redirect($this->getUrl(['record' => $server, 'tab' => '-egg-tab']), true);
                                            })
                                            ->form(fn (Server $server) => [
                                                Select::make('egg_id')
                                                    ->label(trans('admin/server.new_egg'))
                                                    ->prefixIcon('tabler-egg')
                                                    ->options(fn () => Egg::all()->filter(fn (Egg $egg) => $egg->id !== $server->egg->id)->mapWithKeys(fn (Egg $egg) => [$egg->id => $egg->name]))
                                                    ->searchable()
                                                    ->preload()
                                                    ->required(),
                                                Toggle::make('keepOldVariables')
                                                    ->label(trans('admin/server.keep_old_variables'))
                                                    ->default(true),
                                            ])
                                    ),

                                ToggleButtons::make('skip_scripts')
                                    ->label(trans('admin/server.install_script'))->inline()
                                    ->columnSpan([
                                        'default' => 6,
                                        'sm' => 1,
                                        'md' => 1,
                                        'lg' => 2,
                                    ])
                                    ->options([
                                        false => trans('admin/server.yes'),
                                        true => trans('admin/server.skip'),
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
                                Hidden::make('previewing')
                                    ->default(false),
                                Textarea::make('startup')
                                    ->label(trans('admin/server.startup_cmd'))
                                    ->required()
                                    ->columnSpan(6)
                                    ->autosize()
                                    ->hintAction(PreviewStartupAction::make('preview')),

                                Textarea::make('defaultStartup')
                                    ->hintAction(fn () => request()->isSecure() ? CopyAction::make() : null)
                                    ->label(trans('admin/server.default_startup'))
                                    ->disabled()
                                    ->autosize()
                                    ->columnSpan(6)
                                    ->formatStateUsing(function ($state, Get $get) {
                                        $egg = Egg::query()->find($get('egg_id'));

                                        return $egg->startup;
                                    }),

                                Repeater::make('server_variables')
                                    ->label('')
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
                        Tab::make(trans('admin/server.mounts'))
                            ->icon('tabler-layers-linked')
                            ->schema([
                                CheckboxList::make('mounts')
                                    ->label('')
                                    ->relationship('mounts')
                                    ->options(fn (Server $server) => $server->node->mounts->filter(fn (Mount $mount) => $mount->eggs->contains($server->egg))->mapWithKeys(fn (Mount $mount) => [$mount->id => $mount->name]))
                                    ->descriptions(fn (Server $server) => $server->node->mounts->mapWithKeys(fn (Mount $mount) => [$mount->id => "$mount->source -> $mount->target"]))
                                    ->helperText(fn (Server $server) => $server->node->mounts->isNotEmpty() ? '' : trans('admin/server.no_mounts'))
                                    ->columnSpanFull(),
                            ]),
                        Tab::make(trans('admin/server.databases'))
                            ->hidden(fn () => !auth()->user()->can('viewList database'))
                            ->icon('tabler-database')
                            ->columns(4)
                            ->schema([
                                Repeater::make('databases')
                                    ->label('')
                                    ->grid()
                                    ->helperText(fn (Server $server) => $server->databases->isNotEmpty() ? '' : trans('admin/server.no_databases'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('host')
                                            ->label(trans('admin/databasehost.table.host'))
                                            ->disabled()
                                            ->formatStateUsing(fn ($record) => $record->address())
                                            ->suffixAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null)
                                            ->columnSpan(1),
                                        TextInput::make('database')
                                            ->label(trans('admin/databasehost.table.database'))
                                            ->disabled()
                                            ->formatStateUsing(fn ($record) => $record->database)
                                            ->suffixAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null)
                                            ->hintAction(
                                                Action::make('Delete')
                                                    ->label(trans('filament-actions::delete.single.modal.actions.delete.label'))
                                                    ->authorize(fn (Database $database) => auth()->user()->can('delete database', $database))
                                                    ->color('danger')
                                                    ->icon('tabler-trash')
                                                    ->requiresConfirmation()
                                                    ->modalIcon('tabler-database-x')
                                                    ->modalHeading(trans('admin/server.delete_db_heading'))
                                                    ->modalSubmitActionLabel(fn (Get $get) => 'Delete ' . $get('database') . '?')
                                                    ->modalDescription(fn (Get $get) => trans('admin/server.delete_db') . $get('database') . '?')
                                                    ->action(function (DatabaseManagementService $databaseManagementService, $record) {
                                                        $databaseManagementService->delete($record);
                                                        $this->fillForm();
                                                    })
                                            ),
                                        TextInput::make('username')
                                            ->label(trans('admin/databasehost.table.username'))
                                            ->disabled()
                                            ->formatStateUsing(fn ($record) => $record->username)
                                            ->suffixAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null)
                                            ->columnSpan(1),
                                        TextInput::make('password')
                                            ->label(trans('admin/databasehost.table.password'))
                                            ->disabled()
                                            ->password()
                                            ->revealable()
                                            ->columnSpan(1)
                                            ->hintAction(RotateDatabasePasswordAction::make())
                                            ->suffixAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null)
                                            ->formatStateUsing(fn (Database $database) => $database->password),
                                        TextInput::make('remote')
                                            ->disabled()
                                            ->formatStateUsing(fn (Database $record) => $record->remote === '%' ? 'Anywhere ( % )' : $record->remote)
                                            ->columnSpan(1)
                                            ->label(trans('admin/databasehost.table.remote')),
                                        TextInput::make('max_connections')
                                            ->label(trans('admin/databasehost.table.max_connections'))
                                            ->disabled()
                                            ->formatStateUsing(fn (Database $record) => $record->max_connections === 0 ? 'Unlimited' : $record->max_connections)
                                            ->columnSpan(1),
                                        TextInput::make('jdbc')
                                            ->disabled()
                                            ->password()
                                            ->revealable()
                                            ->label(trans('admin/databasehost.table.connection_string'))
                                            ->columnSpan(2)
                                            ->formatStateUsing(fn (Database $record) => $record->jdbc)
                                            ->suffixAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null),
                                    ])
                                    ->relationship('databases')
                                    ->deletable(false)
                                    ->addable(false)
                                    ->columnSpan(4),
                                Forms\Components\Actions::make([
                                    Action::make('createDatabase')
                                        ->authorize(fn () => auth()->user()->can('create database'))
                                        ->disabled(fn () => DatabaseHost::query()->count() < 1)
                                        ->label(fn () => DatabaseHost::query()->count() < 1 ? trans('admin/server.no_db_hosts') : trans('admin/server.create_database'))
                                        ->color(fn () => DatabaseHost::query()->count() < 1 ? 'danger' : 'primary')
                                        ->modalSubmitActionLabel(trans('admin/server.create_database'))
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
                                                    ->title(trans('admin/server.failed_to_create'))
                                                    ->body($e->getMessage())
                                                    ->danger()
                                                    ->persistent()->send();
                                            }
                                            $this->fillForm();
                                        })
                                        ->form([
                                            Select::make('database_host_id')
                                                ->label(trans('admin/databasehost.table.name'))
                                                ->required()
                                                ->placeholder('Select Database Host')
                                                ->options(fn (Server $server) => DatabaseHost::query()
                                                    ->whereHas('nodes', fn ($query) => $query->where('nodes.id', $server->node_id))
                                                    ->pluck('name', 'id')
                                                )
                                                ->default(fn () => (DatabaseHost::query()->first())?->id)
                                                ->selectablePlaceholder(false),
                                            TextInput::make('database')
                                                ->label(trans('admin/server.name'))
                                                ->alphaDash()
                                                ->prefix(fn (Server $server) => 's' . $server->id . '_')
                                                ->hintIcon('tabler-question-mark')
                                                ->hintIconTooltip(trans('admin/databasehost.table.name_helper')),
                                            TextInput::make('remote')
                                                ->columnSpan(1)
                                                ->regex('/^[\w\-\/.%:]+$/')
                                                ->label(trans('admin/databasehost.table.remote'))
                                                ->hintIcon('tabler-question-mark')
                                                ->hintIconTooltip(trans('admin/databasehost.table.remote_helper')),
                                        ]),
                                ])->alignCenter()->columnSpanFull(),
                            ]),
                        Tab::make(trans('admin/server.actions'))
                            ->icon('tabler-settings')
                            ->schema([
                                Fieldset::make(trans('admin/server.actions'))
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
                                                        ->label(trans('admin/server.toggle_install'))
                                                        ->disabled(fn (Server $server) => $server->isSuspended())
                                                        ->action(function (ToggleInstallService $service, Server $server) {
                                                            $service->handle($server);

                                                            $this->refreshFormData(['status', 'docker']);
                                                        }),
                                                ])->fullWidth(),
                                                ToggleButtons::make('')
                                                    ->hint(trans('admin/server.toggle_install_help')),
                                            ]),
                                        Grid::make()
                                            ->columnSpan(3)
                                            ->schema([
                                                Forms\Components\Actions::make([
                                                    Action::make('toggleSuspend')
                                                        ->label(trans('admin/server.suspend'))
                                                        ->color('warning')
                                                        ->hidden(fn (Server $server) => $server->isSuspended())
                                                        ->action(function (SuspensionService $suspensionService, Server $server) {
                                                            try {
                                                                $suspensionService->handle($server, SuspendAction::Suspend);
                                                            } catch (\Exception $exception) {
                                                                Notification::make()->warning()->title(trans('admin/server.notifications.server_suspension'))->body($exception->getMessage())->send();
                                                            }
                                                            Notification::make()->success()->title(trans('admin/server.notifications.server_suspended'))->send();

                                                            $this->refreshFormData(['status', 'docker']);
                                                        }),
                                                    Action::make('toggleUnsuspend')
                                                        ->label(trans('admin/server.unsuspend'))
                                                        ->color('success')
                                                        ->hidden(fn (Server $server) => !$server->isSuspended())
                                                        ->action(function (SuspensionService $suspensionService, Server $server) {
                                                            try {
                                                                $suspensionService->handle($server, SuspendAction::Unsuspend);
                                                            } catch (\Exception $exception) {
                                                                Notification::make()->warning()->title(trans('admin/server.notifications.server_suspension'))->body($exception->getMessage())->send();
                                                            }
                                                            Notification::make()->success()->title(trans('admin/server.notifications.server_unsuspended'))->send();

                                                            $this->refreshFormData(['status', 'docker']);
                                                        }),
                                                ])->fullWidth(),
                                                ToggleButtons::make('')
                                                    ->hidden(fn (Server $server) => $server->isSuspended())
                                                    ->hint(trans('admin/server.notifications.server_suspend_help')),
                                                ToggleButtons::make('')
                                                    ->hidden(fn (Server $server) => !$server->isSuspended())
                                                    ->hint(trans('admin/server.notifications.server_unsuspend_help')),
                                            ]),
                                        Grid::make()
                                            ->columnSpan(3)
                                            ->schema([
                                                Forms\Components\Actions::make([
                                                    Action::make('transfer')
                                                        ->label(trans('admin/server.transfer'))
                                                        // ->action(fn (TransferServerService $transfer, Server $server) => $transfer->handle($server, []))
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
                                                        ->modalheading(trans('admin/server.transfer')),
                                                ])->fullWidth(),
                                                ToggleButtons::make('')
                                                    ->hint(trans('admin/server.transfer_help')),
                                            ]),
                                        Grid::make()
                                            ->columnSpan(3)
                                            ->schema([
                                                Forms\Components\Actions::make([
                                                    Action::make('reinstall')
                                                        ->label(trans('admin/server.reinstall'))
                                                        ->color('danger')
                                                        ->requiresConfirmation()
                                                        ->modalHeading(trans('admin/server.reinstall_modal_heading'))
                                                        ->modalDescription(trans('admin/server.reinstall_modal_description'))
                                                        ->disabled(fn (Server $server) => $server->isSuspended())
                                                        ->action(fn (ReinstallServerService $service, Server $server) => $service->handle($server)),
                                                ])->fullWidth(),
                                                ToggleButtons::make('')
                                                    ->hint(trans('admin/server.reinstall_help')),
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
                ->label(trans('filament-actions::delete.single.modal.actions.delete.label'))
                ->requiresConfirmation()
                ->action(function (Server $server, ServerDeletionService $service) {
                    $service->handle($server);

                    return redirect(ListServers::getUrl(panel: 'admin'));
                })
                ->authorize(fn (Server $server) => auth()->user()->can('delete server', $server)),
            Actions\Action::make('console')
                ->label(trans('admin/server.console'))
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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (!$record instanceof Server) {
            return $record;
        }

        /** @var Server $record */
        $record = parent::handleRecordUpdate($record, $data);

        try {
            $this->daemonServerRepository->setServer($record)->sync();
        } catch (ConnectionException) {
            $this->errored = true;

            Notification::make()
                ->title(trans('admin/server.notifications.error_connecting', ['node' => $record->node->name]))
                ->body(trans('admin/server.notifications.error_connecting_description'))
                ->color('warning')
                ->icon('tabler-database')
                ->warning()
                ->send();
        }

        return $record;
    }

    protected function getSavedNotification(): ?Notification
    {
        if ($this->errored) {
            return null;
        }

        return parent::getSavedNotification();
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

    /**
     * @return array<string, string>
     */
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
