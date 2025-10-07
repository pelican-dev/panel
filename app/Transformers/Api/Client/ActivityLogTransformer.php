<?php

namespace App\Transformers\Api\Client;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use League\Fractal\Resource\ResourceAbstract;

class ActivityLogTransformer extends BaseClientTransformer
{
    protected array $availableIncludes = ['actor'];

    public function getResourceName(): string
    {
        return ActivityLog::RESOURCE_NAME;
    }

    /**
     * @param  ActivityLog  $model
     */
    public function transform($model): array
    {
        return [
            // This is not for security, it is only to provide a unique identifier to
            // the front-end for each entry to improve rendering performance since there
            // is nothing else sufficiently unique to key off at this point.
            'id' => sha1((string) $model->id),
            'event' => $model->event,
            'is_api' => !is_null($model->api_key_id),
            'ip' => $this->canViewIP($model->actor) ? $model->ip : null,
            'description' => $model->description,
            'properties' => $model->wrapProperties(),
            'has_additional_metadata' => $model->hasAdditionalMetadata(),
            'timestamp' => $model->timestamp->toAtomString(),
        ];
    }

    public function includeActor(ActivityLog $model): ResourceAbstract
    {
        if (!$model->actor instanceof User) {
            return $this->null();
        }

        return $this->item($model->actor, $this->makeTransformer(UserTransformer::class), User::RESOURCE_NAME);
    }

    /**
     * Determines if the user can view the IP address in the output either because they are the
     * actor that performed the action, or because they are an administrator on the Panel.
     */
    protected function canViewIP(?Model $actor = null): bool
    {
        return $actor?->is($this->request->user()) || $this->request->user()->can('seeIps activityLog');
    }
}
