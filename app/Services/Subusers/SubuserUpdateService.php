<?php

namespace App\Services\Subusers;

use App\Enums\SubuserPermission;
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

    /**
     * @param  string[]  $permissions
     */
    public function handle(Subuser $subuser, Server $server, array $permissions): void
    {
        $cleanedPermissions = collect($permissions)
            ->unique()
            ->filter(fn ($permission) => $permission === SubuserPermission::WebsocketConnect->value || user()?->can($permission, $server))
            ->sort()
            ->values()
            ->all();

        $current = $subuser->permissions;
        sort($current);

        $log = Activity::event('server:subuser.update')
            ->subject($subuser->user)
            ->property([
                'email' => $subuser->user->email,
                'old' => $current,
                'new' => $cleanedPermissions,
                'revoked' => true,
            ]);

        // Only update the database and hit up the daemon instance to invalidate JTI's if the permissions
        // have actually changed for the user.
        if ($cleanedPermissions !== $current) {
            $log->transaction(function ($instance) use ($subuser, $cleanedPermissions, $server) {
                $subuser->update(['permissions' => $cleanedPermissions]);

                try {
                    $this->serverRepository->setServer($server)->deauthorize($subuser->user->uuid);
                } catch (ConnectionException $exception) {
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
