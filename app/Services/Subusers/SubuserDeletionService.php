<?php

namespace App\Services\Subusers;

use App\Exceptions\Http\Connection\DaemonConnectionException;
use App\Facades\Activity;
use App\Models\Server;
use App\Models\Subuser;
use App\Notifications\RemovedFromServer;
use App\Repositories\Daemon\DaemonServerRepository;

class SubuserDeletionService
{
    public function __construct(
        private DaemonServerRepository $serverRepository,
    ) {}

    public function handle(Subuser $subuser, Server $server): void
    {
        $log = Activity::event('server:subuser.delete')
            ->subject($subuser->user)
            ->property('email', $subuser->user->email)
            ->property('revoked', true);

        $log->transaction(function ($instance) use ($server, $subuser) {
            $subuser->delete();

            $subuser->user->notify(new RemovedFromServer([
                'user' => $subuser->user->name_first,
                'name' => $subuser->server->name,
            ]));

            try {
                $this->serverRepository->setServer($server)->revokeUserJTI($subuser->user_id);
            } catch (DaemonConnectionException $exception) {
                // Don't block this request if we can't connect to the daemon instance.
                logger()->warning($exception, ['user_id' => $subuser->user_id, 'server_id' => $server->id]);

                $instance->property('revoked', false);
            }
        });
    }
}
