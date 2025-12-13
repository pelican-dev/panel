<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait DefaultAdminPolicies
{
    public function viewAny(User $user): bool
    {
        return $user->can('viewList ' . $this->modelName);
    }

    public function view(User $user, Model $model): bool
    {
        return $user->can('view ' . $this->modelName, $model);
    }

    public function create(User $user): bool
    {
        return $user->can('create ' . $this->modelName);
    }

    public function update(User $user, Model $model): bool
    {
        return $user->can('update ' . $this->modelName, $model);
    }

    public function delete(User $user, Model $model): bool
    {
        return $user->can('delete ' . $this->modelName, $model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete ' . $this->modelName);
    }

    public function replicate(User $user, Model $model): bool
    {
        return $user->can('create ' . $this->modelName);
    }
}
