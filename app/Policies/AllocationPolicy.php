<?php

namespace App\Policies;

use App\Enums\SubuserPermission;
use App\Models\Server;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class AllocationPolicy
{
    use DefaultAdminPolicies {
        viewAny as adminViewAny;
        view as adminView;
        create as adminCreate;
        update as adminUpdate;
        delete as adminDelete;
        deleteAny as adminDeleteAny;
    }

    protected string $modelName = 'allocation';

    public function viewAny(User $user): bool
    {
        /** @var ?Server $server */
        $server = Filament::getTenant();

        return $server ? $user->can(SubuserPermission::AllocationRead, $server) : $this->adminViewAny($user);
    }

    public function view(User $user, Model $model): bool
    {
        /** @var ?Server $server */
        $server = Filament::getTenant();

        return $server ? $user->can(SubuserPermission::AllocationRead, $server) : $this->adminView($user, $model);
    }

    public function create(User $user): bool
    {
        /** @var ?Server $server */
        $server = Filament::getTenant();

        return $server ? $user->can(SubuserPermission::AllocationCreate, $server) : $this->adminCreate($user);
    }

    public function update(User $user, Model $model): bool
    {
        /** @var ?Server $server */
        $server = Filament::getTenant();

        return $server ? $user->can(SubuserPermission::AllocationUpdate, $server) : $this->adminUpdate($user, $model);
    }

    public function delete(User $user, Model $model): bool
    {
        /** @var ?Server $server */
        $server = Filament::getTenant();

        return $server ? $user->can(SubuserPermission::AllocationDelete, $server) : $this->adminDelete($user, $model);
    }

    public function deleteAny(User $user): bool
    {
        /** @var ?Server $server */
        $server = Filament::getTenant();

        return $server ? $user->can(SubuserPermission::AllocationDelete, $server) : $this->adminDeleteAny($user);
    }
}
