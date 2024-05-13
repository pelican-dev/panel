<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use App\Services\Servers\RandomWordService;
use Filament\Actions;
use Filament\Forms;
use App\Enums\ContainerStatus;
use App\Enums\ServerState;
use App\Models\Egg;
use App\Models\Server;
use App\Models\ServerVariable;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Services\Servers\ServerDeletionService;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Validator;
use Closure;

class EditServer extends EditRecord
{
    protected static string $resource = ServerResource::class;

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
                Forms\Components\ToggleButtons::make('docker')
                    ->label('Container Status')->inline()->inlineLabel()
                    ->formatStateUsing(function ($state, Server $server) {
                        if ($server->node_id === null) {
                            return 'unknown';
                        }

                        /** @var DaemonServerRepository $service */
                        $service = resolve(DaemonServerRepository::class);
                        $details = $service->setServer($server)->getDetails();

                        return $details['state'] ?? 'unknown';
                    })
                    ->options(fn ($state) => collect(ContainerStatus::cases())->filter(fn ($containerStatus) => $containerStatus->value === $state)->mapWithKeys(
                        fn (ContainerStatus $state) => [$state->value => str($state->value)->replace('_', ' ')->ucwords()]
                    ))
                    ->colors(collect(ContainerStatus::cases())->mapWithKeys(
                        fn (ContainerStatus $status) => [$status->value => $status->color()]
                    ))
                    ->icons(collect(ContainerStatus::cases())->mapWithKeys(
                        fn (ContainerStatus $status) => [$status->value => $status->icon()]
                    ))
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 3,
                    ]),

                Forms\Components\ToggleButtons::make('status')
                    ->label('Server State')->inline()->inlineLabel()
                    ->helperText('')

                    ->formatStateUsing(fn ($state) => $state ?? ServerState::Normal)
                    ->options(fn ($state) => collect(ServerState::cases())->filter(fn ($serverState) => $serverState->value === $state)->mapWithKeys(
                        fn (ServerState $state) => [$state->value => str($state->value)->replace('_', ' ')->ucwords()]
                    ))
                    ->colors(collect(ServerState::cases())->mapWithKeys(
                        fn (ServerState $state) => [$state->value => $state->color()]
                    ))
                    ->icons(collect(ServerState::cases())->mapWithKeys(
                        fn (ServerState $state) => [$state->value => $state->icon()]
                    ))
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 3,
                    ]),

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

                Forms\Components\Textarea::make('description')
                    ->hidden()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('egg_id')
                    ->disabledOn('edit')
                    ->prefixIcon('tabler-egg')
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 6,
                    ])
                    ->relationship('egg', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\ToggleButtons::make('skip_scripts')
                    ->label('Run Egg Install Script?')->inline()
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

                Forms\Components\ToggleButtons::make('custom_image')
                    ->live()
                    ->label('Custom Image?')->inline()
                    ->formatStateUsing(function ($state, Forms\Get $get) {
                        if ($state !== null) {
                            return $state;
                        }

                        $images = Egg::find($get('egg_id'))->docker_images;

                        return !in_array($get('image'), $images);
                    })
                    ->options([
                        false => 'No',
                        true => 'Yes',
                    ])
                    ->colors([
                        false => 'primary',
                        true => 'danger',
                    ])
                    ->icons([
                        false => 'tabler-settings-cancel',
                        true => 'tabler-settings-check',
                    ]),

                Forms\Components\TextInput::make('image')
                    ->hidden(fn (Forms\Get $get) => !$get('custom_image'))
                    ->disabled(fn (Forms\Get $get) => !$get('custom_image'))
                    ->label('Docker Image')
                    ->placeholder('Enter a custom Image')
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 4,
                    ])
                    ->required(),

                Forms\Components\Select::make('image')
                    ->hidden(fn (Forms\Get $get) => $get('custom_image'))
                    ->disabled(fn (Forms\Get $get) => $get('custom_image'))
                    ->label('Docker Image')
                    ->prefixIcon('tabler-brand-docker')
                    ->options(fn (Forms\Get $get) => Egg::find($get('egg_id'))->docker_images)
                    ->disabled(fn (Forms\Components\Select $component) => empty($component->getOptions()))
                    ->selectablePlaceholder(false)
                    ->columnSpan([
                        'default' => 2,
                        'sm' => 2,
                        'md' => 2,
                        'lg' => 4,
                    ])
                    ->required(),

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

                Forms\Components\Hidden::make('start_on_completion'),

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
                        Forms\Components\Repeater::make('server_variables')
                            ->relationship('serverVariables')
                            ->grid()
                            ->reorderable(false)->addable(false)->deletable(false)
                            ->schema(function () {

                                $text = Forms\Components\TextInput::make('variable_value')
                                    ->hidden($this->shouldHideComponent(...))
                                    ->maxLength(191)
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

                                $select = Forms\Components\Select::make('variable_value')
                                    ->hidden($this->shouldHideComponent(...))
                                    ->options($this->getSelectOptionsFromRules(...))
                                    ->selectablePlaceholder(false);

                                $components = [$text, $select];

                                /** @var Forms\Components\Component $component */
                                foreach ($components as &$component) {
                                    $component = $component
                                        ->live(onBlur: true)
                                        ->hintIcon('tabler-code')
                                        ->label(fn (ServerVariable $serverVariable) => $serverVariable->variable->name)
                                        ->hintIconTooltip(fn (ServerVariable $serverVariable) => $serverVariable->variable->rules)
                                        ->prefix(fn (ServerVariable $serverVariable) => '{{' . $serverVariable->variable->env_variable . '}}')
                                        ->helperText(fn (ServerVariable $serverVariable) => empty($serverVariable->variable->description) ? '—' : $serverVariable->variable->description);
                                }

                                return $components;
                            })
                            ->columnSpan(2),
                    ]),

                Forms\Components\Section::make('Resource Management')
                    ->collapsed()
                    ->icon('tabler-server-cog')
                    ->iconColor('primary')
                    ->columns([
                        'default' => 2,
                        'sm' => 4,
                        'md' => 4,
                        'lg' => 4,
                    ])
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->columns(4)
                            ->columnSpanFull()
                            ->schema([
                                Forms\Components\ToggleButtons::make('unlimited_mem')
                                    ->label('Memory')->inlineLabel()->inline()
                                    ->afterStateUpdated(fn (Forms\Set $set) => $set('memory', 0))
                                    ->formatStateUsing(fn (Forms\Get $get) => $get('memory') == 0)
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
                                    ->suffix('MB')
                                    ->required()
                                    ->columnSpan(2)
                                    ->numeric(),
                            ]),

                        Forms\Components\Grid::make()
                            ->columns(4)
                            ->columnSpanFull()
                            ->schema([
                                Forms\Components\ToggleButtons::make('unlimited_disk')
                                    ->label('Disk Space')->inlineLabel()->inline()
                                    ->live()
                                    ->afterStateUpdated(fn (Forms\Set $set) => $set('disk', 0))
                                    ->formatStateUsing(fn (Forms\Get $get) => $get('disk') == 0)
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
                                    ->suffix('MB')
                                    ->required()
                                    ->columnSpan(2)
                                    ->numeric(),
                            ]),

                        Forms\Components\Grid::make()
                            ->columns(4)
                            ->columnSpanFull()
                            ->schema([
                                Forms\Components\ToggleButtons::make('unlimited_cpu')
                                    ->label('CPU')->inlineLabel()->inline()
                                    ->afterStateUpdated(fn (Forms\Set $set) => $set('cpu', 0))
                                    ->formatStateUsing(fn (Forms\Get $get) => $get('cpu') == 0)
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
                                    ->required()
                                    ->columnSpan(2)
                                    ->numeric(),
                            ]),

                        Forms\Components\Grid::make()
                            ->columns(4)
                            ->columnSpanFull()
                            ->schema([
                                Forms\Components\ToggleButtons::make('swap_support')
                                    ->live()
                                    ->label('Enable Swap Memory')->inlineLabel()->inline()
                                    ->columnSpan(2)
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        $value = match ($state) {
                                            'unlimited' => -1,
                                            'disabled' => 0,
                                            'limited' => 128,
                                        };

                                        $set('swap', $value);
                                    })
                                    ->formatStateUsing(function (Forms\Get $get) {
                                        return match (true) {
                                            $get('swap') > 0 => 'limited',
                                            $get('swap') == 0 => 'disabled',
                                            $get('swap') < 0 => 'unlimited',
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

                                Forms\Components\TextInput::make('swap')
                                    ->dehydratedWhenHidden()
                                    ->hidden(fn (Forms\Get $get) => match ($get('swap_support')) {
                                        'disabled', 'unlimited', true => true,
                                        'limited', false => false,
                                    })
                                    ->label('Swap Memory')->inlineLabel()
                                    ->suffix('MB')
                                    ->minValue(-1)
                                    ->columnSpan(2)
                                    ->required()
                                    ->integer(),
                            ]),

                        Forms\Components\Hidden::make('io')
                            ->helperText('The IO performance relative to other running containers')
                            ->label('Block IO Proportion'),

                        Forms\Components\Grid::make()
                            ->columns(4)
                            ->columnSpanFull()
                            ->schema([
                                Forms\Components\ToggleButtons::make('oom_killer')
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

                                Forms\Components\TextInput::make('oom_disabled_hidden')
                                    ->hidden(),
                            ]),

                        Forms\Components\Fieldset::make('Application Feature Limits')
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
                                    ->suffixIcon('tabler-network')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('database_limit')
                                    ->suffixIcon('tabler-database')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('backup_limit')
                                    ->suffixIcon('tabler-copy-check')
                                    ->required()
                                    ->numeric(),
                            ]),
                    ]),
            ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make('Delete')
                ->successRedirectUrl(route('filament.admin.resources.servers.index'))
                ->color('danger')
                ->after(fn (Server $server) => resolve(ServerDeletionService::class)->handle($server))
                ->requiresConfirmation(),
            Actions\Action::make('console')
                ->label('Console')
                ->icon('tabler-terminal')
                ->url(fn (Server $server) => "/server/$server->uuid_short"),
            $this->getSaveFormAction()->formId('form'),
        ];

    }
    protected function getFormActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['docker'], $data['status']);

        return $data;
    }

    public function getRelationManagers(): array
    {
        return [
            ServerResource\RelationManagers\AllocationsRelationManager::class,
        ];
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
