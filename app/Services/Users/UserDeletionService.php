<?php

namespace App\Services\Users;

use App\Models\User;
use App\Exceptions\DisplayException;
use Illuminate\Contracts\Translation\Translator;
use App\Contracts\Repository\UserRepositoryInterface;

class UserDeletionService
{
    /**
     * UserDeletionService constructor.
     */
    public function __construct(
        protected UserRepositoryInterface $repository,
        protected Translator $translator
    ) {
    }

    /**
     * Delete a user from the panel only if they have no servers attached to their account.
     *
     * @throws DisplayException
     */
    public function handle(int|User $user): ?bool
    {
        if (is_int($user)) {
            $user = User::findOrFail($user);
        }

        if ($user->servers()->count() > 0) {
            throw new DisplayException($this->translator->get('admin/user.exceptions.user_has_servers'));
        }

        return $this->repository->delete($user->id);
    }
}
