<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait DefaultPolicies
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('viewList ' . $this->modelName);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Model $model): bool
    {
        return $user->can('view ' . $this->modelName, $model);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create ' . $this->modelName);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Model $model): bool
    {
        return $user->can('update ' . $this->modelName, $model);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Model $model): bool
    {
        return $user->can('delete ' . $this->modelName, $model);
    }
}
