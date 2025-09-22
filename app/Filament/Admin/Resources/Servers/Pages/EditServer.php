<?php

namespace App\Filament\Admin\Resources\Servers\Pages;

use App\Enums\SuspendAction;
use App\Filament\Admin\Resources\Servers\RelationManagers\AllocationsRelationManager;
use App\Filament\Admin\Resources\Servers\RelationManagers\DatabasesRelationManager;
use App\Filament\Admin\Resources\Servers\ServerResource;
use App\Filament\Components\Actions\PreviewStartupAction;
use App\Filament\Components\Forms\Fields\StartupVariable;
use App\Filament\Components\StateCasts\ServerConditionStateCast;
use App\Filament\Server\Pages\Console;
use App\Models\Allocation;
use App\Models\Egg;
use App\Models\Node;
use App\Models\Server;
use App\Models\ServerVariable;
use App\Models\User;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Services\Eggs\EggChangerService;
use App\Services\Servers\RandomWordService;
use App\Services\Servers\ReinstallServerService;
use App\Services\Servers\ServerDeletionService;
use App\Services\Servers\SuspensionService;
use App\Services\Servers\ToggleInstallService;
use App\Services\Servers\TransferServerService;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\StateCasts\BooleanStateCast;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use LogicException;
use Filament\Schemas\Schema;
use Random\RandomException;

