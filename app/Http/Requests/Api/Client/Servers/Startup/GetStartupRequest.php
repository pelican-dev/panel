<?php

namespace App\Http\Requests\Api\Client\Servers\Startup;

use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\Permission;

class GetStartupRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_STARTUP_READ;
    }
}
