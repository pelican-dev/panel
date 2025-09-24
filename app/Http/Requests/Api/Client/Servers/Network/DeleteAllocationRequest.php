<?php

namespace App\Http\Requests\Api\Client\Servers\Network;

use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\Permission;

class DeleteAllocationRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_ALLOCATION_DELETE;
    }
}