class EditServer extends EditRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = ServerResource::class;

    private DaemonServerRepository $daemonServerRepository;

    public function boot(DaemonServerRepository $daemonServerRepository): void
    {
        $this->daemonServerRepository = $daemonServerRepository;
    }

    /**
     * @throws RandomException
     * @throws Exception
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                        Tab::make('information')
                            ->label(trans('admin/server.tabs.information'))
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
                                    ->stateCast(new ServerConditionStateCast())
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 1,
                                        'lg' => 1,
                                    ])
                                    ->hintAction(
                                        Action::make('view_install_log')
                                            ->label(trans('admin/server.view_install_log'))
                                            //->visible(fn (Server $server) => $server->isFailedInstall())
                                            ->modalHeading('')
                                            ->modalSubmitAction(false)
                                            ->modalFooterActionsAlignment(Alignment::Right)
                                            ->modalCancelActionLabel(trans('filament::components/modal.actions.close.label'))
                                            ->schema([
                                                CodeEditor::make('logs')
                                                    ->hiddenLabel()
                                                    ->formatStateUsing(function (Server $server, DaemonServerRepository $serverRepository) {
                                                        try {
                                                            return $serverRepository->setServer($server)->getInstallLogs();
                                                        } catch (ConnectionException) {
                                                            Notification::make()
                                                                ->title(trans('admin/server.notifications.error_connecting', ['node' => $server->node->name]))
                                                                ->body(trans('admin/server.notifications.log_failed'))
                                                                ->color('warning')
                                                                ->warning()
                                                                ->send();
                                                        } catch (Exception) {
                                                            return '';
                                                        }

                                                        return '';
                                                    }),
                                            ])
                                    ),

                                Textarea::make('description')
                                    ->label(trans('admin/server.description'))
                                    ->columnSpanFull(),

                                TextInput::make('uuid')
                                    ->label(trans('admin/server.uuid'))
                                    ->copyable(fn () => request()->isSecure())
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
                                    ->copyable(fn () => request()->isSecure())
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
                                    ->unique()
                                    ->maxLength(255),
                                Select::make('node_id')
                                    ->label(trans('admin/server.node'))
                                    ->relationship('node', 'name', fn (Builder $query) => $query->whereIn('id', auth()->user()->accessibleNodes()->pluck('id')))
                                    ->columnSpan([
                                        'default' => 2,
                                        'sm' => 1,
                                        'md' => 2,
                                        'lg' => 3,
                                    ])
                                    ->disabled(),
                            ]),
                        Tab::make('environment_configuration')
                            ->label(trans('admin/server.tabs.environment_configuration'))
                            ->icon('tabler-brand-docker')
                            ->schema([
                                Fieldset::make(trans('admin/server.resource_limits'))
                                    ->columnSpanFull()
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
                                                    ->stateCast(new BooleanStateCast(false, true))
                                                    ->options([
                                                        1 => trans('admin/server.unlimited'),
                                                        0 => trans('admin/server.limited'),
                                                    ])
                                                    ->colors([
                                                        1 => 'primary',
                                                        0 => 'warning',
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
                                                    ->stateCast(new BooleanStateCast(false, true))
                                                    ->options([
                                                        1 => trans('admin/server.unlimited'),
                                                        0 => trans('admin/server.limited'),
                                                    ])
                                                    ->colors([
                                                        1 => 'primary',
                                                        0 => 'warning',
                                                    ])
                                                    ->columnSpan(2),

                                                TextInput::make('memory')
                                                    ->dehydratedWhenHidden()
                                                    ->hidden(fn (Get $get) => $get('unlimited_mem'))
                                                    ->label(trans('admin/server.memory_limit'))->inlineLabel()
                                                    ->suffix(config('panel.use_binary_prefix') ? 'MiB' : 'MB')
                                                    ->hintIcon('tabler-question-mark', trans('admin/server.memory_helper'))
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
                                                    ->stateCast(new BooleanStateCast(false, true))
                                                    ->options([
                                                        1 => trans('admin/server.unlimited'),
                                                        0 => trans('admin/server.limited'),
                                                    ])
                                                    ->colors([
                                                        1 => 'primary',
                                                        0 => 'warning',
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
                                    ->columnSpanFull()
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
                                                            ->default(0)
                                                            ->afterStateUpdated(fn (Set $set) => $set('threads', []))
                                                            ->formatStateUsing(fn (Get $get) => !empty($get('threads')))
                                                            ->live()
                                                            ->stateCast(new BooleanStateCast(false, true))
                                                            ->options([
                                                                0 => trans('admin/server.disabled'),
                                                                1 => trans('admin/server.enabled'),
                                                            ])
                                                            ->colors([
                                                                0 => 'success',
                                                                1 => 'warning',
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
                                                    ->dehydrated()
                                                    ->label(trans('admin/server.oom'))
                                                    ->formatStateUsing(fn ($state) => $state)
                                                    ->inlineLabel()
                                                    ->inline()
                                                    ->columnSpan(2)
                                                    ->stateCast(new BooleanStateCast(false, true))
                                                    ->options([
                                                        0 => trans('admin/server.disabled'),
                                                        1 => trans('admin/server.enabled'),
                                                    ])
                                                    ->colors([
                                                        0 => 'success',
                                                        1 => 'danger',
                                                    ]),
                                            ]),
                                    ]),

                                Fieldset::make(trans('admin/server.feature_limits'))
                                    ->inlineLabel()
                                    ->columnSpanFull()
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
                                    ->columnSpanFull()
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
                                            ->live()
                                            ->label(trans('admin/server.container_labels'))
                                            ->keyLabel(trans('admin/server.title'))
                                            ->valueLabel(trans('admin/server.description'))
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Tab::make('egg')
                            ->label(trans('admin/server.egg'))
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
                                                $service->handle($server, $data['egg_id'], $data['keep_old_variables']);

                                                // Use redirect instead of fillForm to prevent server variables from duplicating
                                                $this->redirect($this->getUrl(['record' => $server, 'tab' => 'egg::data::tab']), true);
                                            })
                                            ->schema(fn (Server $server) => [
                                                Select::make('egg_id')
                                                    ->label(trans('admin/server.new_egg'))
                                                    ->prefixIcon('tabler-egg')
                                                    ->options(fn () => Egg::all()->filter(fn (Egg $egg) => $egg->id !== $server->egg->id)->mapWithKeys(fn (Egg $egg) => [$egg->id => $egg->name]))
                                                    ->searchable()
                                                    ->preload()
                                                    ->required(),
                                                Toggle::make('keep_old_variables')
                                                    ->label(trans('admin/server.keep_old_variables'))
                                                    ->default(true),
                                            ])
                                    ),

                                ToggleButtons::make('skip_scripts')
                                    ->label(trans('admin/server.install_script'))
                                    ->inline()
                                    ->columnSpan([
                                        'default' => 6,
                                        'sm' => 1,
                                        'md' => 1,
                                        'lg' => 2,
                                    ])
                                    ->stateCast(new BooleanStateCast(false, true))
                                    ->options([
                                        0 => trans('admin/server.yes'),
                                        1 => trans('admin/server.skip'),
                                    ])
                                    ->colors([
                                        0 => 'primary',
                                        1 => 'danger',
                                    ])
                                    ->icons([
                                        0 => 'tabler-code',
                                        1 => 'tabler-code-off',
                                    ])
                                    ->required(),

                                Hidden::make('previewing')
                                    ->default(false),

                                Select::make('select_startup')
                                    ->label(trans('admin/server.startup_cmd'))
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set, $state) => $set('startup', $state))
                                    ->options(function ($state, Get $get, Set $set) {
                                        $egg = Egg::query()->find($get('egg_id'));
                                        $startups = $egg->startup_commands ?? [];

                                        $currentStartup = $get('startup');
                                        if (!$currentStartup && $startups) {
                                            $currentStartup = collect($startups)->first();
                                            $set('startup', $currentStartup);
                                            $set('select_startup', $currentStartup);
                                        }

                                        return array_flip($startups) + ['' => 'Custom Startup'];
                                    })
                                    ->selectablePlaceholder(false)
                                    ->columnSpanFull()
                                    ->hintAction(PreviewStartupAction::make('preview')),

                                Textarea::make('startup')
                                    ->hiddenLabel()
                                    ->required()
                                    ->live()
                                    ->autosize()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        $egg = Egg::query()->find($get('egg_id'));
                                        $startups = $egg->startup_commands ?? [];

                                        if (in_array($state, $startups)) {
                                            $set('select_startup', $state);
                                        } else {
                                            $set('select_startup', '');
                                        }
                                    })
                                    ->placeholder(trans('admin/server.startup_placeholder'))
                                    ->columnSpanFull(),

                                Repeater::make('server_variables')
                                    ->hiddenLabel()
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

                                        return $query->orderByPowerJoins('variable.sort');
                                    })
                                    ->grid()
                                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                        $data['variable_value'] ??= '';

                                        return $data;
                                    })
                                    ->reorderable(false)->addable(false)->deletable(false)
                                    ->schema([
                                        StartupVariable::make('variable_value')
                                            ->fromRecord(),
                                    ])
                                    ->columnSpan(6),
                            ]),
                        Tab::make('mounts')
                            ->label(trans('admin/server.mounts'))
                            ->icon('tabler-layers-linked')
                            ->schema(fn (Get $get) => [
                                ServerResource::getMountCheckboxList($get),
                            ]),
                        Tab::make('actions')
                            ->label(trans('admin/server.actions'))
                            ->icon('tabler-settings')
                            ->schema([
                                Fieldset::make(trans('admin/server.actions'))
                                    ->columnSpanFull()
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
                                                Actions::make([
                                                    Action::make('toggleInstall')
                                                        ->label(trans('admin/server.toggle_install'))
                                                        ->disabled(fn (Server $server) => $server->isSuspended())
                                                        ->modal(fn (Server $server) => $server->isFailedInstall())
                                                        ->modalHeading(trans('admin/server.toggle_install_failed_header'))
                                                        ->modalDescription(trans('admin/server.toggle_install_failed_desc'))
                                                        ->modalSubmitActionLabel(trans('admin/server.reinstall'))
                                                        ->action(function (ToggleInstallService $toggleService, ReinstallServerService $reinstallService, Server $server) {
                                                            if ($server->isFailedInstall()) {
                                                                try {
                                                                    $reinstallService->handle($server);

                                                                    Notification::make()
                                                                        ->title(trans('admin/server.notifications.reinstall_started'))
                                                                        ->success()
                                                                        ->send();

                                                                } catch (Exception) {
                                                                    Notification::make()
                                                                        ->title(trans('admin/server.notifications.reinstall_failed'))
                                                                        ->body(trans('admin/server.notifications.error_connecting', ['node' => $server->node->name]))
                                                                        ->danger()
                                                                        ->send();
                                                                }
                                                            } else {
                                                                try {
                                                                    $toggleService->handle($server);

                                                                    Notification::make()
                                                                        ->title(trans('admin/server.notifications.install_toggled'))
                                                                        ->success()
                                                                        ->send();

                                                                } catch (Exception $exception) {
                                                                    Notification::make()
                                                                        ->title(trans('admin/server.notifications.install_toggle_failed'))
                                                                        ->body($exception->getMessage())
                                                                        ->danger()
                                                                        ->send();
                                                                }
                                                            }
                                                        }),
                                                ])->fullWidth(),
                                                ToggleButtons::make('install_help')
                                                    ->hiddenLabel()
                                                    ->hint(trans('admin/server.toggle_install_help')),
                                            ]),
                                        Grid::make()
                                            ->columnSpan(3)
                                            ->schema([
                                                Actions::make([
                                                    Action::make('toggleSuspend')
                                                        ->label(trans('admin/server.suspend'))
                                                        ->color('warning')
                                                        ->hidden(fn (Server $server) => $server->isSuspended())
                                                        ->action(function (SuspensionService $suspensionService, Server $server) {
                                                            try {
                                                                $suspensionService->handle($server, SuspendAction::Suspend);

                                                                Notification::make()
                                                                    ->success()
                                                                    ->title(trans('admin/server.notifications.server_suspended'))
                                                                    ->send();

                                                            } catch (Exception) {
                                                                Notification::make()
                                                                    ->warning()
                                                                    ->title(trans('admin/server.notifications.server_suspension'))
                                                                    ->body(trans('admin/server.notifications.error_connecting', ['node' => $server->node->name]))
                                                                    ->send();
                                                            }
                                                        }),
                                                    Action::make('toggleUnsuspend')
                                                        ->label(trans('admin/server.unsuspend'))
                                                        ->color('success')
                                                        ->hidden(fn (Server $server) => !$server->isSuspended())
                                                        ->action(function (SuspensionService $suspensionService, Server $server) {
                                                            try {
                                                                $suspensionService->handle($server, SuspendAction::Unsuspend);

                                                                Notification::make()
                                                                    ->success()
                                                                    ->title(trans('admin/server.notifications.server_unsuspended'))
                                                                    ->send();

                                                            } catch (Exception) {
                                                                Notification::make()
                                                                    ->warning()
                                                                    ->title(trans('admin/server.notifications.server_suspension'))
                                                                    ->body(trans('admin/server.notifications.error_connecting', ['node' => $server->node->name]))
                                                                    ->send();
                                                            }
                                                        }),
                                                ])->fullWidth(),
                                                ToggleButtons::make('server_suspend')
                                                    ->hiddenLabel()
                                                    ->hidden(fn (Server $server) => $server->isSuspended())
                                                    ->hint(trans('admin/server.notifications.server_suspend_help')),
                                                ToggleButtons::make('server_unsuspend')
                                                    ->hiddenLabel()
                                                    ->hidden(fn (Server $server) => !$server->isSuspended())
                                                    ->hint(trans('admin/server.notifications.server_unsuspend_help')),
                                            ]),
                                        Grid::make()
                                            ->columnSpan(3)
                                            ->schema([
                                                Actions::make([
                                                    Action::make('transfer')
                                                        ->label(trans('admin/server.transfer'))
                                                        ->disabled(fn (Server $server) => Node::count() <= 1 || $server->isInConflictState())
                                                        ->modalHeading(trans('admin/server.transfer'))
                                                        ->schema($this->transferServer())
                                                        ->action(function (TransferServerService $transfer, Server $server, $data) {
                                                            try {
                                                                $transfer->handle($server, Arr::get($data, 'node_id'), Arr::get($data, 'allocation_id'), Arr::get($data, 'allocation_additional', []));

                                                                Notification::make()
                                                                    ->title(trans('admin/server.notifications.transfer_started'))
                                                                    ->success()
                                                                    ->send();
                                                            } catch (Exception $exception) {
                                                                Notification::make()
                                                                    ->title(trans('admin/server.notifications.transfer_failed'))
                                                                    ->body($exception->getMessage())
                                                                    ->danger()
                                                                    ->send();
                                                            }
                                                        }),
                                                ])->fullWidth(),
                                                ToggleButtons::make('server_transfer')
                                                    ->hiddenLabel()
                                                    ->hint(new HtmlString(trans('admin/server.transfer_help'))),
                                            ]),
                                        Grid::make()
                                            ->columnSpan(3)
                                            ->schema([
                                                Actions::make([
                                                    Action::make('reinstall')
                                                        ->label(trans('admin/server.reinstall'))
                                                        ->color('danger')
                                                        ->requiresConfirmation()
                                                        ->modalHeading(trans('admin/server.reinstall_modal_heading'))
                                                        ->modalDescription(trans('admin/server.reinstall_modal_description'))
                                                        ->disabled(fn (Server $server) => $server->isSuspended())
                                                        ->action(function (ReinstallServerService $service, Server $server) {
                                                            try {
                                                                $service->handle($server);

                                                                Notification::make()
                                                                    ->title(trans('admin/server.notifications.reinstall_started'))
                                                                    ->success()
                                                                    ->send();
                                                            } catch (Exception) {
                                                                Notification::make()
                                                                    ->title(trans('admin/server.notifications.reinstall_failed'))
                                                                    ->body(trans('admin/server.notifications.error_connecting', ['node' => $server->node->name]))
                                                                    ->danger()
                                                                    ->send();
                                                            }
                                                        }),
                                                ])->fullWidth(),
                                                ToggleButtons::make('server_reinstall')
                                                    ->hiddenLabel()
                                                    ->hint(trans('admin/server.reinstall_help')),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    /** @return Component[]
     * @throws Exception
     */
    protected function transferServer(): array
    {
        return [
            Select::make('node_id')
                ->label(trans('admin/server.node'))
                ->prefixIcon('tabler-server-2')
                ->selectablePlaceholder(false)
                ->default(fn (Server $server) => Node::whereNot('id', $server->node->id)->first()?->id)
                ->required()
                ->live()
                ->options(fn (Server $server) => Node::whereNot('id', $server->node->id)->pluck('name', 'id')->all()),
            Select::make('allocation_id')
                ->label(trans('admin/server.primary_allocation'))
                ->disabled(fn (Get $get, Server $server) => !$get('node_id') || !$server->allocation_id)
                ->required(fn (Server $server) => $server->allocation_id)
                ->prefixIcon('tabler-network')
                ->options(fn (Get $get) => Allocation::where('node_id', $get('node_id'))->whereNull('server_id')->get()->mapWithKeys(fn (Allocation $allocation) => [$allocation->id => $allocation->address]))
                ->searchable(['ip', 'port', 'ip_alias'])
                ->placeholder(trans('admin/server.select_allocation')),
            Select::make('allocation_additional')
                ->label(trans('admin/server.additional_allocations'))
                ->disabled(fn (Get $get, Server $server) => !$get('node_id') || $server->allocations->count() <= 1)
                ->multiple()
                ->minItems(fn (Select $select) => $select->getMaxItems())
                ->maxItems(fn (Select $select, Server $server) => $select->isDisabled() ? null : $server->allocations->count() - 1)
                ->prefixIcon('tabler-network')
                ->required(fn (Server $server) => $server->allocations->count() > 1)
                ->options(fn (Get $get) => Allocation::where('node_id', $get('node_id'))->whereNull('server_id')->when($get('allocation_id'), fn ($query) => $query->whereNot('id', $get('allocation_id')))->get()->mapWithKeys(fn (Allocation $allocation) => [$allocation->id => $allocation->address]))
                ->searchable(['ip', 'port', 'ip_alias'])
                ->placeholder(trans('admin/server.select_additional')),
        ];
    }

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        /** @var Server $server */
        $server = $this->getRecord();

        $canForceDelete = cache()->get("servers.$server->uuid.canForceDelete", false);

        return [
            Action::make('Delete')
                ->color('danger')
                ->label(trans('filament-actions::delete.single.label'))
                ->modalHeading(trans('filament-actions::delete.single.modal.heading', ['label' => $this->getRecordTitle()]))
                ->modalSubmitActionLabel(trans('filament-actions::delete.single.label'))
                ->requiresConfirmation()
                ->action(function (Server $server, ServerDeletionService $service) {
                    try {
                        $service->handle($server);

                        return redirect(ListServers::getUrl(panel: 'admin'));
                    } catch (ConnectionException) {
                        cache()->put("servers.$server->uuid.canForceDelete", true, now()->addMinutes(5));

                        return Notification::make()
                            ->title(trans('admin/server.notifications.error_server_delete'))
                            ->body(trans('admin/server.notifications.error_server_delete_body'))
                            ->color('warning')
                            ->icon('tabler-database')
                            ->warning()
                            ->send();
                    }
                })
                ->hidden(fn () => $canForceDelete)
                ->authorize(fn (Server $server) => auth()->user()->can('delete server', $server)),
            Action::make('ForceDelete')
                ->color('danger')
                ->label(trans('filament-actions::force-delete.single.label'))
                ->modalHeading(trans('filament-actions::force-delete.single.modal.heading', ['label' => $this->getRecordTitle()]))
                ->modalSubmitActionLabel(trans('filament-actions::force-delete.single.label'))
                ->requiresConfirmation()
                ->action(function (Server $server, ServerDeletionService $service) {
                    try {
                        $service->withForce()->handle($server);

                        return redirect(ListServers::getUrl(panel: 'admin'));
                    } catch (ConnectionException) {
                        return cache()->forget("servers.$server->uuid.canForceDelete");
                    }
                })
                ->visible(fn () => $canForceDelete)
                ->authorize(fn (Server $server) => auth()->user()->can('delete server', $server)),
            Action::make('console')
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

        unset($data['docker'], $data['status'], $data['allocation_id']);

        return $data;
    }

    protected function afterSave(): void
    {
        /** @var Server $server */
        $server = $this->record;

        $changed = collect($server->getChanges())->except(['updated_at', 'name', 'owner_id', 'condition', 'description', 'external_id', 'tags', 'cpu_pinning', 'allocation_limit', 'database_limit', 'backup_limit', 'skip_scripts'])->all();

        try {
            if ($changed) {
                $this->daemonServerRepository->setServer($server)->sync();
            }
            parent::getSavedNotification()?->send();
        } catch (ConnectionException) {
            Notification::make()
                ->title(trans('admin/server.notifications.error_connecting', ['node' => $server->node->name]))
                ->body(trans('admin/server.notifications.error_connecting_description'))
                ->color('warning')
                ->icon('tabler-database')
                ->warning()
                ->send();
        }
    }

    protected function getSavedNotification(): ?Notification
    {
        return null;
    }

    public function getRelationManagers(): array
    {
        return [
            AllocationsRelationManager::class,
            DatabasesRelationManager::class,
        ];
    }
}
