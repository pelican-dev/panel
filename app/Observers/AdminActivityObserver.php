<?php

namespace App\Observers;

use App\Facades\Activity;
use App\Models\Egg;
use App\Models\Node;
use App\Models\Role;
use App\Models\Server;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class AdminActivityObserver
{
    /**
     * Tracks events already logged in this request to avoid duplicates.
     *
     * @var array<string, bool>
     */
    private static array $logged = [];

    /**
     * Determines if the current request is being handled by the admin panel.
     */
    private function isAdminPanel(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'admin';
    }

    /**
     * Logs an admin activity event for the given model, deduplicating within a single request.
     *
     * @param  string  $event  The event name (e.g. 'admin:user.create')
     * @param  Model  $model  The model being acted upon
     * @param  array<string, mixed>  $properties  Additional properties to log
     *
     * @throws \Throwable
     */
    private function log(string $event, Model $model, array $properties = []): void
    {
        if (!$this->isAdminPanel()) {
            return;
        }

        $actor = user();
        if (!$actor) {
            return;
        }

        // Deduplicate identical events for the same record within a single request.
        $key = $event . ':' . get_class($model) . ':' . $model->getKey();
        if (isset(self::$logged[$key])) {
            return;
        }
        self::$logged[$key] = true;

        $log = Activity::event($event)
            ->actor($actor)
            ->subject($model);

        foreach ($properties as $propKey => $propValue) {
            $log->property($propKey, $propValue);
        }

        $log->log();
    }
    // -------------------------------------------------------------------------
    // User events
    // -------------------------------------------------------------------------

    public function userCreated(User $user): void
    {
        $this->log('admin:user.create', $user, ['name' => $user->username]);
    }

    public function userUpdated(User $user): void
    {
        $changedFields = $this->changedFieldsFor($user);

        $this->log('admin:user.update', $user, [
            'name' => empty($changedFields) ? $user->username : sprintf('%s (%s)', $user->username, implode(', ', $changedFields)),
            'count' => count($changedFields),
            'changes' => implode(', ', $changedFields),
        ]);
    }

    public function userDeleted(User $user): void
    {
        $this->log('admin:user.delete', $user, ['name' => $user->username]);
    }

    // -------------------------------------------------------------------------
    // Server events
    // -------------------------------------------------------------------------

    public function serverCreated(Server $server): void
    {
        $this->log('admin:server.create', $server, ['name' => $server->name]);
    }

    public function serverUpdated(Server $server): void
    {
        $changedFields = $this->changedFieldsFor($server);

        $this->log('admin:server.update', $server, [
            'name' => empty($changedFields) ? $server->name : sprintf('%s (%s)', $server->name, implode(', ', $changedFields)),
            'count' => count($changedFields),
            'changes' => implode(', ', $changedFields),
        ]);
    }

    public function serverDeleted(Server $server): void
    {
        $this->log('admin:server.delete', $server, ['name' => $server->name]);
    }

    // -------------------------------------------------------------------------
    // Node events
    // -------------------------------------------------------------------------

    public function nodeCreated(Node $node): void
    {
        $this->log('admin:node.create', $node, ['name' => $node->name]);
    }

    public function nodeUpdated(Node $node): void
    {
        $changedFields = $this->changedFieldsFor($node);

        $this->log('admin:node.update', $node, [
            'name' => empty($changedFields) ? $node->name : sprintf('%s (%s)', $node->name, implode(', ', $changedFields)),
            'count' => count($changedFields),
            'changes' => implode(', ', $changedFields),
        ]);
    }

    public function nodeDeleted(Node $node): void
    {
        $this->log('admin:node.delete', $node, ['name' => $node->name]);
    }

    // -------------------------------------------------------------------------
    // Egg events
    // -------------------------------------------------------------------------

    public function eggCreated(Egg $egg): void
    {
        $this->log('admin:egg.create', $egg, ['name' => $egg->name]);
    }

    public function eggUpdated(Egg $egg): void
    {
        $changedFields = $this->changedFieldsFor($egg);

        $this->log('admin:egg.update', $egg, [
            'name' => empty($changedFields) ? $egg->name : sprintf('%s (%s)', $egg->name, implode(', ', $changedFields)),
            'count' => count($changedFields),
            'changes' => implode(', ', $changedFields),
        ]);
    }

    public function eggDeleted(Egg $egg): void
    {
        $this->log('admin:egg.delete', $egg, ['name' => $egg->name]);
    }

    // -------------------------------------------------------------------------
    // Role events
    // -------------------------------------------------------------------------

    public function roleCreated(Role $role): void
    {
        $this->log('admin:role.create', $role, ['name' => $role->name]);
    }

    public function roleUpdated(Role $role): void
    {
        $changedFields = $this->changedFieldsFor($role);

        $this->log('admin:role.update', $role, [
            'name' => empty($changedFields) ? $role->name : sprintf('%s (%s)', $role->name, implode(', ', $changedFields)),
            'count' => count($changedFields),
            'changes' => implode(', ', $changedFields),
        ]);
    }

    public function roleDeleted(Role $role): void
    {
        $this->log('admin:role.delete', $role, ['name' => $role->name]);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Returns the sorted list of attribute names that changed on the given model,
     * excluding internal timestamps.
     *
     * @return string[]
     */
    private function changedFieldsFor(Model $model): array
    {
        $fields = collect(array_keys($model->getChanges()))
            ->reject(fn (string $field) => $field === 'updated_at')
            ->values()
            ->all();

        sort($fields);

        return $fields;
    }
}
