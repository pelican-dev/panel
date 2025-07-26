<?php

namespace App\Filament\Admin\Resources\EggResource\Pages;

use AbdelhamidErrahmouni\FilamentMonacoEditor\MonacoEditor;
use App\Filament\Admin\Resources\EggResource;
use App\Filament\Components\Actions\ExportEggAction;
use App\Filament\Components\Actions\ImportEggAction;
use App\Filament\Components\Forms\Fields\CopyFrom;
use App\Models\Egg;
use App\Models\EggVariable;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
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
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\Rules\Unique;

class EditEgg extends EditRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = EggResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make()->tabs([
                    Tab::make(trans('admin/egg.tabs.configuration'))
                        ->columns(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 4])
                        ->icon('tabler-egg')
                        ->schema([
                            TextInput::make('name')
                                ->label(trans('admin/egg.name'))
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 1])
                                ->helperText(trans('admin/egg.name_help')),
                            TextInput::make('uuid')
                                ->label(trans('admin/egg.egg_uuid'))
                                ->disabled()
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 2])
                                ->helperText(trans('admin/egg.uuid_help')),
                            TextInput::make('id')
                                ->label(trans('admin/egg.egg_id'))
                                ->disabled(),
                            Textarea::make('description')
                                ->label(trans('admin/egg.description'))
                                ->rows(3)
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2])
                                ->helperText(trans('admin/egg.description_help')),
                            TextInput::make('author')
                                ->label(trans('admin/egg.author'))
                                ->required()
                                ->maxLength(255)
                                ->email()
                                ->disabled()
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2])
                                ->helperText(trans('admin/egg.author_help_edit')),
                            Textarea::make('startup')
                                ->label(trans('admin/egg.startup'))
                                ->rows(3)
                                ->columnSpanFull()
                                ->required()
                                ->helperText(trans('admin/egg.startup_help')),
                            TagsInput::make('file_denylist')
                                ->label(trans('admin/egg.file_denylist'))
                                ->placeholder('denied-file.txt')
                                ->helperText(trans('admin/egg.file_denylist_help'))
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2]),
                            TagsInput::make('features')
                                ->label(trans('admin/egg.features'))
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 1]),
                            Toggle::make('force_outgoing_ip')
                                ->inline(false)
                                ->label(trans('admin/egg.force_ip'))
                                ->hintIcon('tabler-question-mark')
                                ->hintIconTooltip(trans('admin/egg.force_ip_help')),
                            Hidden::make('script_is_privileged')
                                ->helperText('The docker images available to servers using this egg.'),
                            TagsInput::make('tags')
                                ->label(trans('admin/egg.tags'))
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2]),
                            TextInput::make('update_url')
                                ->label(trans('admin/egg.update_url'))
                                ->url()
                                ->hintIcon('tabler-question-mark')
                                ->hintIconTooltip(trans('admin/egg.update_url_help'))
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 2, 'lg' => 2]),
                            KeyValue::make('docker_images')
                                ->label(trans('admin/egg.docker_images'))
                                ->live()
                                ->columnSpanFull()
                                ->required()
                                ->addActionLabel(trans('admin/egg.add_image'))
                                ->keyLabel(trans('admin/egg.docker_name'))
                                ->valueLabel(trans('admin/egg.docker_uri'))
                                ->helperText(trans('admin/egg.docker_help')),
                        ]),
                    Tab::make(trans('admin/egg.tabs.process_management'))
                        ->columns()
                        ->icon('tabler-server-cog')
                        ->schema([
                            CopyFrom::make('copy_process_from')
                                ->process(),
                            TextInput::make('config_stop')
                                ->label(trans('admin/egg.stop_command'))
                                ->maxLength(255)
                                ->helperText(trans('admin/egg.stop_command_help')),
                            Textarea::make('config_startup')->rows(10)->json()
                                ->label(trans('admin/egg.start_config'))
                                ->helperText(trans('admin/egg.start_config_help')),
                            Textarea::make('config_files')->rows(10)->json()
                                ->label(trans('admin/egg.config_files'))
                                ->helperText(trans('admin/egg.config_files_help')),
                            Textarea::make('config_logs')->rows(10)->json()
                                ->label(trans('admin/egg.log_config'))
                                ->helperText(trans('admin/egg.log_config_help')),
                        ]),
                    Tab::make(trans('admin/egg.tabs.egg_variables'))
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
                                ->addActionLabel(trans('admin/egg.add_new_variable'))
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
                                        ->label(trans('admin/egg.name'))
                                        ->live()
                                        ->debounce(750)
                                        ->maxLength(255)
                                        ->columnSpanFull()
                                        ->afterStateUpdated(fn (Set $set, $state) => $set('env_variable', str($state)->trim()->snake()->upper()->toString()))
                                        ->unique(modifyRuleUsing: fn (Unique $rule, Get $get) => $rule->where('egg_id', $get('../../id')), ignoreRecord: true)
                                        ->validationMessages([
                                            'unique' => trans('admin/egg.error_unique'),
                                        ])
                                        ->required(),
                                    Textarea::make('description')->label(trans('admin/egg.description'))->columnSpanFull(),
                                    TextInput::make('env_variable')
                                        ->label(trans('admin/egg.environment_variable'))
                                        ->maxLength(255)
                                        ->prefix('{{')
                                        ->suffix('}}')
                                        ->hintIcon('tabler-code')
                                        ->hintIconTooltip(fn ($state) => "{{{$state}}}")
                                        ->unique(modifyRuleUsing: fn (Unique $rule, Get $get) => $rule->where('egg_id', $get('../../id')), ignoreRecord: true)
                                        ->rules(EggVariable::getRulesForField('env_variable'))
                                        ->validationMessages([
                                            'unique' => trans('admin/egg.error_unique'),
                                            'required' => trans('admin/egg.error_required'),
                                            '*' => trans('admin/egg.error_reserved'),
                                        ])
                                        ->required(),
                                    TextInput::make('default_value')->label(trans('admin/egg.default_value')),
                                    Fieldset::make(trans('admin/egg.user_permissions'))
                                        ->schema([
                                            Checkbox::make('user_viewable')->label(trans('admin/egg.viewable')),
                                            Checkbox::make('user_editable')->label(trans('admin/egg.editable')),
                                        ]),
                                    TagsInput::make('rules')
                                        ->label(trans('admin/egg.rules'))
                                        ->columnSpanFull()
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
                    Tab::make(trans('admin/egg.tabs.install_script'))
                        ->columns(3)
                        ->icon('tabler-file-download')
                        ->schema([
                            CopyFrom::make('copy_script_from')
                                ->script(),
                            TextInput::make('script_container')
                                ->label(trans('admin/egg.script_container'))
                                ->required()
                                ->maxLength(255)
                                ->placeholder('ghcr.io/pelican-eggs/installers:debian'),
                            Select::make('script_entry')
                                ->label(trans('admin/egg.script_entry'))
                                ->native(false)
                                ->selectablePlaceholder(false)
                                ->options([
                                    'bash' => 'bash',
                                    'ash' => 'ash',
                                    '/bin/bash' => '/bin/bash',
                                ])
                                ->required(),
                            MonacoEditor::make('script_install')
                                ->label(trans('admin/egg.script_install'))
                                ->placeholderText('')
                                ->columnSpanFull()
                                ->fontSize('16px')
                                ->language('shell')
                                ->view('filament.plugins.monaco-editor'),
                        ]),
                ])->columnSpanFull()->persistTabInQueryString(),
            ]);
    }

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->disabled(fn (Egg $egg): bool => $egg->servers()->count() > 0)
                ->label(fn (Egg $egg): string => $egg->servers()->count() <= 0 ? trans('filament-actions::delete.single.label') : trans('admin/egg.in_use')),
            ExportEggAction::make(),
            ImportEggAction::make()
                ->multiple(false),
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
}
