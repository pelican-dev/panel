<?php

namespace App\Services\Users;

use App\Models\User;
use App\Exceptions\DisplayException;
use Illuminate\Contracts\Translation\Translator;

class UserDeletionService
{
    /**
     * UserDeletionService constructor.
     */
    public function __construct(
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

        return $user->delete();
    }
}
