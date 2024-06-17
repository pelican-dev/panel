<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use App\Models\Egg;
use App\Models\Node;
use App\Models\Objects\Endpoint;
use App\Services\Servers\RandomWordService;
use App\Services\Servers\ServerCreationService;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;
use Closure;

class CreateServer extends CreateRecord
{
    protected static string $resource = ServerResource::class;
    protected static bool $canCreateAnother = false;

    public ?Node $node = null;
    public ?Egg $egg = null;
    public array $ports = [];
    public array $eggDefaultPorts = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Information')
                        ->label('Information')
                        ->icon('tabler-info-circle')
                        ->completedIcon('tabler-check')
                        ->columns([
                            'default' => 2,
                            'sm' => 2,
                            'md' => 4,
                            'lg' => 6,
                        ])
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->prefixIcon('tabler-server')
                                ->label('Name')
                                ->suffixAction(Forms\Components\Actions\Action::make('random')
                                    ->icon('tabler-dice-' . random_int(1, 6))
                                    ->action(function (Forms\Set $set, Forms\Get $get) {
                                        $egg = Egg::find($get('egg_id'));
                                        $prefix = $egg ? str($egg->name)->lower()->kebab() . '-' : '';

                                        $word = (new RandomWordService())->word();

                                        $set('name', $prefix . $word);
                                    }))
                                ->columnSpan([
                                    'default' => 2,
                                    'sm' => 4,
                                    'md' => 2,
                                    'lg' => 3,
                                ])
                                ->required()
                                ->maxLength(191),

