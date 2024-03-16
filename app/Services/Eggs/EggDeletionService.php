<?php

namespace App\Services\Eggs;

use App\Contracts\Repository\EggRepositoryInterface;
use App\Exceptions\Service\Egg\HasChildrenException;
use App\Exceptions\Service\HasActiveServersException;
use App\Models\Server;

class EggDeletionService
{
    /**
     * EggDeletionService constructor.
     */
    public function __construct(
        protected EggRepositoryInterface $repository
    ) {
    }

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

        $children = $this->repository->findCountWhere([['config_from', '=', $egg]]);
        if ($children > 0) {
            throw new HasChildrenException(trans('exceptions.egg.has_children'));
        }

        return $this->repository->delete($egg);
    }
}
