<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MountResource\Pages;
use App\Models\Mount;
use Filament\Resources\Resource;

class MountResource extends Resource
{
    protected static ?string $model = Mount::class;

    protected static ?string $navigationIcon = 'tabler-layers-linked';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return trans('admin/mount.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/mount.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/mount.model_label_plural');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('admin/dashboard.advanced');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMounts::route('/'),
            'create' => Pages\CreateMount::route('/create'),
            'edit' => Pages\EditMount::route('/{record}/edit'),
        ];
    }
}
