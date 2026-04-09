<?php

namespace App\Listeners;

use App\Events\User\Deleting;
use App\Events\User\PasswordChanged;
use App\Jobs\RevokeSftpAccessJob;
use App\Models\Node;
use Illuminate\Database\Eloquent\Collection;

class RevocationListener
{
    public function handle(Deleting|PasswordChanged $event): void
    {
        $user = $event->user;

        // Look at all of the nodes that a user is associated with and trigger a job
        // that disconnects them from websockets and SFTP.
        Node::query()
            ->whereIn('nodes.id', $user->directAccessibleServers()->select('servers.node_id')->distinct())
            ->chunk(50, function (Collection $nodes) use ($user) {
                $nodes->each(fn (Node $node) => RevokeSftpAccessJob::dispatch($user->uuid, $node));
            });
    }
}
