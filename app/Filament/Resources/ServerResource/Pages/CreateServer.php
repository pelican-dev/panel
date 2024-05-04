<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use App\Models\Allocation;
use App\Models\Egg;
use App\Models\Node;
use App\Services\Allocations\AssignmentService;
use App\Services\Servers\ServerCreationService;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Closure;
use Filament\Forms;
use Illuminate\Support\HtmlString;

class CreateServer extends CreateRecord
{
    protected static string $resource = ServerResource::class;
    protected static bool $canCreateAnother = false;

    public function form(Form $form): Form
    {
        return $form
            ->columns([
                'default' => 2,
                'sm' => 2,
                'md' => 4,
                'lg' => 6,
            ])
            ->schema([
                Forms\Components\TextInput::make('external_id')
                    ->maxLength(191)
                    ->hidden(),

                Forms\Components\TextInput::make('name')
                    ->prefixIcon('tabler-server')
                    ->label('Display Name')
                    ->suffixAction(Forms\Components\Actions\Action::make('random')
                        ->icon('tabler-dice-' . random_int(1, 6))
                        ->action(function (Forms\Set $set, Forms\Get $get) {
                            $egg = Egg::find($get('egg_id'));
                            $prefix = $egg ? str($egg->name)->lower()->kebab() . '-' : '';

                            $set('name', $prefix . fake()->domainWord);
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
                    ->default(fn () => Node::query()->latest()->first()?->id)
                    ->columnSpan(2)
                    ->live()
                    ->relationship('node', 'name')
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('allocation_id', null))
                    ->required(),

                Forms\Components\Select::make('allocation_id')
                    ->preload()
                    ->live()
                    ->prefixIcon('tabler-network')
                    ->label('Primary Allocation')
                    ->columnSpan(2)
                    ->disabled(fn (Forms\Get $get) => $get('node_id') === null)
                    ->searchable(['ip', 'port', 'ip_alias'])
                    ->afterStateUpdated(function (Forms\Set $set) {
                        $set('allocation_additional', null);
                        $set('allocation_additional.needstobeastringhere.extra_allocations', null);
                    })
                    ->getOptionLabelFromRecordUsing(
                        fn (Allocation $allocation) => "$allocation->ip:$allocation->port" .
                            ($allocation->ip_alias ? " ($allocation->ip_alias)" : '')
                    )
                    ->placeholder(function (Forms\Get $get) {
                        $node = Node::find($get('node_id'));

                        if ($node?->allocations) {
                            return 'Select an Allocation';
                        }

                        return 'Create a New Allocation';
                    })
                    ->relationship(
                        'allocation',
                        'ip',
                        fn (Builder $query, Forms\Get $get) => $query
                            ->where('node_id', $get('node_id'))
                            ->whereNull('server_id'),
                    )
                    ->createOptionForm(fn (Forms\Get $get) => [
                        Forms\Components\TextInput::make('allocation_ip')
                            ->datalist(Node::find($get('node_id'))?->ipAddresses() ?? [])
                            ->label('IP Address')
                            ->inlineLabel()
                            ->ipv4()
                            ->helperText("Usually your machine's public IP unless you are port forwarding.")
                            // ->selectablePlaceholder(false)
                            ->required(),
                        Forms\Components\TextInput::make('allocation_alias')
                            ->label('Alias')
                            ->inlineLabel()
                            ->default(null)
                            ->datalist([
                                $get('name'),
                                Egg::find($get('egg_id'))?->name,
                            ])
                            ->helperText('Optional display name to help you remember what these are.')
                            ->required(false),
                        Forms\Components\TagsInput::make('allocation_ports')
                            ->placeholder('Examples: 27015, 27017-27019')
                            ->helperText(new HtmlString('
                                These are the ports that users can connect to this Server through.
                                <br />
                                You would have to port forward these on your home network.
                            '))
                            ->label('Ports')
                            ->inlineLabel()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $ports = collect();
                                $update = false;
                                foreach ($state as $portEntry) {
                                    if (!str_contains($portEntry, '-')) {
                                        if (is_numeric($portEntry)) {
                                            $ports->push((int) $portEntry);

                                            continue;
                                        }

                                        // Do not add non numerical ports
                                        $update = true;

                                        continue;
                                    }

                                    $update = true;
                                    [$start, $end] = explode('-', $portEntry);
                                    if (!is_numeric($start) || !is_numeric($end)) {
                                        continue;
                                    }

                                    $start = max((int) $start, 0);
                                    $end = min((int) $end, 2 ** 16 - 1);
                                    for ($i = $start; $i <= $end; $i++) {
                                        $ports->push($i);
                                    }
                                }

                                $uniquePorts = $ports->unique()->values();
                                if ($ports->count() > $uniquePorts->count()) {
                                    $update = true;
                                    $ports = $uniquePorts;
                                }

                                $sortedPorts = $ports->sort()->values();
                                if ($sortedPorts->all() !== $ports->all()) {
                                    $update = true;
                                    $ports = $sortedPorts;
                                }

                                if ($update) {
                                    $set('allocation_ports', $ports->all());
                                }
                            })
                            ->splitKeys(['Tab', ' ', ','])
                            ->required(),
                    ])
                    ->createOptionUsing(function (array $data, Forms\Get $get): int {
                        return collect(
                            resolve(AssignmentService::class)->handle(Node::find($get('node_id')), $data)
                        )->first();
                    })
                    ->required(),

                Forms\Components\Repeater::make('allocation_additional')
                    ->label('Additional Allocations')
                    ->columnSpan(2)
                    ->addActionLabel('Add Allocation')
                    ->disabled(fn (Forms\Get $get) => $get('allocation_id') === null)
                    // ->addable() TODO disable when all allocations are taken
                    // ->addable() TODO disable until first additional allocation is selected
                    ->simple(
                        Forms\Components\Select::make('extra_allocations')
                            ->live()
                            ->preload()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->prefixIcon('tabler-network')
                            ->label('Additional Allocations')
                            ->columnSpan(2)
                            ->disabled(fn (Forms\Get $get) => $get('../../node_id') === null)
                            ->searchable(['ip', 'port', 'ip_alias'])
                            ->getOptionLabelFromRecordUsing(
                                fn (Allocation $allocation) => "$allocation->ip:$allocation->port" .
                                    ($allocation->ip_alias ? " ($allocation->ip_alias)" : '')
                            )
                            ->placeholder('Select additional Allocations')
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->relationship(
                                'allocations',
                                'ip',
                                fn (Builder $query, Forms\Get $get, Forms\Components\Select $component, $state) => $query
                                    ->where('node_id', $get('../../node_id'))
                                    ->whereNot('id', $get('../../allocation_id'))
                                    ->whereNull('server_id'),
                            ),
                    ),

                Forms\Components\Textarea::make('description')
                    ->hidden()
                    ->default('')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('egg_id')
                    ->disabledOn('edit')
                    ->prefixIcon('tabler-egg')
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 5,
                    ])
                    ->relationship('egg', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get, $old) {
                        $egg = Egg::query()->find($state);
                        $set('startup', $egg->startup);
                        $set('image', '');

                        $variables = $egg->variables ?? [];
                        $serverVariables = collect();
                        foreach ($variables as $variable) {
                            $serverVariables->add($variable->toArray());
                        }

                        $variables = [];
                        $set($path = 'server_variables', $serverVariables->sortBy(['sort'])->all());
                        for ($i = 0; $i < $serverVariables->count(); $i++) {
                            $set("$path.$i.variable_value", $serverVariables[$i]['default_value']);
                            $set("$path.$i.variable_id", $serverVariables[$i]['id']);
                            $variables[$serverVariables[$i]['env_variable']] = $serverVariables[$i]['default_value'];
                        }

                        $set('environment', $variables);

                        $previousEgg = Egg::query()->find($old);
                        if (!$get('name') || $previousEgg?->getKebabName() === $get('name')) {
                            $set('name', $egg->getKebabName());
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

                Forms\Components\Select::make('select_image')
                    ->label('Docker Image Name')
                    ->prefixIcon('tabler-brand-docker')
                    ->live()
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
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 3,
                    ]),

                Forms\Components\TextInput::make('image')
                    ->label('Docker Image')
                    ->prefixIcon('tabler-brand-docker')
                    ->live()
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
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 3,
                    ]),

                Forms\Components\Fieldset::make('Application Feature Limits')
                    ->inlineLabel()
                    ->hiddenOn('create')
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
                            ->suffixIcon('tabler-network')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('database_limit')
                            ->suffixIcon('tabler-database')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('backup_limit')
                            ->suffixIcon('tabler-copy-check')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),

