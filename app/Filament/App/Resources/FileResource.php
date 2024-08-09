<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\FileResource\Pages;
use App\Models\File;
use Filament\Resources\Resource;

class FileResource extends Resource
{
    protected static ?string $model = File::class;

    protected static ?string $navigationIcon = 'tabler-files';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFiles::route('/{path?}'),
        ];
    }
}
