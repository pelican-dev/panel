<?php

namespace App\Filament\App\Resources\FileResource\Pages;

use AbdelhamidErrahmouni\FilamentMonacoEditor\MonacoEditor;
use App\Facades\Activity;
use App\Filament\App\Resources\FileResource;
use App\Models\File;
use App\Models\Server;
use App\Repositories\Daemon\DaemonFileRepository;
use Filament\Actions\Action as HeaderAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Panel;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\PageRegistration;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Livewire\Attributes\Locked;

class ListFiles extends ListRecords
{
    protected static string $resource = FileResource::class;

    #[Locked]
    public string $path;

    public function mount(string $path = null): void
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
        return $table
            ->paginated(false)
            ->query(function () {
                /** @var Server $server */
                $server = Filament::getTenant();

                return File::get($server, $this->path)->orderByDesc('is_directory')->orderBy('name');
            })
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->icon(fn (File $file) => $file->getIcon()),
                TextColumn::make('size')
                    ->formatStateUsing(fn ($state, File $file) => $file->is_file ? convert_bytes_to_readable($state) : ''),
                TextColumn::make('modified_at')
                    ->formatStateUsing(fn ($state) => $state->diffForHumans()),
            ])
            ->actions([
                Action::make('view')
                    ->label('Open')
                    ->icon('tabler-eye')
                    ->visible(fn (File $file) => $file->is_directory)
                    ->url(fn (File $file) => self::getUrl(['path' => join_paths($this->path, $file->name)])),
                EditAction::make()
                    ->label('Edit')
                    ->icon('tabler-edit')
                    ->visible(fn (File $file) => $file->canEdit())
                    ->modalHeading(fn (File $file) => 'Editing ' . $file->name)
//                    ->keyBindings(['command+s', 'ctrl+s']) TODO: Make this work...
                    ->form([
                        MonacoEditor::make('editor')
                            ->view('filament.plugins.monaco-editor')
                            ->label('')
                            ->formatStateUsing(function (File $file) {
                                /** @var Server $server */
                                $server = Filament::getTenant();

                                return app(DaemonFileRepository::class)
                                    ->setServer($server)
                                    ->getContent(join_paths($this->path, $file->name), config('panel.files.max_edit_size'));
                            }),
                    ])
                    ->action(function ($data, File $file) {
                        /** @var Server $server */
                        $server = Filament::getTenant();

                        app(DaemonFileRepository::class)
                            ->setServer($server)
                            ->putContent(join_paths($this->path, $file->name), $data['editor'] ?? '');

                        Activity::event('server:file.write')
                            ->property('file', join_paths($this->path, $file->name))
                            ->log();
                    }),
                ActionGroup::make([
                    Action::make('rename')
                        ->label('Rename')
                        ->icon('tabler-forms')
                        ->form([
                            TextInput::make('name')
                                ->label('File name')
                                ->default(fn (File $file) => $file->name)
                                ->required(),
                        ])
                        ->action(function ($data, File $file) {
                            /** @var Server $server */
                            $server = Filament::getTenant();

                            app(DaemonFileRepository::class)
                                ->setServer($server)
                                ->renameFiles($this->path, [['to' => $data['name'], 'from' => $file->name]]);

                            Activity::event('server:file.rename')
                                ->property('directory', $this->path)
                                ->property('files', [['to' => $data['name'], 'from' => $file->name]])
                                ->log();

                            Notification::make()
                                ->title($file->name . ' was renamed to ' . $data['name'])
                                ->success()
                                ->send();
                        }),
                    Action::make('copy')
                        ->label('Copy')
                        ->icon('tabler-copy')
                        ->visible(fn (File $file) => $file->is_file)
                        ->action(function (File $file) {
                            /** @var Server $server */
                            $server = Filament::getTenant();

                            app(DaemonFileRepository::class)
                                ->setServer($server)
                                ->copyFile(join_paths($this->path, $file->name));

                            Activity::event('server:file.copy')
                                ->property('file', join_paths($this->path, $file->name))
                                ->log();

                            Notification::make()
                                ->title('File copied')
                                ->success()
                                ->send();
                        }),
                    Action::make('download')
                        ->label('Download')
                        ->icon('tabler-download')
                        ->visible(fn (File $file) => $file->is_file)
                        ->action(function (File $file) {
                            // TODO
                        }),
                    Action::make('move')
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
                        ->action(function ($data, File $file) {
                            /** @var Server $server */
                            $server = Filament::getTenant();

                            $location = resolve_path(join_paths($this->path, $data('location')));

                            app(DaemonFileRepository::class)
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

                            /** @var Server $server */
                            $server = Filament::getTenant();

                            app(DaemonFileRepository::class)
                                ->setServer($server)
                                ->chmodFiles($this->path, [['file' => $file->name, 'mode' => $mode]]);

                            Notification::make()
                                ->title('Permissions changed to ' . $mode)
                                ->success()
                                ->send();
                        }),
                    Action::make('archive')
                        ->label('Archive')
                        ->icon('tabler-archive')
                        ->action(function (File $file) {
                            // TODO
                        }),
                ]),
                DeleteAction::make()
                    ->label('')
                    ->icon('tabler-trash')
                    ->requiresConfirmation()
                    ->modalDescription(fn (File $file) => $file->name)
                    ->modalHeading('Delete {$files}?')
                    ->action(function (File $file) {
                        /** @var Server $server */
                        $server = Filament::getTenant();

                        app(DaemonFileRepository::class)
                            ->setServer($server)
                            ->deleteFiles($this->path, [$file->name]);

                        Activity::event('server:file.delete')
                            ->property('directory', $this->path)
                            ->property('files', $file->name)
                            ->log();
                    }),
            ])
            ->bulkActions([
                // TODO: add more bulk actions
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($files) {
                            $files = $files->map(fn ($file) => $file->name)->toArray();

                            /** @var Server $server */
                            $server = Filament::getTenant();

                            app(DaemonFileRepository::class)
                                ->setServer($server)
                                ->deleteFiles($this->path, $files);

                            Activity::event('server:file.delete')
                                ->property('directory', $this->path)
                                ->property('files', $files)
                                ->log();
                        }),
                ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [ // TODO: add other header actions, upload.
            HeaderAction::make('back')
                ->hidden(fn () => $this->path === '/')
                ->url(fn () => self::getUrl(['path' => dirname($this->path)])),
            HeaderAction::make('new_file')
                ->label('New File')
                ->action(function ($data) {
                    /** @var Server $server */
                    $server = Filament::getTenant();

                    app(DaemonFileRepository::class)
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
                    Select::make('test') //TODO: Add  supported Langs
                        ->label('')
                        ->placeholder('File Language')
                        ->options([ //Placeholders
                            'html' => 'html',
                            'php' => 'php',
                            'js' => 'js',
                            'css' => 'css',
                            'sql' => 'sql',
                            'csv' => 'csv',

                        ]),
                    MonacoEditor::make('editor')
                        ->label('')
                        ->view('filament.plugins.monaco-editor')
                        ->language()
                        ->required(),
                ]),
            HeaderAction::make('new_folder')
                ->label('New Folder')
                ->action(function ($data) {
                    /** @var Server $server */
                    $server = Filament::getTenant();

                    app(DaemonFileRepository::class)
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
