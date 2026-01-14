<?php

namespace App\Filament\Server\Resources\Files\Pages;

use App\Filament\Components\Tables\Columns\BytesColumn;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Filament\Server\Resources\Files\FileResource;
use App\Models\File;
use App\Models\Server;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;

class SearchFiles extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = FileResource::class;

    #[Locked]
    public string $searchTerm;

    #[Url]
    public string $path = '/';

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();

        return [
            $resource::getUrl() => $resource::getBreadcrumb(),
            self::getUrl(['searchTerm' => $this->searchTerm]) => trans('server/file.actions.nested_search.search_for_term', ['term' => ' "' . $this->searchTerm . '"']),
        ];
    }

    public function table(Table $table): Table
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $table
            ->paginated(false)
            ->query(fn () => File::get($server, $this->path, $this->searchTerm)->orderByDesc('is_directory')->orderBy('name'))
            ->columns([
                TextColumn::make('name')
                    ->label(trans('server/file.name'))
                    ->searchable()
                    ->sortable()
                    ->icon(fn (File $file) => $file->getIcon()),
                BytesColumn::make('size')
                    ->label(trans('server/file.size'))
                    ->visibleFrom('md')
                    ->state(fn (File $file) => $file->size)
                    ->sortable(),
                DateTimeColumn::make('modified_at')
                    ->label(trans('server/file.modified_at'))
                    ->visibleFrom('md')
                    ->since()
                    ->sortable(),
            ])
            ->recordUrl(function (File $file) {
                if ($file->is_directory) {
                    return ListFiles::getUrl(['path' => $file->name]);
                }

                return $file->canEdit() ? EditFiles::getUrl(['path' => $file->name]) : null;
            });
    }

    public function getTitle(): string|Htmlable
    {
        return trans('server/file.actions.nested_search.title');
    }
}
