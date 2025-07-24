<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\FileResource\Pages;
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

    protected static ?string $navigationIcon = 'tabler-files';

    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::ACTION_FILE_READ, Filament::getTenant());
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::ACTION_FILE_CREATE, Filament::getTenant());
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_FILE_UPDATE, Filament::getTenant());
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_FILE_DELETE, Filament::getTenant());
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'edit' => Pages\EditFiles::route('/edit/{path}'),
            'search' => Pages\SearchFiles::route('/search/{searchTerm}'), // TODO: find better way?
            'download' => Pages\DownloadFiles::route('/download/{path}'),
            'index' => Pages\ListFiles::route('/{path?}'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return trans('server/file.title');
    }
}
