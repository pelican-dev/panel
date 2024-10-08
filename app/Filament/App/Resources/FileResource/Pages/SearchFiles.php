<?php

namespace App\Filament\App\Resources\FileResource\Pages;

use App\Filament\App\Resources\FileResource;
use App\Models\File;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\PageRegistration;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Livewire\Attributes\Locked;

class SearchFiles extends ListRecords
{
    protected static string $resource = FileResource::class;

    #[Locked]
    public string $searchTerm;
    #[Locked]
    public string $path;

    public function mount(string $searchTerm = null, string $path = null): void
    {
        parent::mount();
        $this->searchTerm = $searchTerm;
        $this->path = $path ?? '/';
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(function () {
                /** @var Server $server */
                $server = Filament::getTenant();

                return File::get($server, $this->path, $this->searchTerm)->orderByDesc('is_directory')->orderBy('name');
            })
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->icon(fn (File $file) => $file->getIcon()),
                TextColumn::make('size')
                    ->formatStateUsing(fn ($state, File $file) => $file->is_file ? convert_bytes_to_readable($state) : ''),
                TextColumn::make('modified_at')
                    ->dateTime()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state->diffForHumans())
                    ->tooltip(fn (File $file) => $file->modified_at),
            ])
            ->recordUrl(function (File $file) {
                if ($file->is_directory) {
                    return ListFiles::getUrl(['path' => join_paths($this->path, $file->name)]);
                }

                return $file->canEdit() ? EditFiles::getUrl(['path' => join_paths($this->path, $file->name)]) : null;
            });
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
