<?php

namespace App\Policies;

use App\Models\BackupHost;
use App\Models\User;

class BackupHostPolicy
{
    use DefaultAdminPolicies;

    protected string $modelName = 'backupHost';

    public function before(User $user, string $ability, string|BackupHost $backupHost): ?bool
    {
        // For "viewAny" the $backupHost param is the class name
        if (is_string($backupHost)) {
            return null;
        }

        foreach ($backupHost->nodes as $node) {
            if (!$user->canTarget($node)) {
                return false;
            }
        }

        return null;
    }
}
