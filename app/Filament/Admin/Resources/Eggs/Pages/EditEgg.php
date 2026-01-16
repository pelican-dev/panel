<?php

namespace App\Filament\Admin\Resources\Eggs\Pages;

use App\Enums\EditorLanguages;
use App\Filament\Admin\Resources\Eggs\EggResource;
use App\Filament\Components\Actions\ExportEggAction;
use App\Filament\Components\Actions\ImportEggAction;
use App\Filament\Components\Forms\Fields\CopyFrom;
use App\Filament\Components\Forms\Fields\MonacoEditor;
use App\Models\Egg;
use App\Models\EggVariable;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Unique;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EditEgg extends EditRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = EggResource::class;

    /**
     * @throws Exception
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()->tabs([
                    Tab::make('configuration')
                        ->label(trans('admin/egg.tabs.configuration'))
                        ->columns(['default' => 2, 'sm' => 2, 'md' => 4, 'lg' => 6])
                        ->icon('tabler-egg')
                        ->schema([
                            Grid::make(2)
                                ->columnSpan(1)
                                ->schema([
                                    Image::make('', '')
                                        ->hidden(fn ($record) => !$record->image)
                                        ->url(fn ($record) => $record->image)
                                        ->alt('')
                                        ->alignJustify()
                                        ->imageSize(150)
                                        ->columnSpanFull(),
                                    Flex::make([
                                        Action::make('uploadImage')
                                            ->iconButton()
                                            ->iconSize(IconSize::Large)
                                            ->icon('tabler-photo-up')
                                            ->modal()
                                            ->modalHeading('')
                                            ->modalSubmitActionLabel(trans('admin/egg.import.import_image'))
                                            ->schema([
                                                Tabs::make()
                                                    ->contained(false)
                                                    ->tabs([
                                                        Tab::make(trans('admin/egg.import.url'))
                                                            ->schema([
                                                                Hidden::make('imageUrl'),
                                                                Hidden::make('imageExtension'),
                                                                TextInput::make('image_url')
                                                                    ->label(trans('admin/egg.import.image_url'))
                                                                    ->reactive()
                                                                    ->autocomplete(false)
                                                                    ->debounce(500)
                                                                    ->afterStateUpdated(function ($state, Set $set) {
                                                                        if (!$state) {
                                                                            $set('image_url_error', null);
                                                                            $set('imageUrl', null);
                                                                            $set('imageExtension', null);

                                                                            return;
                                                                        }

                                                                        try {
                                                                            if (!filter_var($state, FILTER_VALIDATE_URL)) {
                                                                                throw new Exception(trans('admin/egg.import.invalid_url'));
                                                                            }

                                                                            $extension = strtolower(pathinfo(parse_url($state, PHP_URL_PATH), PATHINFO_EXTENSION));

                                                                            if (!array_key_exists($extension, Egg::IMAGE_FORMATS)) {
                                                                                throw new Exception(trans('admin/egg.import.unsupported_format', ['format' => implode(', ', array_keys(Egg::IMAGE_FORMATS))]));
                                                                            }

                                                                            $host = parse_url($state, PHP_URL_HOST);
                                                                            $ip = gethostbyname($host);

                                                                            if (
                                                                                filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false
                                                                            ) {
                                                                                throw new Exception(trans('admin/egg.import.no_local_ip'));
                                                                            }

                                                                            $set('imageUrl', $state);
                                                                            $set('imageExtension', $extension);
                                                                            $set('image_url_error', null);

                                                                        } catch (Exception $e) {
                                                                            $set('image_url_error', $e->getMessage());
                                                                            $set('imageUrl', null);
                                                                            $set('imageExtension', null);
                                                                        }
                                                                    }),
                                                                TextEntry::make('image_url_error')
                                                                    ->hiddenLabel()
                                                                    ->visible(fn ($get) => $get('image_url_error') !== null)
                                                                    ->afterStateHydrated(fn ($set, $get) => $get('image_url_error')),
                                                                Image::make(fn (Get $get) => $get('image_url'), '')
                                                                    ->imageSize(150)
                                                                    ->visible(fn ($get) => $get('image_url') && !$get('image_url_error'))
                                                                    ->alignCenter(),
                                                            ]),
                                                        Tab::make(trans('admin/egg.import.file'))
                                                            ->schema([
                                                                FileUpload::make('image')
                                                                    ->hiddenLabel()
                                                                    ->previewable()
                                                                    ->openable(false)
                                                                    ->downloadable(false)
                                                                    ->maxSize(256)
                                                                    ->maxFiles(1)
                                                                    ->columnSpanFull()
                                                                    ->alignCenter()
                                                                    ->imageEditor()
                                                                    ->image()
                                                                    ->disk('public')
                                                                    ->directory(Egg::ICON_STORAGE_PATH)
                                                                    ->acceptedFileTypes([
                                                                        'image/png',
                                                                        'image/jpeg',
                                                                        'image/webp',
                                                                        'image/svg+xml',
                                                                    ])
                                                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $record) {
                                                                        return $record->uuid . '.' . $file->getClientOriginalExtension();
                                                                    }),
                                                            ]),
                                                    ]),
                                            ])
                                            ->action(function (array $data, $record): void {
                                                if (!empty($data['imageUrl']) && !empty($data['imageExtension'])) {
                                                    $this->saveImageFromUrl($data['imageUrl'], $data['imageExtension'], $record);

                                                    Notification::make()
                                                        ->title(trans('admin/egg.import.image_updated'))
                                                        ->success()
                                                        ->send();

                                                    return;
                                                }

                                                if (!empty($data['image'])) {
                                                    Notification::make()
                                                        ->title(trans('admin/egg.import.image_updated'))
                                                        ->success()
                                                        ->send();

                                                    return;
                                                }

                                                if (empty($data['imageUrl']) && empty($data['image'])) {
                                                    Notification::make()
                                                        ->title(trans('admin/egg.import.no_image'))
                                                        ->warning()
                                                        ->send();
                                                }
                                            }),
                                        Action::make('delete_image')
                                            ->visible(fn ($record) => $record->image)
                                            ->hiddenLabel()
                                            ->icon('tabler-trash')
                                            ->iconButton()
                                            ->iconSize(IconSize::Large)
                                            ->color('danger')
                                            ->action(function ($record) {
                                                foreach (array_keys(Egg::IMAGE_FORMATS) as $ext) {
                                                    $path = Egg::ICON_STORAGE_PATH . "/$record->uuid.$ext";
                                                    if (Storage::disk('public')->exists($path)) {
                                                        Storage::disk('public')->delete($path);
                                                    }
                                                }

                                                Notification::make()
                                                    ->title(trans('admin/egg.import.image_deleted'))
                                                    ->success()
                                                    ->send();

                                                $record->refresh();
                                            }),
                                    ]),
                                ]),
                            TextInput::make('name')
                                ->label(trans('admin/egg.name'))
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(['default' => 2, 'sm' => 2, 'md' => 3, 'lg' => 2])
                                ->helperText(trans('admin/egg.name_help')),
                            Textarea::make('description')
                                ->label(trans('admin/egg.description'))
                                ->rows(3)
                                ->columnSpan(['default' => 2, 'sm' => 2, 'md' => 4, 'lg' => 3])
                                ->helperText(trans('admin/egg.description_help')),
                            TextInput::make('id')
                                ->label(trans('admin/egg.egg_id'))
                                ->columnSpan(1)
                                ->disabled(),
                            TextInput::make('uuid')
                                ->label(trans('admin/egg.egg_uuid'))
                                ->disabled()
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 2])
                                ->helperText(trans('admin/egg.uuid_help')),
                            TextInput::make('author')
                                ->label(trans('admin/egg.author'))
                                ->required()
                                ->maxLength(255)
                                ->email()
                                ->disabled()
                                ->columnSpan(['default' => 1, 'sm' => 1, 'md' => 1, 'lg' => 2])
                                ->helperText(trans('admin/egg.author_help_edit')),
                            Toggle::make('force_outgoing_ip')
                                ->inline(false)
                                ->label(trans('admin/egg.force_ip'))
                                ->columnSpan(1)
                                ->hintIcon('tabler-question-mark', trans('admin/egg.force_ip_help')),
                            KeyValue::make('startup_commands')
                                ->label(trans('admin/egg.startup_commands'))
                                ->live()
                                ->columnSpanFull()
                                ->required()
                                ->addActionLabel(trans('admin/egg.add_startup'))
                                ->keyLabel(trans('admin/egg.startup_name'))
                                ->valueLabel(trans('admin/egg.startup_command'))
                                ->helperText(trans('admin/egg.startup_help')),
                            TagsInput::make('file_denylist')
                                ->label(trans('admin/egg.file_denylist'))
                                ->placeholder('denied-file.txt')
                                ->helperText(trans('admin/egg.file_denylist_help'))
                                ->columnSpan(['default' => 2, 'sm' => 2, 'md' => 2, 'lg' => 3]),
                            TextInput::make('update_url')
                                ->label(trans('admin/egg.update_url'))
                                ->url()
                                ->hintIcon('tabler-question-mark', trans('admin/egg.update_url_help'))
                                ->columnSpan(['default' => 2, 'sm' => 2, 'md' => 2, 'lg' => 3]),
                            TagsInput::make('features')
                                ->label(trans('admin/egg.features'))
                                ->columnSpan(['default' => 2, 'sm' => 2, 'md' => 2, 'lg' => 3]),
                            Hidden::make('script_is_privileged')
                                ->helperText('The docker images available to servers using this egg.'),
                            TagsInput::make('tags')
                                ->label(trans('admin/egg.tags'))
                                ->columnSpan(['default' => 2, 'sm' => 2, 'md' => 2, 'lg' => 3]),
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
                    Tab::make('process_management')
                        ->label(trans('admin/egg.tabs.process_management'))
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
                    Tab::make('egg_variables')
                        ->label(trans('admin/egg.tabs.egg_variables'))
                        ->columnSpanFull()
                        ->icon('tabler-variable')
                        ->schema([
                            Repeater::make('variables')
                                ->hiddenLabel()
                                ->grid()
                                ->relationship('variables')
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
                                        ->unique(modifyRuleUsing: fn (Unique $rule, Get $get) => $rule->where('egg_id', $get('../../id')))
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
                                        ->hintIcon('tabler-code', fn ($state) => "{{{$state}}}")
                                        ->unique(modifyRuleUsing: fn (Unique $rule, Get $get) => $rule->where('egg_id', $get('../../id')))
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
                    Tab::make('install_script')
                        ->label(trans('admin/egg.tabs.install_script'))
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
                                ->selectablePlaceholder(false)
                                ->options([
                                    'bash' => 'bash',
                                    'ash' => 'ash',
                                    '/bin/bash' => '/bin/bash',
                                ])
                                ->required(),
                            MonacoEditor::make('script_install')
                                ->hiddenLabel()
                                ->language(EditorLanguages::shell)
                                ->columnSpanFull(),
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
                ->label(fn (Egg $egg): string => $egg->servers()->count() <= 0 ? trans('filament-actions::delete.single.label') : trans('admin/egg.in_use'))
                ->successNotification(fn (Egg $egg) => Notification::make()
                    ->success()
                    ->title(trans('admin/egg.delete_success'))
                    ->body(trans('admin/egg.deleted', ['egg' => $egg->name]))
                )
                ->failureNotification(fn (Egg $egg) => Notification::make()
                    ->danger()
                    ->title(trans('admin/egg.delete_failed'))
                    ->body(trans('admin/egg.could_not_delete', ['egg' => $egg->name]))
                )
                ->iconButton()->iconSize(IconSize::ExtraLarge),
            ExportEggAction::make(),
            ImportEggAction::make()
                ->multiple(false),
            $this->getSaveFormAction()->formId('form')
                ->iconButton()->iconSize(IconSize::ExtraLarge)
                ->icon('tabler-device-floppy'),
        ];
    }

    public function refreshForm(): void
    {
        $this->fillForm();
    }

    /**
     * Save an image from URL download to a file.
     *
     * @throws Exception
     */
    private function saveImageFromUrl(string $imageUrl, string $extension, Egg $egg): void
    {
        $context = stream_context_create([
            'http' => ['timeout' => 3],
            'https' => [
                'timeout' => 3,
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        $data = @file_get_contents($imageUrl, false, $context, 0, 1048576); // 1024KB

        if (empty($data)) {
            throw new Exception(trans('admin/egg.import.invalid_url'));
        }

        $normalizedExtension = match ($extension) {
            'svg+xml' => 'svg',
            'jpeg' => 'jpg',
            default => $extension,
        };

        Storage::disk('public')->put(Egg::ICON_STORAGE_PATH . "/$egg->uuid.$normalizedExtension", $data);
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
