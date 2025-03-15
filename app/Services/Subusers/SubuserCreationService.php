<?php

namespace App\Services\Subusers;

use App\Events\Server\SubUserAdded;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Server;
use App\Models\Subuser;
use Illuminate\Database\ConnectionInterface;
use App\Services\Users\UserCreationService;
use App\Exceptions\Service\Subuser\UserIsServerOwnerException;
use App\Exceptions\Service\Subuser\ServerSubuserExistsException;
use App\Models\Permission;

class SubuserCreationService
{
    /**
     * SubuserCreationService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
        private UserCreationService $userCreationService,
    ) {}

    /**
     * Creates a new user on the system and assigns them access to the provided server.
     * If the email address already belongs to a user on the system a new user will not
     * be created.
     *
     * @param  string[]  $permissions
     *
     * @throws \App\Exceptions\Model\DataValidationException
     * @throws \App\Exceptions\Service\Subuser\ServerSubuserExistsException
     * @throws \App\Exceptions\Service\Subuser\UserIsServerOwnerException
     * @throws \Throwable
     */
    public function handle(Server $server, string $email, array $permissions): Subuser
    {
        return $this->connection->transaction(function () use ($server, $email, $permissions) {
            $user = User::query()->where('email', $email)->first();
            if (!$user) {
                // Just cap the username generated at 64 characters at most and then append a random string
                // to the end to make it "unique"...
                [$beforeDomain] = explode('@', $email, 1);
                $username = substr(preg_replace('/([^\w.-]+)/', '', $beforeDomain), 0, 64) . Str::random(3);

                $user = $this->userCreationService->handle([
                    'email' => $email,
                    'username' => $username,
                    'root_admin' => false,
                ]);
            }

            if ($server->owner_id === $user->id) {
                throw new UserIsServerOwnerException(trans('exceptions.subusers.user_is_owner'));
            }

            $subuserCount = $server->subusers()->where('user_id', $user->id)->count();
            if ($subuserCount !== 0) {
                throw new ServerSubuserExistsException(trans('exceptions.subusers.subuser_exists'));
            }

            $cleanedPermissions = collect($permissions)
                ->unique()
                ->filter(fn ($permission) => $permission === Permission::ACTION_WEBSOCKET_CONNECT || auth()->user()->can($permission, $server))
                ->sort()
                ->values()
                ->all();

            $subuser = Subuser::query()->create([
                'user_id' => $user->id,
                'server_id' => $server->id,
                'permissions' => $cleanedPermissions,
            ]);

            event(new SubUserAdded($subuser));

            return $subuser;
        });
    }
}
