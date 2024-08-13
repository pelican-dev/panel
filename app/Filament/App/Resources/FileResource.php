<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\FileResource\Pages;
use App\Models\File;
use Filament\Resources\Resource;

class FileResource extends Resource
{
    protected static ?string $model = File::class;
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'tabler-files';

    public static function getPages(): array
    {
        return [
            'edit' => Pages\EditFiles::route('/edit/{path?}'),
            'index' => Pages\ListFiles::route('/{path?}'),
        ];
    }
}
