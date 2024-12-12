<?php

namespace App\Filament\Admin\Resources\EggResource\Pages;

use AbdelhamidErrahmouni\FilamentMonacoEditor\MonacoEditor;
use App\Filament\Admin\Resources\EggResource;
use App\Filament\Admin\Resources\EggResource\RelationManagers\ServersRelationManager;
use App\Filament\Components\Actions\ExportEggAction;
use App\Filament\Components\Actions\ImportEggAction;
use App\Models\Egg;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Pages\EditRecord;

class EditEgg extends EditRecord
{
    protected static string $resource = EggResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make()->tabs([
                    Tab::make('Configuration')
                        ->columns(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 4])
                        ->icon('tabler-egg')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 1])
                                ->helperText('A simple, human-readable name to use as an identifier for this Egg.'),
                            TextInput::make('uuid')
                                ->label('Egg UUID')
                                ->disabled()
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 2])
                                ->helperText('This is the globally unique identifier for this Egg which Wings uses as an identifier.'),
                            TextInput::make('id')
                                ->label('Egg ID')
                                ->disabled(),
                            Textarea::make('description')
                                ->rows(3)
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2])
                                ->helperText('A description of this Egg that will be displayed throughout the Panel as needed.'),
                            TextInput::make('author')
                                ->required()
                                ->maxLength(255)
                                ->email()
                                ->disabled()
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2])
                                ->helperText('The author of this version of the Egg. Uploading a new Egg configuration from a different author will change this.'),
                            Textarea::make('startup')
                                ->rows(2)
                                ->columnSpanFull()
                                ->required()
                                ->helperText('The default startup command that should be used for new servers using this Egg.'),
                            TagsInput::make('file_denylist')
                                ->hidden() // latest wings breaks it.
                                ->placeholder('denied-file.txt')
                                ->helperText('A list of files that the end user is not allowed to edit.')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2]),
                            TagsInput::make('features')
                                ->placeholder('Add Feature')
                                ->helperText('')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2]),
                            Toggle::make('force_outgoing_ip')
                                ->inline(false)
                                ->hintIcon('tabler-question-mark')
                                ->hintIconTooltip("Forces all outgoing network traffic to have its Source IP NATed to the IP of the server's primary allocation IP.
                                    Required for certain games to work properly when the Node has multiple public IP addresses.
                                    Enabling this option will disable internal networking for any servers using this egg, causing them to be unable to internally access other servers on the same node."),
                            Hidden::make('script_is_privileged')
                                ->helperText('The docker images available to servers using this egg.'),
                            TagsInput::make('tags')
                                ->placeholder('Add Tags')
                                ->helperText('')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2]),
                            TextInput::make('update_url')
                                ->label('Update URL')
                                ->url()
                                ->hintIcon('tabler-question-mark')
                                ->hintIconTooltip('URLs must point directly to the raw .json file.')
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2]),
                            KeyValue::make('docker_images')
                                ->live()
                                ->columnSpanFull()
                                ->required()
                                ->addActionLabel('Add Image')
                                ->keyLabel('Name')
                                ->valueLabel('Image URI')
                                ->helperText('The docker images available to servers using this egg.'),
                        ]),
                    Tab::make('Process Management')
                        ->columns()
                        ->icon('tabler-server-cog')
                        ->schema([
                            Select::make('config_from')
                                ->label('Copy Settings From')
                                ->placeholder('None')
                                ->relationship('configFrom', 'name', ignoreRecord: true)
                                ->helperText('If you would like to default to settings from another Egg select it from the menu above.'),
                            TextInput::make('config_stop')
                                ->maxLength(255)
                                ->label('Stop Command')
                                ->helperText('The command that should be sent to server processes to stop them gracefully. If you need to send a SIGINT you should enter ^C here.'),
                            Textarea::make('config_startup')->rows(10)->json()
                                ->label('Start Configuration')
                                ->helperText('List of values the daemon should be looking for when booting a server to determine completion.'),
                            Textarea::make('config_files')->rows(10)->json()
                                ->label('Configuration Files')
                                ->helperText('This should be a JSON representation of configuration files to modify and what parts should be changed.'),
                            Textarea::make('config_logs')->rows(10)->json()
                                ->label('Log Configuration')
                                ->helperText('This should be a JSON representation of where log files are stored, and whether or not the daemon should be creating custom logs.'),
                        ]),
                    Tab::make('Egg Variables')
                        ->columnSpanFull()
                        ->icon('tabler-variable')
                        ->schema([
                            Repeater::make('variables')
                                ->label('')
                                ->grid()
                                ->relationship('variables')
                                ->name('name')
                                ->reorderable()
                                ->collapsible()->collapsed()
                                ->orderColumn()
                                ->addActionLabel('New Variable')
                                ->itemLabel(fn (array $state) => $state['name'])
                                ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                    $data['default_value'] ??= '';
                                    $data['description'] ??= '';
                                    $data['rules'] ??= [];
                                    $data['user_viewable'] ??= '';
                                    $data['user_editable'] ??= '';

                                    return $data;
                                })
                                ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                    $data['default_value'] ??= '';
                                    $data['description'] ??= '';
                                    $data['rules'] ??= [];
                                    $data['user_viewable'] ??= '';
                                    $data['user_editable'] ??= '';

                                    return $data;
                                })
                                ->schema([
                                    TextInput::make('name')
                                        ->live()
                                        ->debounce(750)
                                        ->maxLength(255)
                                        ->columnSpanFull()
                                        ->afterStateUpdated(fn (Set $set, $state) => $set('env_variable', str($state)->trim()->snake()->upper()->toString())
                                        )
                                        ->required(),
                                    Textarea::make('description')->columnSpanFull(),
                                    TextInput::make('env_variable')
                                        ->label('Environment Variable')
                                        ->maxLength(255)
                                        ->prefix('{{')
                                        ->suffix('}}')
                                        ->hintIcon('tabler-code')
                                        ->hintIconTooltip(fn ($state) => "{{{$state}}}")
                                        ->required(),
                                    TextInput::make('default_value')->maxLength(255),
                                    Fieldset::make('User Permissions')
                                        ->schema([
                                            Checkbox::make('user_viewable')->label('Viewable'),
                                            Checkbox::make('user_editable')->label('Editable'),
                                        ]),
                                    TagsInput::make('rules')
                                        ->columnSpanFull()
                                        ->placeholder('Add Rule')
                                        ->reorderable()
                                        ->suggestions([
                                            'required',
                                            'nullable',
                                            'string',
                                            'integer',
                                            'numeric',
                                            'boolean',
                                            'alpha',
                                            'alpha_dash',
                                            'alpha_num',
                                            'url',
                                            'email',
                                            'regex:',
                                            'min:',
                                            'max:',
                                            'between:',
                                            'between:1024,65535',
                                            'in:',
                                            'in:true,false',
                                        ]),
                                ]),
                        ]),
                    Tab::make('Install Script')
                        ->columns(3)
                        ->icon('tabler-file-download')
                        ->schema([
                            Select::make('copy_script_from')
                                ->placeholder('None')
                                ->relationship('scriptFrom', 'name', ignoreRecord: true),
                            TextInput::make('script_container')
                                ->required()
                                ->maxLength(255)
                                ->default('alpine:3.4'),
                            TextInput::make('script_entry')
                                ->required()
                                ->maxLength(255)
                                ->default('ash'),
                            MonacoEditor::make('script_install')
                                ->label('Install Script')
                                ->placeholderText('')
                                ->columnSpanFull()
                                ->fontSize('16px')
                                ->language('shell')
                                ->view('filament.plugins.monaco-editor'),
                        ]),
                ])->columnSpanFull()->persistTabInQueryString(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->disabled(fn (Egg $egg): bool => $egg->servers()->count() > 0)
                ->label(fn (Egg $egg): string => $egg->servers()->count() <= 0 ? 'Delete' : 'In Use'),
            ExportEggAction::make(),
            ImportEggAction::make(),
            $this->getSaveFormAction()->formId('form'),
        ];
    }

    public function refreshForm(): void
    {
        $this->fillForm();
    }

    protected function getFormActions(): array
    {
        return [];
    }

    public function getRelationManagers(): array
    {
        return [
            ServersRelationManager::class,
        ];
    }
}
