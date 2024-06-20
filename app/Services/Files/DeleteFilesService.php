<?php

namespace App\Services\Files;

use App\Models\Server;
use App\Repositories\Daemon\DaemonFileRepository;

class DeleteFilesService
{
    /**
     * DeleteFilesService constructor.
     */
    public function __construct(
        private DaemonFileRepository $fileRepository
    ) {
    }

    /**
     * Deletes the given files.
     */
    public function handle(Server $server, array $files): void
    {
        // TODO: convert $files
        // see https://github.com/Boy132/panel/pull/2/files

        $this->fileRepository->setServer($server)->deleteFiles('/', $files);
    }
}
