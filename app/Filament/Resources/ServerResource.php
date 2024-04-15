<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServerResource\Pages;
use App\Models\Allocation;
use App\Models\Egg;
use App\Models\Node;
use App\Models\Server;
use App\Services\Allocations\AssignmentService;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;

    protected static ?string $navigationIcon = 'tabler-brand-docker';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(6)
            ->schema([
                Forms\Components\TextInput::make('external_id')
                    ->maxLength(191)
                    ->hidden(),

                Forms\Components\TextInput::make('name')
                    ->prefixIcon('tabler-server')
                    ->label('Display Name')
                    ->suffixAction(Forms\Components\Actions\Action::make('random')
                        ->icon('tabler-dice-' . random_int(1, 6))
                        ->color('primary')
                        ->action(function (Forms\Set $set, Forms\Get $get) {
                            $egg = Egg::find($get('egg_id'));
                            $prefix = $egg ? str($egg->name)->lower()->kebab() . '-' : '';

                            $set('name', $prefix . fake()->domainWord);
                        }))
                    ->columnSpan(4)
                    ->required()
                    ->maxLength(191),

                Forms\Components\Select::make('owner_id')
                    ->prefixIcon('tabler-user')
                    ->default(auth()->user()->id)
                    ->label('Owner')
                    ->columnSpan(2)
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
                            ->ipv4()
                            ->datalist(function () use ($get) {
                                $node = Node::find($get('node_id'));
                                if (is_ip($node->fqdn)) {
                                    return [$node->fqdn];
                                }

                                $validRecords = gethostbynamel($node->fqdn);
                                if (!$validRecords) {
                                    return [];
                                }

                                return $validRecords ?: [];
                            })
                            ->label('IP Address')
                            ->helperText("Usually your machine's public IP unless you are port forwarding.")
                            ->required(),
                        Forms\Components\TextInput::make('allocation_alias')
                            ->label('Alias')
                            ->default(null)
                            ->datalist([
                                $get('name'),
                                Egg::find($get('egg_id'))?->name,
                            ])
                            ->helperText('This is just a display only name to help you recognize what this Allocation is used for.')
                            ->required(false),
                        Forms\Components\TagsInput::make('allocation_ports')
                            ->placeholder('Examples: 27015, 27017-27019')
                            ->helperText('
                                These are the ports that users can connect to this Server through.
                                They usually consist of the port forwarded ones.
                            ')
                            ->label('Ports')
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
                            ->relationship(
                                'allocations',
                                'ip',
                                fn (Builder $query, Forms\Get $get, Forms\Components\Select $component, $state) => $query
                                    ->where('node_id', $get('../../node_id'))
                                    ->whereNotIn(
                                        'id',
                                        collect(($repeater = $component->getParentRepeater())->getState())
                                            ->pluck(
                                                (string) str($component->getStatePath())
                                                    ->after("{$repeater->getStatePath()}.")
                                                    ->after('.'),
                                            )
                                            ->flatten()
                                            ->diff(Arr::wrap($state))
                                            ->filter(fn (mixed $siblingItemState): bool => filled($siblingItemState))
                                            ->add($get('../../allocation_id'))
                                    )
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
                    ->columnSpan(2)
                    ->relationship('egg', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $egg = Egg::find($state);
                        $set('startup', $egg->startup);

                        $variables = $egg->variables ?? [];
                        $serverVariables = collect();
                        foreach ($variables as $variable) {
                            $serverVariables->add($variable->toArray());
                        }

                        $variables = [];
                        $set($path = 'server_variables', $serverVariables->all());
                        for ($i = 0; $i < $serverVariables->count(); $i++) {
                            $set("$path.$i.variable_value", $serverVariables[$i]['default_value']);
                            $set("$path.$i.variable_id", $serverVariables[$i]['id']);
                            $variables[$serverVariables[$i]['env_variable']] = $serverVariables[$i]['default_value'];
                        }

                        $set('environment', $variables);
                    })
                    ->required(),

                Forms\Components\ToggleButtons::make('skip_scripts')
                    ->label('Run Egg Install Script?')
                    ->default(false)
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

                Forms\Components\Select::make('image')
                    ->hidden(fn (Forms\Get $get) => $get('custom_image'))
                    ->disabled(fn (Forms\Get $get) => $get('custom_image'))
                    ->label('Docker Image')
                    ->prefixIcon('tabler-brand-docker')
                    ->options(function (Forms\Get $get, Forms\Set $set) {
                        $images = Egg::find($get('egg_id'))->docker_images ?? [];

                        $set('image', collect($images)->first());

                        return $images;
                    })
                    ->disabled(fn (Forms\Components\Select $component) => empty($component->getOptions()))
                    ->selectablePlaceholder(false)
                    ->columnSpan(2)
                    ->required(),

                Forms\Components\TextInput::make('image')
                    ->hidden(fn (Forms\Get $get) => !$get('custom_image'))
                    ->disabled(fn (Forms\Get $get) => !$get('custom_image'))
                    ->label('Docker Image')
                    ->placeholder('Enter a custom Image')
                    ->columnSpan(2)
                    ->required(),

                Forms\Components\ToggleButtons::make('custom_image')
                    ->live()
                    ->label('Custom Image?')
                    ->default(false)
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

                Forms\Components\Fieldset::make('Application Feature Limits')
                    ->inlineLabel()
                    ->hiddenOn('create')
                    ->columns(3)
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
                    ->rows(function ($state) {
                        return str($state)->explode("\n")->reduce(
                            fn (int $carry, $line) => $carry + floor(strlen($line) / 125),
                            0
                        );
                    })
                    ->columnSpanFull(),

                Forms\Components\Hidden::make('environment')->default([]),

                Forms\Components\Section::make('Egg Variables')
                    ->icon('tabler-eggs')
                    ->iconColor('primary')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Forms\Components\Placeholder::make('Select an egg first to show its variables!')
                            ->hidden(fn (Forms\Get $get) => !empty($get('server_variables'))),

                        Forms\Components\Repeater::make('server_variables')
                            ->relationship('serverVariables')
                            ->grid(2)
                            ->reorderable(false)
                            ->addable(false)
                            ->deletable(false)
                            ->default([])
                            ->hidden(fn ($state) => empty($state))
                            ->schema([
                                Forms\Components\TextInput::make('variable_value')
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
                                    ])
                                    ->label(fn (Forms\Get $get) => $get('name'))
                                    ->hint(fn (Forms\Get $get) => $get('rules'))
                                    ->prefix(fn (Forms\Get $get) => '{{' . $get('env_variable') . '}}')
                                    ->helperText(fn (Forms\Get $get) => empty($get('description')) ? '—' : $get('description'))
                                    ->maxLength(191),

                                Forms\Components\Hidden::make('variable_id')->default(0),
                            ])
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Resource Management')
                    // ->hiddenOn('create')
                    ->collapsed()
                    ->icon('tabler-server-cog')
                    ->iconColor('primary')
                    ->columns(3)
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

    public static function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->hidden()
                    ->label('UUID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->icon('tabler-brand-docker')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('node.name')
                    ->icon('tabler-server-2')
                    ->url(fn (Server $server): string => route('filament.admin.resources.nodes.edit', ['record' => $server->node]))
                    ->sortable(),
                Tables\Columns\TextColumn::make('egg.name')
                    ->icon('tabler-egg')
                    ->url(fn (Server $server): string => route('filament.admin.resources.eggs.edit', ['record' => $server->egg]))
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->icon('tabler-user')
                    ->label('Owner')
                    ->url(fn (Server $server): string => route('filament.admin.resources.users.edit', ['record' => $server->user]))
                    ->sortable(),
                Tables\Columns\SelectColumn::make('allocation.id')
                    ->label('Primary Allocation')
                    ->options(fn ($state, Server $server) => [$server->allocation->id => $server->allocation->address])
                    ->selectablePlaceholder(false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('image')->hidden(),
                Tables\Columns\TextColumn::make('backups_count')
                    ->counts('backups')
                    ->label('Backups')
                    ->icon('tabler-file-download')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServers::route('/'),
            'create' => Pages\CreateServer::route('/create'),
            'edit' => Pages\EditServer::route('/{record}/edit'),
        ];
    }
}
