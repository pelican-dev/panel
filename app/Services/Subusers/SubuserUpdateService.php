<?php

namespace App\Services\Subusers;

use App\Enums\SubuserPermission;
use App\Facades\Activity;
use App\Jobs\RevokeSftpAccessJob;
use App\Models\Server;
use App\Models\Subuser;

class SubuserUpdateService
{
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
            $log->transaction(function () use ($subuser, $cleanedPermissions, $server) {
                $subuser->update(['permissions' => $cleanedPermissions]);

                RevokeSftpAccessJob::dispatch($subuser->user->uuid, $server);
            });
        }

        $log->reset();
    }
}
