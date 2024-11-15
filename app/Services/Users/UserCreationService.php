<?php

namespace App\Services\Users;

use App\Models\Role;
use Ramsey\Uuid\Uuid;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Auth\PasswordBroker;
use App\Notifications\AccountCreated;

class UserCreationService
{
    /**
     * UserCreationService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
        private Hasher $hasher,
        private PasswordBroker $passwordBroker,
    ) {}

    /**
     * Create a new user on the system.
     *
     * @throws \Exception
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function handle(array $data): User
    {
        if (array_key_exists('password', $data) && !empty($data['password'])) {
            $data['password'] = $this->hasher->make($data['password']);
        }

        $this->connection->beginTransaction();
        if (empty($data['password'])) {
            $generateResetToken = true;
            $data['password'] = $this->hasher->make(str_random(30));
        }

        $isRootAdmin = array_key_exists('root_admin', $data) && $data['root_admin'];
        unset($data['root_admin']);

        $user = User::query()->forceCreate(array_merge($data, [
            'uuid' => Uuid::uuid4()->toString(),
        ]));

        if ($isRootAdmin) {
            $user->syncRoles(Role::getRootAdmin());
        }

        if (isset($generateResetToken)) {
            $token = $this->passwordBroker->createToken($user);
        }

        $this->connection->commit();
        $user->notify(new AccountCreated($user, $token ?? null));

        return $user;
    }
}
