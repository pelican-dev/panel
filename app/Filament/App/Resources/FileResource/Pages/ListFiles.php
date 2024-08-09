<?php

namespace App\Filament\App\Resources\FileResource\Pages;

use App\Facades\Activity;
use App\Filament\App\Resources\FileResource;
use App\Models\File;
use App\Models\Server;
use App\Repositories\Daemon\DaemonFileRepository;
use Filament\Actions\Action as HeaderAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Textarea;
use Filament\Panel;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\PageRegistration;
use Filament\Tables\Actions\Action;
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
            ->actions([ // TODO: add other actions like copy, rename etc.
                Action::make('view')
                    ->label('')
                    ->icon('tabler-eye')
                    ->tooltip('Open')
                    ->visible(fn (File $file) => $file->is_directory)
                    ->url(fn (File $file) => self::getUrl(['path' => $this->path === '/' ? $file->name : $this->path . '/' . $file->name])),
                EditAction::make()
                    ->label('')
                    ->icon('tabler-edit')
                    ->tooltip('Edit')
                    ->visible(fn (File $file) => $file->canEdit())
                    ->form([
                        Textarea::make('content') // TODO: replace with proper code editor
                            ->rows(20)
                            ->label(fn (File $file) => $file->name)
                            ->formatStateUsing(function (File $file) {
                                /** @var Server $server */
                                $server = Filament::getTenant();

                                return app(DaemonFileRepository::class)
                                    ->setServer($server)
                                    ->getContent($this->path . $file->name, config('panel.files.max_edit_size'));
                            }),
                    ])
                    ->action(function ($data, File $file) {
                        /** @var Server $server */
                        $server = Filament::getTenant();

                        app(DaemonFileRepository::class)
                            ->setServer($server)
                            ->putContent($this->path . $file->name, $data['content'] ?? '');

                        Activity::event('server:file.write')
                            ->property('file', $this->path . $file->name)
                            ->log();
                    }),
                DeleteAction::make()
                    ->label('')
                    ->icon('tabler-trash')
                    ->tooltip('Delete')
                    ->requiresConfirmation()
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
        return [ // TODO: add other header actions, like "create file", "create folder" etc.
            HeaderAction::make('back')
                ->hidden(fn () => $this->path === '/')
                ->url(fn () => self::getUrl(['path' => dirname($this->path)])),
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
}
