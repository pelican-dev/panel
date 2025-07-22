<?php

namespace App\Http\Requests\Api\Client\Servers\Backups;

use App\Models\Permission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class RenameBackupRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_BACKUP_DELETE;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
