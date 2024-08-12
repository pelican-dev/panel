<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ActivityResource\Pages;
use App\Models\ActivityLog;
use Filament\Resources\Resource;

class ActivityResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'tabler-stack';

    protected static ?string $tenantOwnershipRelationshipName = 'actor';

    protected static ?string $tenantRelationshipName = 'activity'; // TODO: not displaying anything

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
        ];
    }
}
