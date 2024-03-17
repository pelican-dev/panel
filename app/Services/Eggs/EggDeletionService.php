<?php

namespace App\Services\Eggs;

use App\Exceptions\Service\Egg\HasChildrenException;
use App\Exceptions\Service\HasActiveServersException;
use App\Models\Egg;
use App\Models\Server;

class EggDeletionService
{
    /**
     * Delete an Egg from the database if it has no active servers attached to it.
     *
     * @throws \App\Exceptions\Service\HasActiveServersException
     * @throws \App\Exceptions\Service\Egg\HasChildrenException
     */
    public function handle(int $egg): int
    {
        if (Server::query()->where('egg_id', $egg)->count()) {
            throw new HasActiveServersException(trans('exceptions.egg.delete_has_servers'));
        }

        $children = Egg::query()->where('config_from', $egg)->count();
        if ($children > 0) {
            throw new HasChildrenException(trans('exceptions.egg.has_children'));
        }

        $egg = Egg::query()->findOrFail($egg);

        return (int) $egg->delete();
    }
}
