<?php

namespace App\Http\Controllers\Api\Client;

use App\Models\ActivityLog;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Transformers\Api\Client\ActivityLogTransformer;

class ActivityLogController extends ClientApiController
{
    /**
     * List activity logs
     *
     * Returns a paginated set of the user's activity logs.
     *
     * @return array<array-key, mixed>
     */
    public function __invoke(ClientApiRequest $request): array
    {
        $activity = QueryBuilder::for($request->user()->activity())
            ->allowedFilters([AllowedFilter::partial('event')])
            ->allowedSorts(['timestamp'])
            ->with('actor')
            ->whereNotIn('activity_logs.event', ActivityLog::DISABLED_EVENTS)
            ->paginate(min($request->query('per_page', '25'), 100))
            ->appends($request->query());

        return $this->fractal->collection($activity)
            ->transformWith($this->getTransformer(ActivityLogTransformer::class))
            ->toArray();
    }
}
