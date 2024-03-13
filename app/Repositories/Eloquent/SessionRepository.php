<?php

namespace App\Repositories\Eloquent;

use App\Models\Session;
use Illuminate\Support\Collection;
use App\Contracts\Repository\SessionRepositoryInterface;

class SessionRepository extends EloquentRepository implements SessionRepositoryInterface
{
    /**
     * Return the model backing this repository.
     */
    public function model(): string
    {
        return Session::class;
    }

    /**
     * Return all the active sessions for a user.
     */
    public function getUserSessions(int $user): Collection
    {
        return $this->getBuilder()->where('user_id', $user)->get($this->getColumns());
    }

    /**
     * Delete a session for a given user.
     */
    public function deleteUserSession(int $user, string $session): ?int
    {
        return $this->getBuilder()->where('user_id', $user)->where('id', $session)->delete();
    }
}
