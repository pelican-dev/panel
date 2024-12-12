<?php

namespace App\Services\Subusers;

use App\Exceptions\Http\Connection\DaemonConnectionException;
use App\Facades\Activity;
use App\Models\Server;
use App\Models\Subuser;
use App\Repositories\Daemon\DaemonServerRepository;
use Illuminate\Http\Client\ConnectionException;

class SubuserUpdateService
{
    public function __construct(
        private DaemonServerRepository $serverRepository,
    ) {}

    public function handle(Subuser $subuser, Server $server, array $permissions): void
    {
        $current = $subuser->permissions;

        sort($permissions);
        sort($current);

        $log = Activity::event('server:subuser.update')
            ->subject($subuser->user)
            ->property([
                'email' => $subuser->user->email,
                'old' => $current,
                'new' => $permissions,
                'revoked' => true,
            ]);

        // Only update the database and hit up the daemon instance to invalidate JTI's if the permissions
        // have actually changed for the user.
        if ($permissions !== $current) {
            $log->transaction(function ($instance) use ($subuser, $permissions, $server) {
                $subuser->update(['permissions' => $permissions]);

                try {
                    $this->serverRepository->setServer($server)->revokeUserJTI($subuser->user_id);
                } catch (ConnectionException|DaemonConnectionException $exception) {
                    // Don't block this request if we can't connect to the daemon instance. Chances are it is
                    // offline and the token will be invalid once daemon boots back.
                    logger()->warning($exception, ['user_id' => $subuser->user_id, 'server_id' => $server->id]);

                    $instance->property('revoked', false);
                }
            });
        }

        $log->reset();
    }
}
