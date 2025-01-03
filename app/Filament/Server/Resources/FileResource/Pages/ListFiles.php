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
use App\Services\Nodes\NodeJWTService;
use App\Filament\Components\Tables\Columns\BytesColumn;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use Carbon\CarbonImmutable;
use Filament\Actions\Action as HeaderAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Panel;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\PageRegistration;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Livewire\Attributes\Locked;

class ListFiles extends ListRecords
{
    protected static string $resource = FileResource::class;

    #[Locked]
    public string $path;

    public function mount(?string $path = null): void
    {
        parent::mount();
        $this->path = $path ?? '/';
    }

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

        return $table
            ->paginated([15, 25, 50, 100])
            ->defaultPaginationPageOption(15)
            ->query(fn () => File::get($server, $this->path)->orderByDesc('is_directory'))
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->icon(fn (File $file) => $file->getIcon()),
                BytesColumn::make('size')
                    ->visibleFrom('md')
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
                    ->label('Edit')
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
                        ->action(function ($data, File $file, DaemonFileRepository $fileRepository) use ($server) {
                            $fileRepository
                                ->setServer($server)
                                ->renameFiles($this->path, [['to' => $data['name'], 'from' => $file->name]]);

                            Activity::event('server:file.rename')
                                ->property('directory', $this->path)
                                ->property('files', [['to' => $data['name'], 'from' => $file->name]])
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
                        ->action(function (File $file, DaemonFileRepository $fileRepository) use ($server) {
                            $fileRepository
                                ->setServer($server)
                                ->copyFile(join_paths($this->path, $file->name));

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
                        ->action(function (File $file, NodeJWTService $service) use ($server) {
                            $token = $service
                                ->setExpiresAt(CarbonImmutable::now()->addMinutes(15))
                                ->setUser(auth()->user())
                                ->setClaims([
                                    'file_path' => rawurldecode(join_paths($this->path, $file->name)),
                                    'server_uuid' => $server->uuid,
                                ])
                                ->handle($server->node, auth()->user()->id . $server->uuid);

                            Activity::event('server:file.download')
                                ->property('file', join_paths($this->path, $file->name))
                                ->log();

                            return redirect()->away(sprintf('%s/download/file?token=%s', $server->node->getConnectionAddress(), $token->toString())); // TODO: download works, but breaks modals
                        }),
                    Action::make('move')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_UPDATE, $server))
                        ->label('Move')
                        ->icon('tabler-replace')
                        ->form([
                            TextInput::make('location')
                                ->label('File name')
                                ->hint('Enter the new name and directory of this file or folder, relative to the current directory.')
                                ->default(fn (File $file) => $file->name)
                                ->required()
                                ->live(),
                            Placeholder::make('new_location')
                                ->content(fn (Get $get) => resolve_path('./' . join_paths($this->path, $get('location')))),
                        ])
                        ->action(function ($data, File $file, DaemonFileRepository $fileRepository) use ($server) {
                            $location = resolve_path(join_paths($this->path, $data['location']));

                            $fileRepository
                                ->setServer($server)
                                ->renameFiles($this->path, [['to' => $location, 'from' => $file->name]]);

                            Activity::event('server:file.rename')
                                ->property('directory', $this->path)
                                ->property('files', [['to' => $location, 'from' => $file->name]])
                                ->log();

                            Notification::make()
                                ->title(join_paths($this->path, $file->name) . ' was moved to ' . $location)
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
                        ->action(function ($data, File $file, DaemonFileRepository $fileRepository) use ($server) {
                            $owner = (in_array('read', $data['owner']) ? 4 : 0) | (in_array('write', $data['owner']) ? 2 : 0) | (in_array('execute', $data['owner']) ? 1 : 0);
                            $group = (in_array('read', $data['group']) ? 4 : 0) | (in_array('write', $data['group']) ? 2 : 0) | (in_array('execute', $data['group']) ? 1 : 0);
                            $public = (in_array('read', $data['public']) ? 4 : 0) | (in_array('write', $data['public']) ? 2 : 0) | (in_array('execute', $data['public']) ? 1 : 0);

                            $mode = $owner . $group . $public;

                            $fileRepository
                                ->setServer($server)
                                ->chmodFiles($this->path, [['file' => $file->name, 'mode' => $mode]]);

                            Notification::make()
                                ->title('Permissions changed to ' . $mode)
                                ->success()
                                ->send();
                        }),
                    Action::make('archive')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_ARCHIVE, $server))
                        ->label('Archive')
                        ->icon('tabler-archive')
                        ->action(function (File $file, DaemonFileRepository $fileRepository) use ($server) {
                            $fileRepository
                                ->setServer($server)
                                ->compressFiles($this->path, [$file->name]);

                            Activity::event('server:file.compress')
                                ->property('directory', $this->path)
                                ->property('files', [$file->name])
                                ->log();

                            Notification::make()
                                ->title('Archive created')
                                ->success()
                                ->send();

                            return redirect(ListFiles::getUrl(['path' => $this->path]));
                        }),
                    Action::make('unarchive')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_ARCHIVE, $server))
                        ->label('Unarchive')
                        ->icon('tabler-archive')
                        ->visible(fn (File $file) => $file->isArchive())
                        ->action(function (File $file, DaemonFileRepository $fileRepository) use ($server) {
                            $fileRepository
                                ->setServer($server)
                                ->decompressFile($this->path, $file->name);

                            Activity::event('server:file.decompress')
                                ->property('directory', $this->path)
                                ->property('files', $file->name)
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
                    ->modalDescription(fn (File $file) => $file->name)
                    ->modalHeading('Delete file?')
                    ->action(function (File $file, DaemonFileRepository $fileRepository) use ($server) {
                        $fileRepository
                            ->setServer($server)
                            ->deleteFiles($this->path, [$file->name]);

                        Activity::event('server:file.delete')
                            ->property('directory', $this->path)
                            ->property('files', $file->name)
                            ->log();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('move')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_UPDATE, $server))
                        ->form([
                            TextInput::make('location')
                                ->label('File name')
                                ->hint('Enter the new name and directory of this file or folder, relative to the current directory.')
                                ->default(fn (File $file) => $file->name)
                                ->required()
                                ->live(),
                            Placeholder::make('new_location')
                                ->content(fn (Get $get) => resolve_path('./' . join_paths($this->path, $get('location') ?? ''))),
                        ])
                        ->action(function (Collection $files, $data, DaemonFileRepository $fileRepository) use ($server) {
                            $location = resolve_path(join_paths($this->path, $data['location']));

                            // @phpstan-ignore-next-line
                            $files = $files->map(fn ($file) => ['to' => $location, 'from' => $file->name])->toArray();
                            $fileRepository
                                ->setServer($server)
                                ->renameFiles($this->path, $files);

                            Activity::event('server:file.rename')
                                ->property('directory', $this->path)
                                ->property('files', $files)
                                ->log();

                            Notification::make()
                                ->title(count($files) . ' Files were moved from to ' . $location)
                                ->success()
                                ->send();
                        }),
                    BulkAction::make('archive')
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_ARCHIVE, $server))
                        ->action(function (Collection $files, DaemonFileRepository $fileRepository) use ($server) {
                            // @phpstan-ignore-next-line
                            $files = $files->map(fn ($file) => $file->name)->toArray();

                            $fileRepository
                                ->setServer($server)
                                ->compressFiles($this->path, $files);

                            Activity::event('server:file.compress')
                                ->property('directory', $this->path)
                                ->property('files', $files)
                                ->log();

                            Notification::make()
                                ->title('Archive created')
                                ->success()
                                ->send();

                            return redirect(ListFiles::getUrl(['path' => $this->path]));
                        }),
                    DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_DELETE, $server))
                        ->action(function (Collection $files, DaemonFileRepository $fileRepository) use ($server) {
                            // @phpstan-ignore-next-line
                            $files = $files->map(fn ($file) => $file->name)->toArray();
                            $fileRepository
                                ->setServer($server)
                                ->deleteFiles($this->path, $files);

                            Activity::event('server:file.delete')
                                ->property('directory', $this->path)
                                ->property('files', $files)
                                ->log();

                            Notification::make()
                                ->title(count($files) . ' Files deleted.')
                                ->success()
                                ->send();
                        }),
                ]),
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
                ->action(function ($data, DaemonFileRepository $fileRepository) use ($server) {
                    $fileRepository
                        ->setServer($server)
                        ->putContent(join_paths($this->path, $data['name']), $data['editor'] ?? '');

                    Activity::event('server:file.write')
                        ->property('file', join_paths($this->path, $data['name']))
                        ->log();
                })
                ->form([
                    TextInput::make('name')
                        ->label('File Name')
                        ->required(),
                    Select::make('lang')
                        ->live()
                        ->hidden() //TODO: Make file language selection work
                        ->label('Language')
                        ->placeholder('File Language')
                        ->options(EditorLanguages::class),
                    MonacoEditor::make('editor')
                        ->label('')
                        ->view('filament.plugins.monaco-editor')
                        ->language(fn (Get $get) => $get('lang')),
                ]),
            HeaderAction::make('new_folder')
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_FILE_CREATE, $server))
                ->label('New Folder')
                ->color('gray')
                ->action(function ($data, DaemonFileRepository $fileRepository) use ($server) {
                    $fileRepository
                        ->setServer($server)
                        ->createDirectory($data['name'], $this->path);

                    Activity::event('server:file.write')
                        ->property('file', join_paths($this->path, $data['name']))
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
                ->action(function ($data, DaemonFileRepository $fileRepository) use ($server) {
                    if (count($data['files']) > 0 && !isset($data['url'])) {
                        /** @var UploadedFile $file */
                        foreach ($data['files'] as $file) {
                            $fileRepository
                                ->setServer($server)
                                ->putContent(join_paths($this->path, $file->getClientOriginalName()), $file->getContent());

                            Activity::event('server:file.uploaded')
                                ->property('directory', $this->path)
                                ->property('file', $file->getFilename())
                                ->log();
                        }
                    } elseif ($data['url'] !== null) {
                        $fileRepository
                            ->setServer($server)
                            ->pull($data['url'], $this->path);

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
                            Tabs\Tab::make('Upload Files')
                                ->live()
                                ->schema([
                                    FileUpload::make('files')
                                        ->label('File(s)')
                                        ->storeFiles(false)
                                        ->previewable(false)
                                        ->preserveFilenames()
                                        ->multiple(),
                                ]),
                            Tabs\Tab::make('Upload From URL')
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
                        ->regex('/^[^*]*\*?[^*]*$/')
                        ->minLength(3),
                ])
                ->action(fn ($data) => redirect(SearchFiles::getUrl([
                    'searchTerm' => $data['searchTerm'],
                    'path' => $this->path,
                ]))),
        ];
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

    private function getPermissionsFromModeBit(int $mode): array
    {
        if ($mode === 1) {
            return ['execute'];
        } elseif ($mode === 2) {
            return ['write'];
        } elseif ($mode === 3) {
            return ['write', 'execute'];
        } elseif ($mode === 4) {
            return ['read'];
        } elseif ($mode === 5) {
            return ['read', 'execute'];
        } elseif ($mode === 6) {
            return ['read', 'write'];
        } elseif ($mode === 7) {
            return ['read', 'write', 'execute'];
        }

        return [];
    }
}
