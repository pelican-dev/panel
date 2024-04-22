<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EggResource\Pages;
use AbdelhamidErrahmouni\FilamentMonacoEditor\MonacoEditor;
use App\Models\Egg;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EggResource extends Resource
{
    protected static ?string $model = Egg::class;

    public static function getLabel(): string
    {
        return trans_choice('strings.eggs', 1);
    }

    protected static ?string $navigationIcon = 'tabler-eggs';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $recordRouteKeyName = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()->tabs([
                    Forms\Components\Tabs\Tab::make(trans('strings.configuration'))
                        ->columns(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 4])
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(191)
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2])
                                ->label(trans('strings.name'))
                                ->helperText(trans('admin/eggs.descriptions.name')),
                            Forms\Components\TextInput::make('uuid')
                                ->disabled()
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2])
                                ->label(trans('strings.uuid'))
                                ->helperText(trans('admin/eggs.descriptions.uuid')),
                            Forms\Components\Textarea::make('description')
                                ->rows(3)
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2])
                                ->label(trans('strings.description'))
                                ->helperText(trans('admin/eggs.descriptions.description')),
                            Forms\Components\TextInput::make('author')
                                ->required()
                                ->maxLength(191)
                                ->disabled()
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2])
                                ->label(trans('strings.author'))
                                ->helperText(trans('admin/eggs.descriptions.author')),
                            Forms\Components\Textarea::make('startup')
                                ->rows(2)
                                ->columnSpanFull()
                                ->required()
                                ->label(trans('strings.startup'))
                                ->helperText(trans('admin/eggs.descriptions.startup')),
                            Forms\Components\TagsInput::make('file_denylist')
                                ->placeholder('denied-file.txt')
                                ->helperText('A list of files that the end user is not allowed to edit.')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2]),
                            Forms\Components\TagsInput::make('features')
                                ->placeholder('Add Feature')
                                ->helperText('')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2]),
                            Forms\Components\Toggle::make('force_outgoing_ip')
                                ->label(trans('strings.force_outgoing_ip'))
                                ->helperText(trans('admin/eggs.descriptions.force_outgoing_ip')),
                            Forms\Components\Toggle::make('script_is_privileged')
                                ->helperText('The docker images available to servers using this egg.'),
                            Forms\Components\TextInput::make('update_url')
                                ->disabled()
                                ->helperText('Not implemented.')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2]),
                            Forms\Components\KeyValue::make('docker_images')
                                ->columnSpanFull()
                                ->required()
                                ->label(trans('strings.docker_images'))
                                ->addActionLabel('Add Image')
                                ->keyLabel(trans('strings.name'))
                                ->valueLabel(trans('strings.image_uri'))
                                ->helperText(trans('admin/eggs.descriptions.docker_images')),
                        ]),

                    Forms\Components\Tabs\Tab::make('Process Management')
                        ->columns()
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
                    Forms\Components\Tabs\Tab::make('Egg Variables')
                        ->columnSpanFull()
                        ->columns(2)
                        ->schema([
                            Forms\Components\Repeater::make('variables')
                                ->grid()
                                ->relationship('variables')
                                ->name('name')
                                ->columns(2)
                                ->reorderable()
                                ->collapsible()
                                ->collapsed()
                                ->orderColumn()
                                ->columnSpan(2)
                                ->itemLabel(fn (array $state) => $state['name'])
                                ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                    $data['default_value'] ??= '';
                                    $data['description'] ??= '';
                                    $data['rules'] ??= '';

                                    return $data;
                                })
                                ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                    $data['default_value'] ??= '';
                                    $data['description'] ??= '';
                                    $data['rules'] ??= '';

                                    return $data;
                                })
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->live()
                                        ->debounce(750)
                                        ->maxLength(191)
                                        ->columnSpanFull()
                                        ->label(trans('strings.name'))
                                        ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('env_variable', str($state)->trim()->snake()->upper()->toString())
                                        )
                                        ->required(),
                                    Forms\Components\Textarea::make('description')->columnSpanFull()->label(trans('strings.description')),
                                    Forms\Components\TextInput::make('env_variable')
                                        ->label('Environment Variable')
                                        ->maxLength(191)
                                        ->hint(fn ($state) => "{{{$state}}}")
                                        ->required(),
                                    Forms\Components\TextInput::make('default_value')->maxLength(191),
                                    Forms\Components\Textarea::make('rules')->rows(3)->columnSpanFull(),
                                ]),
                        ]),
                    Forms\Components\Tabs\Tab::make('Install Script')
                        ->columns(3)
                        ->schema([

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

                            MonacoEditor::make('script_install')
                                ->columnSpanFull()
                                ->fontSize('16px')
                                ->language('shell')
                                ->view('filament.plugins.monaco-editor'),
                        ]),

                ])->columnSpanFull()->persistTabInQueryString(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->checkIfRecordIsSelectableUsing(fn (Egg $egg) => $egg->servers_count <= 0)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Id')
                    ->hidden()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->icon('tabler-egg')
                    ->label(trans('strings.name'))
                    ->description(fn ($record): ?string => $record->description)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->label(trans('strings.author'))
                    ->hidden()
                    ->searchable(),
                Tables\Columns\TextColumn::make('servers_count')
                    ->counts('servers')
                    ->icon('tabler-server')
                    ->label(trans_choice('strings.servers', 2)),
                Tables\Columns\TextColumn::make('script_container')
                    ->searchable()
                    ->hidden(),
                Tables\Columns\TextColumn::make('copyFrom.name')
                    ->hidden()
                    ->sortable(),
                Tables\Columns\TextColumn::make('script_entry')
                    ->hidden()
                    ->searchable(),
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
