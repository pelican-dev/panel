<?php

namespace App\Http\Requests\Api\Client\Servers\Settings;

use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\Permission;

class ReinstallServerRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_SETTINGS_REINSTALL;
    }
}
