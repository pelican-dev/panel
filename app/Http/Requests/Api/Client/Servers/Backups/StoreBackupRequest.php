<?php

namespace App\Http\Requests\Api\Client\Servers\Backups;

use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\Permission;

class StoreBackupRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_BACKUP_CREATE;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'is_locked' => 'nullable|boolean',
            'ignored' => 'nullable|string',
        ];
    }
}
