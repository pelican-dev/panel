<?php

namespace App\Http\Requests\Api\Client\Servers\Backups;

use App\Enums\SubuserPermission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class RestoreBackupRequest extends ClientApiRequest
{
    public function permission(): SubuserPermission
    {
        return SubuserPermission::BackupRestore;
    }

    public function rules(): array
    {
        return ['truncate' => 'required|boolean'];
    }
}
