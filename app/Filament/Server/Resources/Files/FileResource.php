<?php

namespace App\Filament\Server\Resources\Files;

use App\Filament\Server\Resources\Files\Pages\DownloadFiles;
use App\Filament\Server\Resources\Files\Pages\EditFiles;
use App\Filament\Server\Resources\Files\Pages\ListFiles;
use App\Filament\Server\Resources\Files\Pages\SearchFiles;
use App\Models\File;
use App\Traits\Filament\BlockAccessInConflict;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;

class FileResource extends Resource
{
    use BlockAccessInConflict;
    use CanCustomizePages;
    use CanCustomizeRelations;

    protected static ?string $model = File::class;

    protected static ?int $navigationSort = 2;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-files';

    protected static bool $isScopedToTenant = false;

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'edit' => EditFiles::route('/edit/{path}'),
            'search' => SearchFiles::route('/search/{searchTerm}'), // TODO: find better way?
            'download' => DownloadFiles::route('/download/{path}'),
            'index' => ListFiles::route('/{path?}'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return trans('server/file.title');
    }
}
