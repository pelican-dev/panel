<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EggResource\Pages;
use App\Models\Egg;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EggResource extends Resource
{
    protected static ?string $model = Egg::class;

    protected static ?string $navigationIcon = 'heroicon-o-ellipsis-horizontal-circle';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $recordRouteKeyName = 'id';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()->tabs([
                    Forms\Components\Tabs\Tab::make('Configuration')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('name')->required()->maxLength(191)
                                ->helperText('A simple, human-readable name to use as an identifier for this Egg.'),
                            Forms\Components\Textarea::make('description')->rows(5)
                                ->helperText('A description of this Egg that will be displayed throughout the Panel as needed.'),
                            Forms\Components\TextInput::make('uuid')->disabled()
                                ->helperText('This is the globally unique identifier for this Egg which the Daemon uses as an identifier.'),
                            Forms\Components\TextInput::make('author')->required()->maxLength(191)->disabled()
                                ->helperText('The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.'),
                            Forms\Components\Toggle::make('force_outgoing_ip')->required()
                                ->helperText("Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.
                                    Required for certain games to work properly when the Node has multiple public IP addresses.
                                    Enabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node."),
                            Forms\Components\Textarea::make('startup')->rows(5)
                                ->helperText('The default startup command that should be used for new servers using this Egg.'),
                            Forms\Components\KeyValue::make('docker_images')
                                ->columnSpanFull()
                                ->addActionLabel('Add Image')
                                ->keyLabel('Name')
                                ->valueLabel('Image URI')
                                ->helperText('The docker images available to servers using this egg.'),
                        ]),

                    Forms\Components\Tabs\Tab::make('Process Management')
                        ->columns(2)
                        ->schema([
                            Forms\Components\Select::make('config_from')
                                ->label('Copy Settings From')
                                ->placeholder('None')
                                ->relationship('configFrom', 'name', ignoreRecord: true)
                                ->helperText('If you would like to default to settings from another Egg select it from the menu above.'),

                            Forms\Components\TextInput::make('config_stop')
                                ->maxLength(191)
                                ->label('Stop Command')
                                ->helperText('The command that should be sent to server processes to stop them gracefully. If you need to send a SIGINT you should enter ^C here.'),

                            Forms\Components\Textarea::make('config_startup')->rows(10)->json()
                                ->label('Start Configuration')
                                ->helperText('List of values the daemon should be looking for when booting a server to determine completion.'),

                            Forms\Components\Textarea::make('config_files')->rows(10)->json()
                                ->label('Configuration Files')
                                ->helperText('This should be a JSON representation of configuration files to modify and what parts should be changed.'),

                            Forms\Components\Textarea::make('config_logs')->rows(10)->json()
                                ->label('Log Configuration')
                                ->helperText('This should be a JSON representation of where log files are stored, and whether or not the daemon should be creating custom logs.'),
                        ]),
                    Forms\Components\Tabs\Tab::make('Variables')
                        ->columnSpanFull()
                        // ->columns(2)
                        ->schema([
                            Forms\Components\Repeater::make('Blah')
                                ->relationship('variables')
                                ->name('name')
                                ->columns(1)
                                ->columnSpan(1)
                                ->itemLabel(fn (array $state) => $state['name'])
                                ->schema([
                                    Forms\Components\TextInput::make('name')->maxLength(191)->columnSpanFull(),
                                    Forms\Components\Textarea::make('description')->columnSpanFull(),
                                    Forms\Components\TextInput::make('env_variable')->maxLength(191),
                                    Forms\Components\TextInput::make('default_value')->maxLength(191),
                                    Forms\Components\Textarea::make('rules')->rows(3)->columnSpanFull()->required(),
                                ])
                        ]),
                    Forms\Components\Tabs\Tab::make('Install Script')
                        ->columns(3)
                        ->schema([
                            Forms\Components\Textarea::make('script_install')->rows(20)->columnSpanFull(),

                            Forms\Components\Select::make('copy_script_from')
                                ->placeholder('None')
                                ->relationship('scriptFrom', 'name', ignoreRecord: true),

                            Forms\Components\TextInput::make('script_container')
                                ->required()
                                ->maxLength(191)
                                ->default('alpine:3.4'),

                            Forms\Components\TextInput::make('script_entry')
                                ->required()
                                ->maxLength(191)
                                ->default('ash'),
                        ]),

                ])->columnSpanFull(),

                // Forms\Components\TagsInput::make('features'),
                // Forms\Components\TagsInput::make('file_denylist')->placeholder('new-file.txt'),
                // Forms\Components\TextInput::make('update_url'),
                // Forms\Components\Toggle::make('script_is_privileged')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Id')
                    // ->hidden()
                    ->searchable(),
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->hidden()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->hidden()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->words(50)
                    ->wrap(),
                Tables\Columns\TextColumn::make('servers_count')
                    ->counts('servers')
                    ->icon('heroicon-m-server-stack')
                    ->label('Servers'),
                Tables\Columns\TextColumn::make('script_container')
                    ->searchable()
                    ->hidden(),
                Tables\Columns\TextColumn::make('copyFrom.name')
                    ->hidden()
                    ->sortable(),
                Tables\Columns\TextColumn::make('script_entry')
                    ->hidden()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                //
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
            'index' => Pages\ListEggs::route('/'),
            'create' => Pages\CreateEgg::route('/create'),
            'edit' => Pages\EditEgg::route('/{record}/edit'),
        ];
    }
}
