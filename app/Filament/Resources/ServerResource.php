<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServerResource\Pages;
use App\Filament\Resources\ServerResource\RelationManagers;
use App\Models\Allocation;
use App\Models\Egg;
use App\Models\Node;
use App\Models\Server;
use App\Services\Allocations\AssignmentService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
                    ->label('Display Name')
                    ->columnSpan(4)
                    ->required()
                    ->maxLength(191),

                Forms\Components\Select::make('owner_id')
                    ->default(auth()->user()->id)
                    ->label('Owner')
                    ->columnSpan(2)
                    ->relationship('user', 'username')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('node_id')
                    ->default(fn () => Node::query()->latest()->first()->id)
                    ->columnSpan(2)
                    ->live()
                    ->relationship('node', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('allocation_id')
                    ->label('Primary Allocation')
                    ->columnSpan(3)
                    ->disabled(fn (Forms\Get $get) => $get('node_id') === null)
                    ->searchable(['ip', 'port', 'ip_alias'])
                    ->getOptionLabelFromRecordUsing(fn (Allocation $allocation) =>
                        "$allocation->ip:$allocation->port" .
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
                            ->required(),
                        Forms\Components\TextInput::make('allocation_alias')
                            ->label('Alias')
                            ->required(false),
                        Forms\Components\TagsInput::make('allocation_ports')
                            ->placeholder('Examples: 27015, 27017-27019')
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
                    ->columnSpan(2)
                    ->relationship('egg', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('startup', Egg::find($state)->startup))
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
                    ->options(fn (Forms\Get $get) => array_flip(Egg::find($get('egg_id'))->docker_images ?? []))
                    ->selectablePlaceholder(false)
                    ->required(),

                Forms\Components\TextInput::make('image')
                    ->label('Docker Image')
                    ->placeholder('Or enter a custom Image...')
                    ->columnSpan(2),

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
                    ->label('Startup Command')
                    ->required()
                    ->live()
                    ->rows(function ($state) {
                        return str($state)->explode("\n")->reduce(fn (int $carry, $line)
                            => $carry + floor(strlen($line) / 125),
                        0);
                    })
                    ->columnSpanFull(),

                Forms\Components\Repeater::make('s')
                    ->reorderable(false)
                    ->addable(false)
                    ->deletable(false)
                    ->label('Egg Variables')
                    ->columnSpanFull()
                    ->grid(2)
                    ->default(function (Forms\Get $get) {
                        $variables = Egg::find($get('egg_id'))->variables ?? [];
                        $serverVariables = collect();
                        foreach ($variables as $variable) {
                            $serverVariables->add($variable->toArray());
                        }

                        return $serverVariables->all();
                    })
                    // ->relationship('serverVariables')
                    // ->default([1, 2, 3])
                    ->name('name')
                    // ->itemLabel(fn (array $state) => 'asdf')
                    ->schema([
                        Forms\Components\TextInput::make('value')
                            ->label(fn (Forms\Get $get) => $get('name'))
                            ->helperText(fn (Forms\Get $get) => new HtmlString("
                                {$get('description')}<br />
                                Access in Startup: <code>{{{$get('env_variable')}}}</code><br />
                                Validation Rules: <code>{$get('rules')}</code>
                            "))
                            // ->inlineLabel()
                            ->maxLength(191),
//                        Forms\Components\Textarea::make('description')->columnSpanFull(),
//                        Forms\Components\TextInput::make('env_variable')->maxLength(191),
//                        Forms\Components\TextInput::make('default_value')->maxLength(191),
//                        Forms\Components\Textarea::make('rules')->rows(3)->columnSpanFull()->required(),
                    ])
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
