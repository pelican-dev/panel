<?php

namespace App\Filament\Server\Resources\FileResource\Pages;

use App\Filament\Server\Resources\FileResource;
use App\Models\File;
use App\Models\Server;
use App\Filament\Components\Tables\Columns\BytesColumn;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;

class SearchFiles extends ListRecords
{
    protected static string $resource = FileResource::class;

    protected static ?string $title = 'Global Search';

    #[Locked]
    public string $searchTerm;

    #[Url]
    public string $path = '/';

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();

        return [
            $resource::getUrl() => $resource::getBreadcrumb(),
            self::getUrl(['searchTerm' => $this->searchTerm]) => 'Search "' . $this->searchTerm . '"',
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
                    ->searchable()
                    ->icon(fn (File $file) => $file->getIcon()),
                BytesColumn::make('size'),
                DateTimeColumn::make('modified_at')
                    ->since()
                    ->sortable(),
            ])
            ->recordUrl(function (File $file) {
                if ($file->is_directory) {
                    return ListFiles::getUrl(['path' => join_paths($this->path, $file->name)]);
                }

                return $file->canEdit() ? EditFiles::getUrl(['path' => join_paths($this->path, $file->name)]) : null;
            });
    }
}
