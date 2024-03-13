<?php

namespace App\Http\Requests\Api\Client\Servers\Settings;

use App\Models\Permission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class ReinstallServerRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_SETTINGS_REINSTALL;
    }
}
