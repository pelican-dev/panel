<?php

namespace App\Http\Requests\Api\Client\Servers\Network;

use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\Permission;

class GetNetworkRequest extends ClientApiRequest
{
    /**
     * Check that the user has permission to view the allocations for
     * this server.
     */
    public function permission(): string
    {
        return Permission::ACTION_ALLOCATION_READ;
    }
}
