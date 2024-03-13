<?php

namespace App\Http\Requests\Api\Client\Servers\Databases;

use App\Models\Permission;
use App\Contracts\Http\ClientPermissionsRequest;
use App\Http\Requests\Api\Client\ClientApiRequest;

class GetDatabasesRequest extends ClientApiRequest implements ClientPermissionsRequest
{
    public function permission(): string
    {
        return Permission::ACTION_DATABASE_READ;
    }
}
