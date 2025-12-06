<?php

namespace App\Http\Requests\Api\Client\Servers\Network;

use App\Enums\SubuserPermission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class GetNetworkRequest extends ClientApiRequest
{
    /**
     * Check that the user has permission to view the allocations for
     * this server.
     */
    public function permission(): SubuserPermission
    {
        return SubuserPermission::AllocationRead;
    }
}
