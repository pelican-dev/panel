<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
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
                    ->label('Container Status')
                    ->inlineLabel()
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
                    ])
                    ->inline(),

                Forms\Components\ToggleButtons::make('status')
                    ->label('Server State')
                    ->helperText('')
                    ->inlineLabel()
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
                    ])
                    ->inline(),

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
                    ->label('Run Egg Install Script?')
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

                Forms\Components\ToggleButtons::make('custom_image')
                    ->live()
                    ->label('Custom Image?')
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
                    ])
                    ->inline(),

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
                            ->label('')
                            ->relationship('serverVariables')
                            ->grid()
                            ->deletable(false)
                            ->addable(false)
                            ->schema([
                                Forms\Components\TextInput::make('variable_value')
                                    ->rules([
                                        fn (ServerVariable $variable): Closure => function (string $attribute, $value, Closure $fail) use ($variable) {
                                            $validator = Validator::make(['validatorkey' => $value], [
                                                'validatorkey' => $variable->variable->rules,
                                            ]);

                                            if ($validator->fails()) {
                                                $message = str($validator->errors()->first())->replace('validatorkey', $variable->variable->name);

                                                $fail($message);
                                            }
                                        },
                                    ])
                                    ->label(fn (ServerVariable $variable) => $variable->variable->name)
                                    ->hintIcon('tabler-code')
                                    ->hintIconTooltip(fn (ServerVariable $variable) => $variable->variable->rules)
                                    ->prefix(fn (ServerVariable $variable) => '{{' . $variable->variable->env_variable . '}}')
                                    ->helperText(fn (ServerVariable $variable) => $variable->variable->description ?: '—')
                                    ->maxLength(191),

                                Forms\Components\Hidden::make('variable_id'),
                            ])
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
                    ->schema([
                        Forms\Components\ToggleButtons::make('unlimited_mem')
                            ->label('Memory')
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('memory', 0))
                            ->inlineLabel()->inline()
                            ->live()
                            ->formatStateUsing(fn (Forms\Get $get) => $get('memory') <= 0)
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
                            ->disabled(fn (Forms\Get $get) => $get('unlimited_mem'))
                            ->label('Memory Limit')
                            ->suffix('MB')
                            ->required()
                            ->inlineLabel()
                            ->columnSpan(2)
                            ->numeric(),

                        Forms\Components\ToggleButtons::make('unlimited_disk')
                            ->label('Disk Space')
                            ->inlineLabel()->inline()
                            ->live()
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('disk', 0))
                            ->formatStateUsing(fn (Forms\Get $get) => $get('disk') <= 0)
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
                            ->disabled(fn (Forms\Get $get) => $get('unlimited_disk'))
                            ->label('Disk Space Limit')
                            ->suffix('MB')
                            ->required()
                            ->inlineLabel()
                            ->columnSpan(2)
                            ->numeric(),

                        Forms\Components\ToggleButtons::make('unlimited_cpu')
                            ->label('CPU')
                            ->inlineLabel()->inline()
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('cpu', 0))
                            ->live()
                            ->formatStateUsing(fn (Forms\Get $get) => $get('cpu') <= 0)
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
                            ->disabled(fn (Forms\Get $get) => $get('unlimited_cpu'))
                            ->label('CPU Limit')
                            ->suffix('%')
                            ->required()
                            ->inlineLabel()
                            ->columnSpan(2)
                            ->numeric(),

                        Forms\Components\Hidden::make('io')
                            ->helperText('The IO performance relative to other running containers')
                            ->label('Block IO Proportion')
                            ->required(),
                        //                            ->numeric()
                        //                            ->minValue(0)
                        //                            ->maxValue(1000)
                        //                            ->step(10)

                        Forms\Components\ToggleButtons::make('swap_support')
                            ->label('Enable Swap Memory')
                            ->columnSpan(2)
                            ->inlineLabel()->inline()
                            ->formatStateUsing(fn (Forms\Get $get) => $get('swap') <= 0)
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
                            ->disabled(fn (Forms\Get $get) => $get('swap_support'))
                            ->label('Swap Memory')
                            ->suffix('MB')
                            ->minValue(-1)
                            ->columnSpan(2)
                            ->inlineLabel()
                            ->required()
                            ->numeric(),

                        Forms\Components\ToggleButtons::make('oom_disabled')
                            ->label('OOM Killer')
                            ->inline()
                            ->columnSpan(2)
                            ->options([
                                false => 'Disabled',
                                true => 'Enabled',
                            ])
                            ->colors([
                                false => 'success',
                                true => 'danger',
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
            Actions\DeleteAction::make('Force Delete')
                ->label('Force Delete')
                ->successRedirectUrl(route('filament.admin.resources.servers.index'))
                ->color('danger')
                ->after(fn (Server $server) => resolve(ServerDeletionService::class)->withForce()->handle($server))
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
}
