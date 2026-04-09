<?php

namespace App\Services\Subusers;

use App\Events\Server\SubUserRemoved;
use App\Facades\Activity;
use App\Jobs\RevokeSftpAccessJob;
use App\Models\Server;
use App\Models\Subuser;

class SubuserDeletionService
{
    public function handle(Subuser $subuser, Server $server): void
    {
        $log = Activity::event('server:subuser.delete')
            ->subject($subuser->user)
            ->property('email', $subuser->user->email)
            ->property('revoked', true);

        $log->transaction(function ($instance) use ($server, $subuser) {
            $subuser->delete();

            event(new SubUserRemoved($subuser->server, $subuser->user));

            RevokeSftpAccessJob::dispatch($subuser->user->uuid, $server);
        });
    }
}
