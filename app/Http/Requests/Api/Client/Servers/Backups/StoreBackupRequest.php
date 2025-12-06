<?php

namespace App\Http\Requests\Api\Client\Servers\Backups;

use App\Enums\SubuserPermission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class StoreBackupRequest extends ClientApiRequest
{
    public function permission(): SubuserPermission
    {
        return SubuserPermission::BackupCreate;
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
