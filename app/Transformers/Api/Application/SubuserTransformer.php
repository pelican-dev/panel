<?php

namespace App\Transformers\Api\Application;

use App\Models\Server;
use App\Models\Subuser;
use App\Models\User;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\NullResource;

class SubuserTransformer extends BaseTransformer
{
    /**
     * List of resources that can be included.
     */
    protected array $availableIncludes = ['user', 'server'];

    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return Subuser::RESOURCE_NAME;
    }

    /**
     * @param  Subuser  $subuser
     */
    public function transform($subuser): array
    {
        return [
            'id' => $subuser->id,
            'user_id' => $subuser->user_id,
            'server_id' => $subuser->server_id,
            'permissions' => $subuser->permissions,
            'created_at' => $this->formatTimestamp($subuser->created_at),
            'updated_at' => $this->formatTimestamp($subuser->updated_at),
        ];
    }

    /**
     * Return a generic item of user for this subuser.
     */
    public function includeUser(Subuser $subuser): Item|NullResource
    {
        if (!$this->authorize(User::RESOURCE_NAME)) {
            return $this->null();
        }

        $subuser->loadMissing('user');

        return $this->item($subuser->getRelation('user'), $this->makeTransformer(UserTransformer::class), 'user');
    }

    /**
     * Return a generic item of server for this subuser.
     */
    public function includeServer(Subuser $subuser): Item|NullResource
    {
        if (!$this->authorize(Server::RESOURCE_NAME)) {
            return $this->null();
        }

        $subuser->loadMissing('server');

        return $this->item($subuser->getRelation('server'), $this->makeTransformer(ServerTransformer::class), 'server');
    }
}
