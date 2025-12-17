<?php

namespace App\Http\Requests\Api\Client\Servers\Files;

use App\Enums\SubuserPermission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class UploadFileRequest extends ClientApiRequest
{
    public function permission(): SubuserPermission
    {
        return SubuserPermission::FileCreate;
    }
}
