<?php

namespace App\Services\Files;

use App\Models\Server;
use App\Repositories\Daemon\DaemonFileRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Str;

readonly class DeleteFilesService
{
    public function __construct(
        private DaemonFileRepository $daemonFileRepository
    ) {}

    /**
     * Deletes the given files.
     *
     * @param  string[]  $files
     *
     * @throws ConnectionException
     */
    public function handle(Server $server, array $files): void
    {
        $filesToDelete = collect();
        foreach ($files as $line) {
            $path = dirname($line);
            $pattern = basename($line);
            collect($this->daemonFileRepository->setServer($server)->getDirectory($path))->each(function ($item) use ($path, $pattern, $filesToDelete) {
                if (Str::is($pattern, $item['name'])) {
                    $filesToDelete->push($path . '/' . $item['name']);
                }
            });
        }

        if ($filesToDelete->isNotEmpty()) {
            $this->daemonFileRepository->setServer($server)->deleteFiles('/', $filesToDelete->toArray());
        }
    }
}
