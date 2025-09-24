<?php

namespace App\Http\Requests\Api\Client\Servers\Files;

use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\Permission;

class UploadFileRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_FILE_CREATE;
    }
}
