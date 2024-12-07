<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\RelationManagers\ServersRelationManager;
use App\Models\User;
use Filament\Resources\Resource;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'tabler-users';

    protected static ?string $recordTitleAttribute = 'username';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getRelations(): array
    {
        return [
            ServersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\UserResource\Pages\ListUsers::route('/'),
            'edit' => \App\Filament\Admin\Resources\UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
