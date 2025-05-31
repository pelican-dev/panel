<?php

namespace App\Filament\Server\Resources\FileResource\Pages;

use AbdelhamidErrahmouni\FilamentMonacoEditor\MonacoEditor;
use App\Enums\EditorLanguages;
use App\Facades\Activity;
use App\Filament\Server\Resources\FileResource;
use App\Models\File;
use App\Models\Permission;
use App\Models\Server;
use App\Repositories\Daemon\DaemonFileRepository;
use App\Filament\Components\Tables\Columns\BytesColumn;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use Filament\Actions\Action as HeaderAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Panel;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\PageRegistration;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Route;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route as RouteFacade;
use Livewire\Attributes\Locked;

class ListFiles extends ListRecords
{
    protected static string $resource = FileResource::class;

    #[Locked]
    public string $path = '/';

    private DaemonFileRepository $fileRepository;

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();

        $breadcrumbs = [
            $resource::getUrl() => $resource::getBreadcrumb(),
        ];

        $previousParts = '';
        foreach (explode('/', $this->path) as $part) {
            $previousParts = $previousParts . '/' . $part;
            $breadcrumbs[self::getUrl(['path' => ltrim($previousParts, '/')])] = $part;
        }

        return $breadcrumbs;
    }

    public function table(Table $table): Table
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $files = File::get($server, $this->path);

        return $table
            ->paginated([25, 50])
            ->defaultPaginationPageOption(25)
            ->query(fn () => $files->orderByDesc('is_directory'))
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->icon(fn (File $file) => $file->getIcon()),
                BytesColumn::make('size')
                    ->visibleFrom('md')
                    ->state(fn (File $file) => $file->is_directory ? null : $file->size)
                    ->sortable(),
                DateTimeColumn::make('modified_at')
                    ->visibleFrom('md')
                    ->since()
                    ->sortable(),
            ])
            ->recordUrl(function (File $file) use ($server) {
                if ($file->is_directory) {
                    return self::getUrl(['path' => join_paths($this->path, $file->name)]);
                }

                if (!auth()->user()->can(Permission::ACTION_FILE_READ_CONTENT, $server)) {
                    return null;
                }

                return $file->canEdit() ? EditFiles::getUrl(['path' => join_paths($this->path, $file->name)]) : null;
            })
            ->actions([
                Action::make('view')
                    ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_READ, $server))
                    ->label('Open')
                    ->icon('tabler-eye')
                    ->visible(fn (File $file) => $file->is_directory)
                    ->url(fn (File $file) => self::getUrl(['path' => join_paths($this->path, $file->name)])),
                EditAction::make('edit')
                    ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_READ_CONTENT, $server))
                    ->icon('tabler-edit')
                    ->visible(fn (File $file) => $file->canEdit())
                    ->url(fn (File $file) => EditFiles::getUrl(['path' => join_paths($this->path, $file->name)])),
                ActionGroup::make([
                    Action::make('rename')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_UPDATE, $server))
                        ->label('Rename')
                        ->icon('tabler-forms')
                        ->form([
                            TextInput::make('name')
                                ->label('File name')
                                ->default(fn (File $file) => $file->name)
                                ->required(),
                        ])
                        ->action(function ($data, File $file) {
                            $files = [['to' => $data['name'], 'from' => $file->name]];

                            $this->getDaemonFileRepository()->renameFiles($this->path, $files);

                            Activity::event('server:file.rename')
                                ->property('directory', $this->path)
                                ->property('files', $files)
                                ->property('to', $data['name'])
                                ->property('from', $file->name)
                                ->log();

                            Notification::make()
                                ->title('File Renamed')
                                ->body(fn () => $file->name . ' -> ' . $data['name'])
                                ->success()
                                ->send();
                        }),
                    Action::make('copy')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_CREATE, $server))
                        ->label('Copy')
                        ->icon('tabler-copy')
                        ->visible(fn (File $file) => $file->is_file)
                        ->action(function (File $file) {
                            $this->getDaemonFileRepository()->copyFile(join_paths($this->path, $file->name));

                            Activity::event('server:file.copy')
                                ->property('file', join_paths($this->path, $file->name))
                                ->log();

                            Notification::make()
                                ->title('File copied')
                                ->success()
                                ->send();

                            return redirect(ListFiles::getUrl(['path' => $this->path]));
                        }),
                    Action::make('download')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_READ_CONTENT, $server))
                        ->label('Download')
                        ->icon('tabler-download')
                        ->visible(fn (File $file) => $file->is_file)
                        ->url(fn (File $file) => DownloadFiles::getUrl(['path' => join_paths($this->path, $file->name)]), true),
                    Action::make('move')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_UPDATE, $server))
                        ->label('Move')
                        ->icon('tabler-replace')
                        ->form([
                            TextInput::make('location')
                                ->label('New location')
                                ->hint('Enter the location of this file or folder, relative to the current directory.')
                                ->required()
                                ->live(),
                            Placeholder::make('new_location')
                                ->content(fn (Get $get, File $file) => resolve_path('./' . join_paths($this->path, $get('location') ?? '/', $file->name))),
                        ])
                        ->action(function ($data, File $file) {
                            $location = rtrim($data['location'], '/');
                            $files = [['to' => join_paths($location, $file->name), 'from' => $file->name]];

                            $this->getDaemonFileRepository()->renameFiles($this->path, $files);

                            $oldLocation = join_paths($this->path, $file->name);
                            $newLocation = resolve_path(join_paths($this->path, $location, $file->name));

                            Activity::event('server:file.rename')
                                ->property('directory', $this->path)
                                ->property('files', $files)
                                ->property('to', $newLocation)
                                ->property('from', $oldLocation)
                                ->log();

                            Notification::make()
                                ->title('File Moved')
                                ->body($oldLocation . ' -> ' . $newLocation)
                                ->success()
                                ->send();
                        }),
                    Action::make('permissions')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_UPDATE, $server))
                        ->label('Permissions')
                        ->icon('tabler-license')
                        ->form([
                            CheckboxList::make('owner')
                                ->bulkToggleable()
                                ->options([
                                    'read' => 'Read',
                                    'write' => 'Write',
                                    'execute' => 'Execute',
                                ])
                                ->formatStateUsing(function ($state, File $file) {
                                    $mode = (int) substr((string) $file->mode_bits, 0, 1);

                                    return $this->getPermissionsFromModeBit($mode);
                                }),
                            CheckboxList::make('group')
                                ->bulkToggleable()
                                ->options([
                                    'read' => 'Read',
                                    'write' => 'Write',
                                    'execute' => 'Execute',
                                ])
                                ->formatStateUsing(function ($state, File $file) {
                                    $mode = (int) substr((string) $file->mode_bits, 1, 1);

                                    return $this->getPermissionsFromModeBit($mode);
                                }),
                            CheckboxList::make('public')
                                ->bulkToggleable()
                                ->options([
                                    'read' => 'Read',
                                    'write' => 'Write',
                                    'execute' => 'Execute',
                                ])
                                ->formatStateUsing(function ($state, File $file) {
                                    $mode = (int) substr((string) $file->mode_bits, 2, 1);

                                    return $this->getPermissionsFromModeBit($mode);
                                }),
                        ])
                        ->action(function ($data, File $file) {
                            $owner = (in_array('read', $data['owner']) ? 4 : 0) | (in_array('write', $data['owner']) ? 2 : 0) | (in_array('execute', $data['owner']) ? 1 : 0);
                            $group = (in_array('read', $data['group']) ? 4 : 0) | (in_array('write', $data['group']) ? 2 : 0) | (in_array('execute', $data['group']) ? 1 : 0);
                            $public = (in_array('read', $data['public']) ? 4 : 0) | (in_array('write', $data['public']) ? 2 : 0) | (in_array('execute', $data['public']) ? 1 : 0);

                            $mode = $owner . $group . $public;

                            $this->getDaemonFileRepository()->chmodFiles($this->path, [['file' => $file->name, 'mode' => $mode]]);

                            Notification::make()
                                ->title('Permissions changed to ' . $mode)
                                ->success()
                                ->send();
                        }),
                    Action::make('archive')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_ARCHIVE, $server))
                        ->label('Archive')
                        ->icon('tabler-archive')
                        ->form([
                            TextInput::make('name')
                                ->label('Archive name')
                                ->placeholder(fn () => 'archive-' . str(Carbon::now()->toRfc3339String())->replace(':', '')->before('+0000') . 'Z')
                                ->suffix('.tar.gz'),
                        ])
                        ->action(function ($data, File $file) {
                            $archive = $this->getDaemonFileRepository()->compressFiles($this->path, [$file->name], $data['name']);

                            Activity::event('server:file.compress')
                                ->property('name', $archive['name'])
                                ->property('directory', $this->path)
                                ->property('files', [$file->name])
                                ->log();

                            Notification::make()
                                ->title('Archive created')
                                ->body($archive['name'])
                                ->success()
                                ->send();

                            return redirect(ListFiles::getUrl(['path' => $this->path]));
                        }),
                    Action::make('unarchive')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_ARCHIVE, $server))
                        ->label('Unarchive')
                        ->icon('tabler-archive')
                        ->visible(fn (File $file) => $file->isArchive())
                        ->action(function (File $file) {
                            $this->getDaemonFileRepository()->decompressFile($this->path, $file->name);

                            Activity::event('server:file.decompress')
                                ->property('directory', $this->path)
                                ->property('file', $file->name)
                                ->log();

                            Notification::make()
                                ->title('Unarchive completed')
                                ->success()
                                ->send();

                            return redirect(ListFiles::getUrl(['path' => $this->path]));
                        }),
                ]),
                DeleteAction::make()
                    ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_DELETE, $server))
                    ->label('')
                    ->icon('tabler-trash')
                    ->requiresConfirmation()
                    ->modalHeading(fn (File $file) => trans('filament-actions::delete.single.modal.heading', ['label' => $file->name . ' ' . ($file->is_directory ? 'folder' : 'file')]))
                    ->action(function (File $file) {
                        $this->deselectAllTableRecords();
                        $this->getDaemonFileRepository()->deleteFiles($this->path, [$file->name]);

                        Activity::event('server:file.delete')
                            ->property('directory', $this->path)
                            ->property('files', $file->name)
                            ->log();
                    }),
            ])
            ->groupedBulkActions([
                BulkAction::make('move')
                    ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_UPDATE, $server))
                    ->form([
                        TextInput::make('location')
                            ->label('Directory')
                            ->hint('Enter the new directory, relative to the current directory.')
                            ->required()
                            ->live(),
                        Placeholder::make('new_location')
                            ->content(fn (Get $get) => resolve_path('./' . join_paths($this->path, $get('location') ?? ''))),
                    ])
                    ->action(function (Collection $files, $data) {
                        $location = rtrim($data['location'], '/');

                        $files = $files->map(fn ($file) => ['to' => join_paths($location, $file['name']), 'from' => $file['name']])->toArray();
                        $this->getDaemonFileRepository()->renameFiles($this->path, $files);

                        Activity::event('server:file.rename')
                            ->property('directory', $this->path)
                            ->property('files', $files)
                            ->log();

                        Notification::make()
                            ->title(count($files) . ' Files were moved to ' . resolve_path(join_paths($this->path, $location)))
                            ->success()
                            ->send();
                    }),
                BulkAction::make('archive')
                    ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_ARCHIVE, $server))
                    ->form([
                        TextInput::make('name')
                            ->label('Archive name')
                            ->placeholder(fn () => 'archive-' . str(Carbon::now()->toRfc3339String())->replace(':', '')->before('+0000') . 'Z')
                            ->suffix('.tar.gz'),
                    ])
                    ->action(function ($data, Collection $files) {
                        $files = $files->map(fn ($file) => $file['name'])->toArray();

                        $archive = $this->getDaemonFileRepository()->compressFiles($this->path, $files, $data['name']);

                        Activity::event('server:file.compress')
                            ->property('name', $archive['name'])
                            ->property('directory', $this->path)
                            ->property('files', $files)
                            ->log();

                        Notification::make()
                            ->title('Archive created')
                            ->body($archive['name'])
                            ->success()
                            ->send();

                        return redirect(ListFiles::getUrl(['path' => $this->path]));
                    }),
                DeleteBulkAction::make()
                    ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_DELETE, $server))
                    ->action(function (Collection $files) {
                        $files = $files->map(fn ($file) => $file['name'])->toArray();
                        $this->getDaemonFileRepository()->deleteFiles($this->path, $files);

                        Activity::event('server:file.delete')
                            ->property('directory', $this->path)
                            ->property('files', $files)
                            ->log();

                        Notification::make()
                            ->title(count($files) . ' Files deleted.')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    protected function getHeaderActions(): array
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return [
            HeaderAction::make('new_file')
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_CREATE, $server))
                ->label('New File')
                ->color('gray')
                ->keyBindings('')
                ->modalSubmitActionLabel('Create')
                ->action(function ($data) {
                    $this->getDaemonFileRepository()->putContent(join_paths($this->path, $data['name']), $data['editor'] ?? '');

                    Activity::event('server:file.write')
                        ->property('file', join_paths($this->path, $data['name']))
                        ->log();
                })
                ->form([
                    TextInput::make('name')
                        ->label('File Name')
                        ->required(),
                    Select::make('lang')
                        ->label('Syntax Highlighting')
                        ->searchable()
                        ->native(false)
                        ->live()
                        ->options(EditorLanguages::class)
                        ->selectablePlaceholder(false)
                        ->afterStateUpdated(fn ($state) => $this->dispatch('setLanguage', lang: $state))
                        ->default(EditorLanguages::plaintext->value),
                    MonacoEditor::make('editor')
                        ->label('')
                        ->view('filament.plugins.monaco-editor')
                        ->language(fn (Get $get) => $get('lang') ?? 'plaintext'),
                ]),
            HeaderAction::make('new_folder')
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_CREATE, $server))
                ->label('New Folder')
                ->color('gray')
                ->action(function ($data) {
                    $this->getDaemonFileRepository()->createDirectory($data['name'], $this->path);

                    Activity::event('server:file.create-directory')
                        ->property(['directory' => $this->path, 'name' => $data['name']])
                        ->log();
                })
                ->form([
                    TextInput::make('name')
                        ->label('Folder Name')
                        ->required(),
                ]),
            HeaderAction::make('upload')
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_CREATE, $server))
                ->label('Upload')
                ->action(function ($data) {
                    if (count($data['files']) > 0 && !isset($data['url'])) {
                        /** @var UploadedFile $file */
                        foreach ($data['files'] as $file) {
                            $this->getDaemonFileRepository()->putContent(join_paths($this->path, $file->getClientOriginalName()), $file->getContent());

                            Activity::event('server:file.uploaded')
                                ->property('directory', $this->path)
                                ->property('file', $file->getClientOriginalName())
                                ->log();
                        }
                    } elseif ($data['url'] !== null) {
                        $this->getDaemonFileRepository()->pull($data['url'], $this->path);

                        Activity::event('server:file.pull')
                            ->property('url', $data['url'])
                            ->property('directory', $this->path)
                            ->log();
                    }

                    return redirect(ListFiles::getUrl(['path' => $this->path]));
                })
                ->form([
                    Tabs::make()
                        ->contained(false)
                        ->schema([
                            Tab::make('Upload Files')
                                ->live()
                                ->schema([
                                    FileUpload::make('files')
                                        ->storeFiles(false)
                                        ->previewable(false)
                                        ->preserveFilenames()
                                        ->maxSize((int) round($server->node->upload_size * (config('panel.use_binary_prefix') ? 1.048576 * 1024 : 1000)))
                                        ->multiple(),
                                ]),
                            Tab::make('Upload From URL')
                                ->live()
                                ->disabled(fn (Get $get) => count($get('files')) > 0)
                                ->schema([
                                    TextInput::make('url')
                                        ->label('URL')
                                        ->url(),
                                ]),
                        ]),
                ]),
            HeaderAction::make('search')
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_READ, $server))
                ->label('Global Search')
                ->modalSubmitActionLabel('Search')
                ->form([
                    TextInput::make('searchTerm')
                        ->placeholder('Enter a search term, e.g. *.txt')
                        ->required()
                        ->regex('/^[^*]*\*?[^*]*$/')
                        ->minValue(3),
                ])
                ->action(fn ($data) => redirect(SearchFiles::getUrl([
                    'searchTerm' => $data['searchTerm'],
                    'path' => $this->path,
                ]))),
        ];
    }

    /**
     * @return string[]
     */
    private function getPermissionsFromModeBit(int $mode): array
    {
        return match ($mode) {
            1 => ['execute'],
            2 => ['write'],
            3 => ['write', 'execute'],
            4 => ['read'],
            5 => ['read', 'execute'],
            6 => ['read', 'write'],
            7 => ['read', 'write', 'execute'],
            default => [],
        };
    }

    private function getDaemonFileRepository(): DaemonFileRepository
    {
        /** @var Server $server */
        $server = Filament::getTenant();
        $this->fileRepository ??= (new DaemonFileRepository())->setServer($server);

        return $this->fileRepository;
    }

    public static function route(string $path): PageRegistration
    {
        return new PageRegistration(
            page: static::class,
            route: fn (Panel $panel): Route => RouteFacade::get($path, static::class)
                ->middleware(static::getRouteMiddleware($panel))
                ->withoutMiddleware(static::getWithoutRouteMiddleware($panel))
                ->where('path', '.*'),
        );
    }
}
