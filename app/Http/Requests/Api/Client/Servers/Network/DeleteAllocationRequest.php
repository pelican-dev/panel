<?php

namespace App\Http\Requests\Api\Client\Servers\Network;

use App\Enums\SubuserPermission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class DeleteAllocationRequest extends ClientApiRequest
{
    public function permission(): SubuserPermission
    {
        return SubuserPermission::AllocationDelete;
    }
}
