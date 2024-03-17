<?php

namespace App\Services\Databases\Hosts;

use App\Exceptions\Service\HasActiveServersException;
use App\Models\DatabaseHost;

class HostDeletionService
{
    /**
     * Delete a specified host from the Panel if no databases are
     * attached to it.
     *
     * @throws \App\Exceptions\Service\HasActiveServersException
     */
    public function handle(int $host): int
    {
        $host = DatabaseHost::query()->findOrFail($host);

        if ($host->databases()->count() > 0) {
            throw new HasActiveServersException(trans('exceptions.databases.delete_has_databases'));
        }

        return (int) $host->delete();
    }
}
