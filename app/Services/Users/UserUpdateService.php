<?php

namespace App\Services\Users;

use App\Models\User;
use App\Traits\Services\HasUserLevels;
use Illuminate\Contracts\Hashing\Hasher;
use Throwable;

class UserUpdateService
{
    use HasUserLevels;

    public function __construct(private readonly Hasher $hasher) {}

    /**
     * Update the user model instance and return the updated model.
     *
     * @param  array<array-key, mixed>  $data
     *
     * @throws Throwable
     */
    public function handle(User $user, array $data): User
    {
        if (!empty(array_get($data, 'password'))) {
            $data['password'] = $this->hasher->make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->forceFill($data)->saveOrFail();

        return $user->refresh();
    }
}
