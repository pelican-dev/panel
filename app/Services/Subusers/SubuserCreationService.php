<?php

namespace App\Services\Subusers;

use App\Models\User;
use App\Notifications\AddedToServer;
use Illuminate\Support\Str;
use App\Models\Server;
use App\Models\Subuser;
use Illuminate\Database\ConnectionInterface;
use App\Services\Users\UserCreationService;
use App\Exceptions\Service\Subuser\UserIsServerOwnerException;
use App\Exceptions\Service\Subuser\ServerSubuserExistsException;

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
                $username = substr(preg_replace('/([^\w\.-]+)/', '', strtok($email, '@')), 0, 64) . Str::random(3);

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

            $subuser = Subuser::query()->create([
                'user_id' => $user->id,
                'server_id' => $server->id,
                'permissions' => array_unique($permissions),
            ]);

            $subuser->user->notify(new AddedToServer([
                'user' => $subuser->user->name_first,
                'name' => $subuser->server->name,
                'uuid_short' => $subuser->server->uuid_short,
            ]));

            return $subuser;
        });
    }
}
