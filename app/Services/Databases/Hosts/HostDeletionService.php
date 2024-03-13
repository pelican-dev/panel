<?php

namespace App\Services\Databases\Hosts;

use App\Exceptions\Service\HasActiveServersException;
use App\Contracts\Repository\DatabaseRepositoryInterface;
use App\Contracts\Repository\DatabaseHostRepositoryInterface;

class HostDeletionService
{
    /**
     * HostDeletionService constructor.
     */
    public function __construct(
        private DatabaseRepositoryInterface $databaseRepository,
        private DatabaseHostRepositoryInterface $repository
    ) {
    }

    /**
     * Delete a specified host from the Panel if no databases are
     * attached to it.
     *
     * @throws \App\Exceptions\Service\HasActiveServersException
     */
    public function handle(int $host): int
    {
        $count = $this->databaseRepository->findCountWhere([['database_host_id', '=', $host]]);
        if ($count > 0) {
            throw new HasActiveServersException(trans('exceptions.databases.delete_has_databases'));
        }

        return $this->repository->delete($host);
    }
}
