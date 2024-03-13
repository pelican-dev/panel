<?php

namespace App\Repositories\Eloquent;

use App\Models\RecoveryToken;

class RecoveryTokenRepository extends EloquentRepository
{
    public function model(): string
    {
        return RecoveryToken::class;
    }
}
