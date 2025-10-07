<?php

namespace App\Http\Requests\Api\Client\Servers\Backups;

use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\Permission;

class RestoreBackupRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_BACKUP_RESTORE;
    }

    public function rules(): array
    {
        return ['truncate' => 'required|boolean'];
    }
}
