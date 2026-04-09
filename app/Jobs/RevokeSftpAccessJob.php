<?php

namespace App\Jobs;

use App\Models\Node;
use App\Models\Server;
use App\Repositories\Daemon\DaemonServerRepository;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\Attributes\DeleteWhenMissingModels;
use Illuminate\Queue\Attributes\WithoutRelations;

/**
 * Revokes all SFTP access for a user on a given node or for a specific server.
 */
#[DeleteWhenMissingModels]
class RevokeSftpAccessJob implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $maxExceptions = 1;

    public function __construct(
        public readonly string $user,
        #[WithoutRelations]
        public readonly Server|Node $target,
    ) {}

    public function uniqueId(): string
    {
        $target = $this->target instanceof Node ? "node:{$this->target->uuid}" : "server:{$this->target->uuid}";

        return "revoke-sftp:{$this->user}:{$target}";
    }

    public function handle(DaemonServerRepository $repository): void
    {
        try {
            if ($this->target instanceof Server) {
                $repository->setServer($this->target)->deauthorize($this->user);
            } else {
                $repository->setNode($this->target)->deauthorize($this->user);
            }
        } catch (ConnectionException) {
            // Keep retrying this job with a longer and longer backoff until we hit three
            // attempts at which point we stop and will assume the node is fully offline
            // and we are just wasting time.
            $this->release($this->attempts() * 10);
        }
    }
}
