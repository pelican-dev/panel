<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Server;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_server');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Server $server): bool
    {
        return $user->can('view_server');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_server');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Server $server): bool
    {
        return $user->can('update_server');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Server $server): bool
    {
        return $user->can('delete_server');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_server');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Server $server): bool
    {
        return $user->can('force_delete_server');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_server');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Server $server): bool
    {
        return $user->can('restore_server');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_server');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Server $server): bool
    {
        return $user->can('replicate_server');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_server');
    }
}
