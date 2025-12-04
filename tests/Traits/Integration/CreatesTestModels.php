<?php

namespace App\Tests\Traits\Integration;

use App\Enums\SubuserPermission;
use App\Models\Allocation;
use App\Models\Egg;
use App\Models\Node;
use App\Models\Server;
use App\Models\Subuser;
use App\Models\User;
use Ramsey\Uuid\Uuid;

trait CreatesTestModels
{
    /**
     * Creates a server model in the databases for the purpose of testing. If an attribute
     * is passed in that normally requires this function to create a model no model will be
     * created and that attribute's value will be used.
     *
     * The returned server model will have all the relationships loaded onto it.
     */
    public function createServerModel(array $attributes = []): Server
    {
        if (isset($attributes['user_id'])) {
            $attributes['owner_id'] = $attributes['user_id'];
        }

        if (!isset($attributes['owner_id'])) {
            /** @var \App\Models\User $user */
            $user = User::factory()->create();
            $attributes['owner_id'] = $user->id;
        }

        if (!isset($attributes['node_id'])) {
            /** @var \App\Models\Node $node */
            $node = Node::factory()->create();
            $attributes['node_id'] = $node->id;
        }

        if (!isset($attributes['allocation_id'])) {
            /** @var \App\Models\Allocation $allocation */
            $allocation = Allocation::factory()->create(['node_id' => $attributes['node_id']]);
            $attributes['allocation_id'] = $allocation->id;
        }

        if (empty($attributes['egg_id'])) {
            $egg = $this->getBungeecordEgg();

            $attributes['egg_id'] = $egg->id;
        }

        unset($attributes['user_id']);

        /** @var \App\Models\Server $server */
        $server = Server::factory()->create($attributes);

        Allocation::query()->where('id', $server->allocation_id)->update(['server_id' => $server->id]);

        return $server->fresh([
            'user', 'node', 'allocation', 'egg',
        ]);
    }

    /**
     * Generates a user and a server for that user. If an array of permissions is passed it
     * is assumed that the user is actually a subuser of the server.
     *
     * @param  array<string|SubuserPermission>  $permissions
     * @return array{\App\Models\User, \App\Models\Server}
     */
    public function generateTestAccount(array $permissions = []): array
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        if (empty($permissions)) {
            return [$user, $this->createServerModel(['user_id' => $user->id])];
        }

        $server = $this->createServerModel();

        Subuser::query()->create([
            'user_id' => $user->id,
            'server_id' => $server->id,
            'permissions' => array_map(fn ($permission) => $permission instanceof SubuserPermission ? $permission->value : $permission, $permissions),
        ]);

        return [$user, $server];
    }

    /**
     * Clones a given egg allowing us to make modifications that don't affect other
     * tests that rely on the egg existing in the correct state.
     */
    protected function cloneEggAndVariables(Egg $egg): Egg
    {
        $model = $egg->replicate(['id', 'uuid']);
        $model->uuid = Uuid::uuid4()->toString();
        $model->push();

        /** @var \App\Models\Egg $model */
        $model = $model->fresh();

        foreach ($egg->variables as $variable) {
            $variable->replicate(['id', 'egg_id'])->forceFill(['egg_id' => $model->id])->push();
        }

        return $model->fresh();
    }

    /**
     * Almost every test just assumes it is using BungeeCord — this is the critical
     * egg model for all tests unless specified otherwise.
     */
    private function getBungeecordEgg(): Egg
    {
        /** @var \App\Models\Egg $egg */
        $egg = Egg::query()->where('author', 'panel@example.com')->where('name', 'Bungeecord')->firstOrFail();

        return $egg;
    }
}
