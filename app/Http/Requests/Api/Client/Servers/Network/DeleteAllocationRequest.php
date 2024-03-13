<?php

namespace App\Http\Requests\Api\Client\Servers\Network;

use App\Models\Permission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class DeleteAllocationRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_ALLOCATION_DELETE;
    }
}