                Forms\Components\Textarea::make('startup')
                    ->hintIcon('tabler-code')
                    ->label('Startup Command')
                    ->required()
                    ->live()
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 4,
                        'md' => 4,
                        'lg' => 6,
                    ])
                    ->rows(function ($state) {
                        return str($state)->explode("\n")->reduce(
                            fn (int $carry, $line) => $carry + floor(strlen($line) / 125),
                            0
                        );
                    }),

                Forms\Components\Hidden::make('environment')->default([]),

                Forms\Components\Hidden::make('start_on_completion')->default(true),

                Forms\Components\Section::make('Egg Variables')
                    ->icon('tabler-eggs')
                    ->iconColor('primary')
                    ->collapsible()
                    ->collapsed()
                    ->columnSpan(([
                        'default' => 2,
                        'sm' => 4,
                        'md' => 4,
                        'lg' => 6,
                    ]))
                    ->schema([
                        Forms\Components\Placeholder::make('Select an egg first to show its variables!')
                            ->hidden(fn (Forms\Get $get) => !empty($get('server_variables'))),

                        Forms\Components\Repeater::make('server_variables')
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
                                    ->rules([
                                        fn (Forms\Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                            $validator = Validator::make(['validatorkey' => $value], [
                                                'validatorkey' => $get('rules'),
                                            ]);

                                            if ($validator->fails()) {
                                                $message = str($validator->errors()->first())->replace('validatorkey', $get('name'));

                                                $fail($message);
                                            }
                                        },
                                    ]);

                                $select = Forms\Components\Select::make('variable_value')
                                    ->hidden($this->shouldHideComponent(...))
                                    ->options($this->getSelectOptionsFromRules(...))
                                    ->selectablePlaceholder(false);

                                $components = [$text, $select];

                                /** @var Forms\Components\Component $component */
                                foreach ($components as &$component) {
                                    $component = $component
                                        ->live()
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

                Forms\Components\Section::make('Resource Management')
                    // ->hiddenOn('create')
                    ->collapsed()
                    ->icon('tabler-server-cog')
                    ->iconColor('primary')
                    ->columns(2)
                    ->columnSpan(([
                        'default' => 2,
                        'sm' => 4,
                        'md' => 4,
                        'lg' => 6,
                    ]))
                    ->schema([
                        Forms\Components\TextInput::make('memory')
                            ->default(0)
                            ->label('Allocated Memory')
                            ->suffix('MB')
                            ->required()
                            ->numeric(),

                        Forms\Components\TextInput::make('swap')
                            ->default(0)
                            ->label('Swap Memory')
                            ->suffix('MB')
                            ->helperText('0 disables swap and -1 allows unlimited swap')
                            ->minValue(-1)
                            ->required()
                            ->numeric(),

                        Forms\Components\TextInput::make('disk')
                            ->default(0)
                            ->label('Disk Space Limit')
                            ->suffix('MB')
                            ->required()
                            ->numeric(),

                        Forms\Components\TextInput::make('cpu')
                            ->default(0)
                            ->label('CPU Limit')
                            ->suffix('%')
                            ->required()
                            ->numeric(),

                        Forms\Components\TextInput::make('threads')
                            ->hint('Advanced')
                            ->hintColor('danger')
                            ->helperText('Examples: 0, 0-1,3, or 0,1,3,4')
                            ->label('CPU Pinning')
                            ->suffixIcon('tabler-cpu')
                            ->maxLength(191),

                        Forms\Components\TextInput::make('io')
                            ->helperText('The IO performance relative to other running containers')
                            ->label('Block IO Proportion')
                            ->required()
                            ->minValue(0)
                            ->maxValue(1000)
                            ->step(10)
                            ->default(0)
                            ->numeric(),

                        Forms\Components\ToggleButtons::make('oom_disabled')
                            ->label('OOM Killer')
                            ->inline()
                            ->default(false)
                            ->options([
                                false => 'Disabled',
                                true => 'Enabled',
                            ])
                            ->colors([
                                false => 'success',
                                true => 'danger',
                            ])
                            ->icons([
                                false => 'tabler-sword-off',
                                true => 'tabler-sword',
                            ])
                            ->required(),
                    ]),
            ]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data['allocation_additional'] = collect($data['allocation_additional'])->filter()->all();

        /** @var ServerCreationService $service */
        $service = resolve(ServerCreationService::class);

        return $service->handle($data);
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
}
