<?php

namespace App\Services\Subusers;

use App\Events\Server\SubUserRemoved;
use App\Facades\Activity;
use App\Models\Server;
use App\Models\Subuser;
use App\Repositories\Daemon\DaemonServerRepository;
use Illuminate\Http\Client\ConnectionException;

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

            event(new SubUserRemoved($subuser->server, $subuser->user));

            try {
                $this->serverRepository->setServer($server)->deauthorize($subuser->user->uuid);
            } catch (ConnectionException $exception) {
                // Don't block this request if we can't connect to the daemon instance.
                logger()->warning($exception, ['user_id' => $subuser->user_id, 'server_id' => $server->id]);

                $instance->property('revoked', false);
            }
        });
    }
}
