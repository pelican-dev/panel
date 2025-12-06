<?php

namespace App\Http\Requests\Api\Client\Servers\Settings;

use App\Enums\SubuserPermission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class ReinstallServerRequest extends ClientApiRequest
{
    public function permission(): SubuserPermission
    {
        return SubuserPermission::SettingsReinstall;
    }
}
