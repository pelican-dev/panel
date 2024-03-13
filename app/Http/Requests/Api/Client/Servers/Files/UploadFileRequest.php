<?php

namespace App\Http\Requests\Api\Client\Servers\Files;

use App\Models\Permission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class UploadFileRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_FILE_CREATE;
    }
}
