<?php

namespace App\Filament\App\Resources\FileResource\Pages;

use App\Facades\Activity;
use App\Filament\App\Resources\FileResource;
use App\Models\File;
use App\Models\Server;
use App\Repositories\Daemon\DaemonFileRepository;
use Carbon\Carbon;
use Filament\Actions\Action as HeaderAction;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\PageRegistration;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
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

                return File::get($server, $this->path)->orderByDesc('directory')->orderBy('name');
            })
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->icon(fn (File $file) => $file->getIcon()),
                TextColumn::make('size')
                    ->formatStateUsing(fn ($state, File $file) => $file->file ? convert_bytes_to_readable($state) : ''),
                TextColumn::make('created')
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->diffForHumans()),
                TextColumn::make('modified')
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->diffForHumans()),
            ])
            ->actions([
                Action::make('view')
                    ->label('')
                    ->icon('tabler-eye')
                    ->tooltip('Open')
                    ->visible(fn (File $file) => $file->directory)
                    ->url(fn (File $file) => self::getUrl(['path' => $this->path === '/' ? $file->name : $this->path . '/' . $file->name])),
                EditAction::make()
                    ->label('')
                    ->icon('tabler-edit')
                    ->tooltip('Edit')
                    ->visible(fn (File $file) => $file->canEdit()),
                DeleteAction::make()
                    ->label('')
                    ->icon('tabler-trash')
                    ->tooltip('Delete')
                    ->visible(fn (File $file) => !$file->system)
                    ->requiresConfirmation()
                    ->action(function (File $file) {
                        /** @var Server $server */
                        $server = Filament::getTenant();

                        app(DaemonFileRepository::class)->setServer($server)->deleteFiles($this->path, [$file->name]);

                        Activity::event('server:file.delete')
                            ->property('directory', $this->path)
                            ->property('files', $file->name)
                            ->log();
                    }),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
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
