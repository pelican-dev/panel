<?php

namespace App\Filament\Server\Resources\Files;

use App\Filament\Server\Resources\Files\Pages\DownloadFiles;
use App\Filament\Server\Resources\Files\Pages\EditFiles;
use App\Filament\Server\Resources\Files\Pages\ListFiles;
use App\Filament\Server\Resources\Files\Pages\SearchFiles;
use App\Models\File;
use App\Models\Permission;
use App\Traits\Filament\BlockAccessInConflict;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use Filament\Facades\Filament;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

class FileResource extends Resource
{
    use BlockAccessInConflict;
    use CanCustomizePages;
    use CanCustomizeRelations;

    protected static ?string $model = File::class;

    protected static ?int $navigationSort = 2;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-files';

    protected static bool $isScopedToTenant = false;

    public static function canViewAny(): bool
    {
        return user()?->can(Permission::ACTION_FILE_READ, Filament::getTenant());
    }

    public static function canCreate(): bool
    {
        return user()?->can(Permission::ACTION_FILE_CREATE, Filament::getTenant());
    }

    public static function canEdit(Model $record): bool
    {
        return user()?->can(Permission::ACTION_FILE_UPDATE, Filament::getTenant());
    }

    public static function canDelete(Model $record): bool
    {
        return user()?->can(Permission::ACTION_FILE_DELETE, Filament::getTenant());
    }

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
