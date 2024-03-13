<?php

namespace App\Http\Requests\Api\Client\Servers\Startup;

use App\Models\Permission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class GetStartupRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_STARTUP_READ;
    }
}
