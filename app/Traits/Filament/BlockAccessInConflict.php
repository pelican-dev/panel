<?php

namespace App\Traits\Filament;

use App\Models\Server;
use Filament\Facades\Filament;

trait BlockAccessInConflict
{
    public static function canAccess(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        if ($server->isInConflictState()) {
            return false;
        }

        return parent::canAccess();
    }
}