                            Forms\Components\Select::make('owner_id')
                                ->prefixIcon('tabler-user')
                                ->default(auth()->user()->id)
                                ->label('Owner')
                                ->columnSpan([
                                    'default' => 2,
                                    'sm' => 4,
                                    'md' => 2,
                                    'lg' => 3,
                                ])
                                ->relationship('user', 'username')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\Select::make('node_id')
                                ->disabledOn('edit')
                                ->prefixIcon('tabler-server-2')
                                ->default(fn () => ($this->node = Node::query()->latest()->first())?->id)
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 3,
                                ])
                                ->live()
                                ->relationship('node', 'name')
                                ->searchable()
                                ->preload()
                                ->afterStateUpdated(function (Forms\Set $set, $state) {
                                    $this->node = Node::find($state);
                                })
                                ->required(),

                            Forms\Components\TextInput::make('description')
                                ->placeholder('Description')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 3,
                                ])
                                ->label('Notes'),
                        ]),

                    Wizard\Step::make('Egg Configuration')
                        ->label('Egg Configuration')
                        ->icon('tabler-egg')
                        ->completedIcon('tabler-check')
                        ->columns([
                            'default' => 1,
                            'sm' => 2,
                            'md' => 2,
                            'lg' => 4,
                        ])
                        ->schema([

                            Forms\Components\Select::make('egg_id')
                                ->disabledOn('edit')
                                ->prefixIcon('tabler-egg')
                                ->columnSpan([
                                    'default' => 2,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 3,
                                ])
                                ->relationship('egg', 'name')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get, $old) {
                                    $this->egg = Egg::query()->find($state);
                                    $set('startup', $this->egg?->startup);
                                    $set('image', '');

                                    $this->resetEggVariables($set, $get);

                                    $previousEgg = Egg::query()->find($old);
                                    if (!$get('name') || $previousEgg?->getKebabName() === $get('name')) {
                                        $set('name', $this->egg->getKebabName());
                                    }
                                })
                                ->required(),

                            Forms\Components\ToggleButtons::make('skip_scripts')
                                ->label('Run Egg Install Script?')
                                ->default(false)
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'md' => 1,
                                    'lg' => 1,
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
                                ->inline()
                                ->required(),

                            Forms\Components\TagsInput::make('ports')
                                ->columnSpan(2)
                                ->placeholder('Example: 25565, 8080, 1337-1340')
                                ->splitKeys(['Tab', ' ', ','])
                                ->hidden(fn () => !$this->egg)
                                ->helperText(new HtmlString('
                                These are the ports that users can connect to this Server through.
                                <br />
                                You would typically port forward these on your home network.
                            '))
                                ->label('Ports')
                                ->afterStateUpdated(self::ports(...))
                                ->live(),

                            Forms\Components\Repeater::make('assignments')
                                ->columnSpan(2)
                                ->defaultItems(fn () => count($this->eggDefaultPorts))
                                ->label('Port Assignments')
                                ->helperText(fn (Forms\Get $get) => empty($get('ports')) ? 'You must add ports to assign them!' : '')
                                ->live()
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(false)
                                ->hidden(fn () => !$this->egg)
                                ->simple(
                                    Forms\Components\Select::make('port')
                                        ->live()
                                        ->disabled(fn (Forms\Get $get) => empty($get('../../ports')) || empty($get('../../assignments')))
                                        ->prefix(function (Forms\Components\Component $component) {
                                            $key = str($component->getStatePath())->beforeLast('.')->afterLast('.')->toString();

                                            return $key;
                                        })
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->options(fn (Forms\Get $get) => $this->ports)
                                        ->required(),
                                ),

                            Forms\Components\Textarea::make('startup')
                                ->hidden(fn () => !$this->egg)
                                ->hintIcon('tabler-code')
                                ->label('Startup Command')
                                ->required()
                                ->live()
                                ->disabled(fn (Forms\Get $get) => $this->egg === null)
                                ->afterStateUpdated($this->resetEggVariables(...))
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 2,
                                    'lg' => 4,
                                ])
                                ->rows(function ($state) {
                                    return str($state)->explode("\n")->reduce(
                                        fn (int $carry, $line) => $carry + floor(strlen($line) / 125),
                                        0
                                    );
                                }),

                            Forms\Components\Hidden::make('environment')->default([]),

                            Forms\Components\Hidden::make('start_on_completion')->default(true),

                            Forms\Components\Section::make('Variables')
                                ->icon('tabler-eggs')
                                ->iconColor('primary')
                                ->hidden(fn (Forms\Get $get) => $get('egg_id') === null)
                                ->collapsible()
                                ->columnSpanFull()
                                ->schema([
                                    Forms\Components\Placeholder::make('Select an egg first to show its variables!')
                                        ->hidden(fn (Forms\Get $get) => $get('egg_id')),

                                    Forms\Components\Placeholder::make('The selected egg has no variables!')
                                        ->hidden(fn (Forms\Get $get) => !$get('egg_id') ||
                                            Egg::query()->find($get('egg_id'))?->variables()?->count()
                                        ),

                                    Forms\Components\Repeater::make('server_variables')
                                        ->label('')
                                        ->relationship('serverVariables')
                                        ->saveRelationshipsBeforeChildrenUsing(null)
                                        ->saveRelationshipsUsing(null)
                                        ->grid(2)
                                        ->reorderable(false)
                                        ->addable(false)
                                        ->deletable(false)
                                        ->default([])
                                        ->hidden(fn ($state) => empty($state))
                                        ->schema(function () {

                                            $text = Forms\Components\TextInput::make('variable_value')
                                                ->hidden($this->shouldHideComponent(...))
                                                ->maxLength(191)
                                                ->required(fn (Forms\Get $get) => in_array('required', explode('|', $get('rules'))))
                                                ->rules(
                                                    fn (Forms\Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                                        $validator = Validator::make(['validatorkey' => $value], [
                                                            'validatorkey' => $get('rules'),
                                                        ]);

                                                        if ($validator->fails()) {
                                                            $message = str($validator->errors()->first())->replace('validatorkey', $get('name'))->toString();

                                                            $fail($message);
                                                        }
                                                    },
                                                );

                                            $select = Forms\Components\Select::make('variable_value')
                                                ->hidden($this->shouldHideComponent(...))
                                                ->options($this->getSelectOptionsFromRules(...))
                                                ->selectablePlaceholder(false);

                                            $components = [$text, $select];

                                            foreach ($components as &$component) {
                                                $component = $component
                                                    ->live(onBlur: true)
                                                    ->hintIcon('tabler-code')
                                                    ->label(fn (Forms\Get $get) => $get('name'))
                                                    ->hintIconTooltip(fn (Forms\Get $get) => $get('rules'))
                                                    ->prefix(fn (Forms\Get $get) => '{{' . $get('env_variable') . '}}')
                                                    ->helperText(fn (Forms\Get $get) => empty($get('description')) ? '—' : $get('description'))
                                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                                        $environment = $get($envPath = '../../environment');
                                                        $environment[$get('env_variable')] = $state;
                                                        $set($envPath, $environment);
                                                    });
                                            }

                                            return $components;
                                        })
                                        ->columnSpan(2),
                                ]),
                        ]),

                    Wizard\Step::make('Environment Configuration')
                        ->label('Environment Configuration')
                        ->icon('tabler-brand-docker')
                        ->completedIcon('tabler-check')
                        ->schema([
                            Forms\Components\Fieldset::make('Resource Limits')
                                ->columnSpan([
                                    'default' => 2,
                                    'sm' => 4,
                                    'md' => 4,
                                    'lg' => 6,
                                ])
                                ->columns([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 3,
                                    'lg' => 3,
                                ])
                                ->schema([
                                    Forms\Components\Grid::make()
                                        ->columns(4)
                                        ->columnSpanFull()
                                        ->schema([
                                            Forms\Components\ToggleButtons::make('unlimited_mem')
                                                ->label('Memory')->inlineLabel()->inline()
                                                ->default(true)
                                                ->afterStateUpdated(fn (Forms\Set $set) => $set('memory', 0))
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

                                            Forms\Components\TextInput::make('memory')
                                                ->dehydratedWhenHidden()
                                                ->hidden(fn (Forms\Get $get) => $get('unlimited_mem'))
                                                ->label('Memory Limit')->inlineLabel()
                                                ->suffix(config('panel.use_binary_prefix') ? 'MiB' : 'MB')
                                                ->default(0)
                                                ->required()
                                                ->columnSpan(2)
                                                ->numeric()
                                                ->minValue(0),
                                        ]),

                                    Forms\Components\Grid::make()
                                        ->columns(4)
                                        ->columnSpanFull()
                                        ->schema([
                                            Forms\Components\ToggleButtons::make('unlimited_disk')
                                                ->label('Disk Space')->inlineLabel()->inline()
                                                ->default(true)
                                                ->live()
                                                ->afterStateUpdated(fn (Forms\Set $set) => $set('disk', 0))
                                                ->options([
                                                    true => 'Unlimited',
                                                    false => 'Limited',
                                                ])
                                                ->colors([
                                                    true => 'primary',
                                                    false => 'warning',
                                                ])
                                                ->columnSpan(2),

                                            Forms\Components\TextInput::make('disk')
                                                ->dehydratedWhenHidden()
                                                ->hidden(fn (Forms\Get $get) => $get('unlimited_disk'))
                                                ->label('Disk Space Limit')->inlineLabel()
                                                ->suffix(config('panel.use_binary_prefix') ? 'MiB' : 'MB')
                                                ->default(0)
                                                ->required()
                                                ->columnSpan(2)
                                                ->numeric()
                                                ->minValue(0),
                                        ]),

                                    Forms\Components\Grid::make()
                                        ->columns(4)
                                        ->columnSpanFull()
                                        ->schema([
                                            Forms\Components\ToggleButtons::make('unlimited_cpu')
                                                ->label('CPU')->inlineLabel()->inline()
                                                ->default(true)
                                                ->afterStateUpdated(fn (Forms\Set $set) => $set('cpu', 0))
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

                                            Forms\Components\TextInput::make('cpu')
                                                ->dehydratedWhenHidden()
                                                ->hidden(fn (Forms\Get $get) => $get('unlimited_cpu'))
                                                ->label('CPU Limit')->inlineLabel()
                                                ->suffix('%')
                                                ->default(0)
                                                ->required()
                                                ->columnSpan(2)
                                                ->numeric()
                                                ->minValue(0)
                                                ->helperText('100% equals one CPU core.'),
                                        ]),

                                    Forms\Components\Grid::make()
                                        ->columns(4)
                                        ->columnSpanFull()
                                        ->schema([
                                            Forms\Components\ToggleButtons::make('swap_support')
                                                ->live()
                                                ->label('Enable Swap Memory')
                                                ->inlineLabel()
                                                ->inline()
                                                ->columnSpan(2)
                                                ->default('disabled')
                                                ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                    $value = match ($state) {
                                                        'unlimited' => -1,
                                                        'disabled' => 0,
                                                        'limited' => 128,
                                                        default => throw new \LogicException('Invalid state'),
                                                    };

                                                    $set('swap', $value);
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

                                            Forms\Components\TextInput::make('swap')
                                                ->dehydratedWhenHidden()
                                                ->hidden(fn (Forms\Get $get) => match ($get('swap_support')) {
                                                    'disabled', 'unlimited' => true,
                                                    default => false,
                                                })
                                                ->label('Swap Memory')
                                                ->default(0)
                                                ->suffix(config('panel.use_binary_prefix') ? 'MiB' : 'MB')
                                                ->minValue(-1)
                                                ->columnSpan(2)
                                                ->inlineLabel()
                                                ->required()
                                                ->integer(),
                                        ]),

                                    Forms\Components\Hidden::make('io')
                                        ->helperText('The IO performance relative to other running containers')
                                        ->label('Block IO Proportion')
                                        ->default(config('panel.default_io_weight')),

                                    Forms\Components\Grid::make()
                                        ->columns(4)
                                        ->columnSpanFull()
                                        ->schema([
                                            Forms\Components\ToggleButtons::make('oom_killer')
                                                ->label('OOM Killer')
                                                ->inlineLabel()->inline()
                                                ->default(false)
                                                ->columnSpan(2)
                                                ->options([
                                                    false => 'Disabled',
                                                    true => 'Enabled',
                                                ])
                                                ->colors([
                                                    false => 'success',
                                                    true => 'danger',
                                                ]),

                                            Forms\Components\TextInput::make('oom_disabled_hidden')
                                                ->hidden(),
                                        ]),
                                ]),

                            Forms\Components\Fieldset::make('Feature Limits')
                                ->inlineLabel()
                                ->columnSpan([
                                    'default' => 2,
                                    'sm' => 4,
                                    'md' => 4,
                                    'lg' => 6,
                                ])
                                ->columns([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 3,
                                    'lg' => 3,
                                ])
                                ->schema([
                                    Forms\Components\TextInput::make('allocation_limit')
                                        ->label('Allocations')
                                        ->suffixIcon('tabler-network')
                                        ->required()
                                        ->numeric()
                                        ->default(0),
                                    Forms\Components\TextInput::make('database_limit')
                                        ->label('Databases')
                                        ->suffixIcon('tabler-database')
                                        ->required()
                                        ->numeric()
                                        ->default(0),
                                    Forms\Components\TextInput::make('backup_limit')
                                        ->label('Backups')
                                        ->suffixIcon('tabler-copy-check')
                                        ->required()
                                        ->numeric()
                                        ->default(0),
                                ]),
                            Forms\Components\Fieldset::make('Docker Settings')
                                ->columnSpan([
                                    'default' => 2,
                                    'sm' => 4,
                                    'md' => 4,
                                    'lg' => 6,
                                ])
                                ->columns([
                                    'default' => 1,
                                    'sm' => 2,
                                    'md' => 3,
                                    'lg' => 3,
                                ])
                                ->schema([
                                    Forms\Components\Select::make('select_image')
                                        ->label('Image Name')
                                        ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('image', $state))
                                        ->options(function ($state, Forms\Get $get, Forms\Set $set) {
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
                                        ->columnSpan(1),

                                    Forms\Components\TextInput::make('image')
                                        ->label('Image')
                                        ->debounce(500)
                                        ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                            $egg = Egg::query()->find($get('egg_id'));
                                            $images = $egg->docker_images ?? [];

                                            if (in_array($state, $images)) {
                                                $set('select_image', $state);
                                            } else {
                                                $set('select_image', 'ghcr.io/custom-image');
                                            }
                                        })
                                        ->placeholder('Enter a custom Image')
                                        ->columnSpan(2),

                                    Forms\Components\KeyValue::make('docker_labels')
                                        ->label('Container Labels')
                                        ->keyLabel('Title')
                                        ->valueLabel('Description')
                                        ->columnSpan(3),

                                    Forms\Components\CheckboxList::make('mounts')
                                        ->live()
                                        ->relationship('mounts')
                                        ->options(fn () => $this->node?->mounts->mapWithKeys(fn ($mount) => [$mount->id => $mount->name]) ?? [])
                                        ->descriptions(fn () => $this->node?->mounts->mapWithKeys(fn ($mount) => [$mount->id => "$mount->source -> $mount->target"]) ?? [])
                                        ->label('Mounts')
                                        ->helperText(fn () => $this->node?->mounts->isNotEmpty() ? '' : 'No Mounts exist for this Node')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ])
                    ->columnSpanFull()
                    ->nextAction(fn (Action $action) => $action->label('Next Step'))
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                                        <x-filament::button
                                                type="submit"
                                                size="sm"
                                            >
                                                Create Server
                                            </x-filament::button>
                                        BLADE))),
            ]);
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function handleRecordCreation(array $data): Model
    {
        foreach (array_keys($this->eggDefaultPorts) as $i => $env) {
            $data['environment'][$env] = $data['ports'][$data['assignments'][$i]];
        }

        /** @var ServerCreationService $service */
        $service = resolve(ServerCreationService::class);

        return $service->handle($data, validateVariables: false);
    }

    private function shouldHideComponent(Forms\Get $get, Forms\Components\Component $component): bool
    {
        $containsRuleIn = str($get('rules'))->explode('|')->reduce(
            fn ($result, $value) => $result === true && !str($value)->startsWith('in:'), true
        );

        if ($component instanceof Forms\Components\Select) {
            return $containsRuleIn;
        }

        if ($component instanceof Forms\Components\TextInput) {
            return !$containsRuleIn;
        }

        throw new \Exception('Component type not supported: ' . $component::class);
    }

    private function getSelectOptionsFromRules(Forms\Get $get): array
    {
        $inRule = str($get('rules'))->explode('|')->reduce(
            fn ($result, $value) => str($value)->startsWith('in:') ? $value : $result, ''
        );

        return str($inRule)
            ->after('in:')
            ->explode(',')
            ->each(fn ($value) => str($value)->trim())
            ->mapWithKeys(fn ($value) => [$value => $value])
            ->all();
    }

    public function ports($state, Forms\Set $set)
    {
        $ports = collect();
        foreach ($state as $portEntry) {
            if (str_contains($portEntry, '-')) {
                [$start, $end] = explode('-', $portEntry);
                if (!is_numeric($start) || !is_numeric($end)) {
                    continue;
                }

                $start = max((int) $start, Endpoint::PORT_FLOOR);
                $end = min((int) $end, Endpoint::PORT_CEIL);
                for ($i = $start; $i <= $end; $i++) {
                    $ports->push($i);
                }
            }

            if (!is_numeric($portEntry)) {
                continue;
            }

            $ports->push((int) $portEntry);
        }

        $uniquePorts = $ports->unique()->values();
        if ($ports->count() > $uniquePorts->count()) {
            $ports = $uniquePorts;
        }

        $ports = $ports->filter(fn ($port) => $port > 1024 && $port < 65535)->values();

        $set('ports', $ports->all());
        $this->ports = $ports->all();
    }

    public function resetEggVariables(Forms\Set $set, Forms\Get $get)
    {
        $set('assignments', []);

        $i = 0;
        $this->eggDefaultPorts = [];
        if (str_contains($get('startup'), '{{SERVER_PORT}}')) {
            $this->eggDefaultPorts['SERVER_PORT'] = null;
            $set('assignments.SERVER_PORT', ['port' => null]);
        }

        $variables = $this->egg->variables ?? [];
        $serverVariables = collect();
        $this->ports = [];
        foreach ($variables as $variable) {
            if (str_contains($variable->rules, 'port')) {
                $this->eggDefaultPorts[$variable->env_variable] = $variable->default_value;
                $this->ports[] = (int) $variable->default_value;

                $set("assignments.$variable->env_variable", ['port' => $i++]);

                continue;
            }

            $serverVariables->add($variable->toArray());
        }

        $set('ports', $this->ports);

        $variables = [];
        $set($path = 'server_variables', $serverVariables->sortBy(['sort'])->all());
        for ($i = 0; $i < $serverVariables->count(); $i++) {
            $set("$path.$i.variable_value", $serverVariables[$i]['default_value']);
            $set("$path.$i.variable_id", $serverVariables[$i]['id']);
            $variables[$serverVariables[$i]['env_variable']] = $serverVariables[$i]['default_value'];
        }

        $set('environment', $variables);
    }
}
