<?php

namespace App\Policies;

use App\Models\User;

class EggPolicy
{
    public function create(User $user): bool
    {
        return true;
    }
}
