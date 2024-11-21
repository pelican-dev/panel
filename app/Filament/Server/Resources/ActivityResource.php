<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\ActivityResource\Pages;
use App\Models\ActivityLog;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Resources\Resource;

class ActivityResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'tabler-stack';

    protected static ?string $tenantOwnershipRelationshipName = 'actor';

    protected static ?string $tenantRelationshipName = 'activity'; // TODO: not displaying anything

    // TODO: find better way handle server conflict state
    public static function canAccess(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        if ($server->isInConflictState()) {
            return false;
        }

        return parent::canAccess();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
        ];
    }
}
