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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;

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
                    ->prefixIcon('tabler-server-2')
                    ->default(fn () => Node::query()->latest()->first()->id)
                    ->columnSpan(2)
                    ->live()
                    ->relationship('node', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('allocation_id')
                    ->prefixIcon('tabler-network')
                    ->label('Primary Allocation')
                    ->columnSpan(3)
                    ->disabled(fn (Forms\Get $get) => $get('node_id') === null)
                    ->searchable(['ip', 'port', 'ip_alias'])
                    ->getOptionLabelFromRecordUsing(fn (Allocation $allocation) => "$allocation->ip:$allocation->port" .
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
                        fn (Builder $query, Forms\Get $get) => $query->where('node_id', $get('node_id')),
                    )
                    ->createOptionForm([
                        Forms\Components\TextInput::make('allocation_ip')
                            ->label('IP Address')
                            ->helperText('Usually your machine\'s public IP unless you are port forwarding.')
                            ->required(),
                        Forms\Components\TextInput::make('allocation_alias')
                            ->label('Alias')
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

                Forms\Components\Textarea::make('description')
                    ->hidden()
                    ->default('')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('egg_id')
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

                        $set($path = 'server_variables', $serverVariables->all());
                        for ($i = 0; $i < $serverVariables->count(); $i++) {
                            $set("$path.$i.variable_value", $serverVariables[$i]['default_value']);
                            $set("$path.$i.variable_id", $serverVariables[$i]['id']);
                        }
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
                    ->inline()
                    ->required(),

                Forms\Components\Select::make('image')
                    ->hidden(fn (Forms\Get $get) => $get('custom_image'))
                    ->disabled(fn (Forms\Get $get) => $get('custom_image'))
                    ->label('Docker Image')
                    ->prefixIcon('tabler-brand-docker')
                    ->options(fn (Forms\Get $get) => array_flip(Egg::find($get('egg_id'))->docker_images ?? []))
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
                    ->inline(),

                Forms\Components\Fieldset::make('Application Feature Limits')
                    ->inlineLabel()
                    ->hiddenOn('create')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('allocation_limit')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('database_limit')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('backup_limit')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),

                Forms\Components\Fieldset::make('Resource Management')
                    // ->inlineLabel()
                    ->hiddenOn('create')
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
                            ->minValue(10)
                            ->maxValue(1000)
                            ->step(10)
                            ->default(500)
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
                            ->required(),
                    ]),

                Forms\Components\Textarea::make('startup')
                    ->hintIcon('tabler-code')
                    ->label('Startup Command')
                    ->required()
                    ->live()
                    ->rows(function ($state) {
                        return str($state)->explode("\n")->reduce(fn (int $carry, $line) => $carry + floor(strlen($line) / 125),
                            0);
                    })
                    ->columnSpanFull(),

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
                            ->afterStateUpdated(function () {

                            })

                            ->schema([
                                Forms\Components\TextInput::make('variable_value')
                                    //->rule(0, fn (Forms\Get $get) => str($get('rules'))) // TODO

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

                                Forms\Components\Hidden::make('variable_id')->default(0)
                            ])
                            ->columnSpanFull(),
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
                    Tables\Actions\DeleteBulkAction::make(),
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
