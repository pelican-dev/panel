<?php

namespace App\Http\Controllers\Api\Client\Servers;

use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Server;
use App\Models\Permission;
use App\Models\ActivityLog;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Transformers\Api\Client\ActivityLogTransformer;
use App\Http\Controllers\Api\Client\ClientApiController;
use App\Models\Role;
use Dedoc\Scramble\Attributes\Group;

#[Group('Server - Activity log')]
class ActivityLogController extends ClientApiController
{
    /**
     * List activity logs
     *
     * Returns the activity logs for a server.
     *
     * @return array<array-key, mixed>
     */
    public function __invoke(ClientApiRequest $request, Server $server): array
    {
        Gate::authorize(Permission::ACTION_ACTIVITY_READ, $server);

        $activity = QueryBuilder::for($server->activity())
            ->allowedSorts(['timestamp'])
            ->allowedFilters([AllowedFilter::partial('event')])
            ->with('actor')
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
            })
            ->paginate(min($request->query('per_page', '25'), 100))
            ->appends($request->query());

        return $this->fractal->collection($activity)
            ->transformWith($this->getTransformer(ActivityLogTransformer::class))
            ->toArray();
    }
}
