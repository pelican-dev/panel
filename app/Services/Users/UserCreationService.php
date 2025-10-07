<?php

namespace App\Services\Users;

use App\Exceptions\Model\DataValidationException;
use App\Models\Role;
use App\Models\User;
use App\Notifications\AccountCreated;
use Exception;
use Filament\Facades\Filament;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class UserCreationService
{
    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly Hasher $hasher,
    ) {}

    /**
     * Create a new user on the system.
     *
     * @param  array<array-key, mixed>  $data
     *
     * @throws Exception
     * @throws DataValidationException
     */
    public function handle(array $data): User
    {
        if (array_key_exists('password', $data) && !empty($data['password'])) {
            $data['password'] = $this->hasher->make($data['password']);
        }

        $this->connection->beginTransaction();
        if (empty($data['password'])) {
            $generateResetToken = true;
            $data['password'] = $this->hasher->make(Str::random(30));
        }

        $isRootAdmin = array_key_exists('root_admin', $data) && $data['root_admin'];
        unset($data['root_admin']);

        if (empty($data['username'])) {
            $data['username'] = str($data['email'])->before('@')->toString() . Str::random(3);
        }

        $data['username'] = str($data['username'])
            ->replace(['.', '-'], '')
            ->ascii()
            ->substr(0, 64)
            ->toString();

        /** @var User $user */
        $user = User::query()->forceCreate(array_merge($data, [
            'uuid' => Uuid::uuid4()->toString(),
        ]));

        if ($isRootAdmin) {
            $user->syncRoles(Role::getRootAdmin());
        }

        if (isset($generateResetToken)) {
            /** @var PasswordBroker $broker */
            $broker = Password::broker(Filament::getPanel('app')->getAuthPasswordBroker());
            $token = $broker->createToken($user);
        }

        $this->connection->commit();

        $user->notify(new AccountCreated($token ?? null));

        return $user;
    }
}
