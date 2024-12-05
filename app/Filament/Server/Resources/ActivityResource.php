<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\ActivityResource\Pages;
use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Server;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

class ActivityResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $label = 'Activity';

    protected static ?string $pluralLabel = 'Activity';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationIcon = 'tabler-stack';

    public static function getEloquentQuery(): Builder
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $server->activity()
            ->getQuery()
            ->whereNotIn('activity_logs.event', ActivityLog::DISABLED_EVENTS)
            ->when(config('activity.hide_admin_activity'), function (Builder $builder) use ($server) {
                // We could do this with a query and a lot of joins, but that gets pretty
                // painful so for now we'll execute a simpler query.
                $subusers = $server->subusers()->pluck('user_id')->merge([$server->owner_id]);
                $rootAdmins = Role::getRootAdmin()->users()->pluck('id');

                $builder->select('activity_logs.*')
                    ->leftJoin('users', function (JoinClause $join) {
                        $join->on('users.id', 'activity_logs.actor_id')
                            ->where('activity_logs.actor_type', (new User())->getMorphClass());
                    })
                    ->where(function (Builder $builder) use ($subusers, $rootAdmins) {
                        $builder->whereNull('users.id')
                            ->orWhereNotIn('users.id', $rootAdmins)
                            ->orWhereIn('users.id', $subusers);
                    });
            });
    }

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

    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::ACTION_ACTIVITY_READ, Filament::getTenant());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
        ];
    }
}
