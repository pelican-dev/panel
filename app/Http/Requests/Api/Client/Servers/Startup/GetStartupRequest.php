<?php

namespace App\Http\Requests\Api\Client\Servers\Startup;

use App\Enums\SubuserPermission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class GetStartupRequest extends ClientApiRequest
{
    public function permission(): SubuserPermission
    {
        return SubuserPermission::StartupRead;
    }
}
