<?php

namespace App\Models;

use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    public const ROOT_ADMIN = 'Root Admin';

    public function isRootAdmin(): bool
    {
        return $this->name === self::ROOT_ADMIN;
    }
}
