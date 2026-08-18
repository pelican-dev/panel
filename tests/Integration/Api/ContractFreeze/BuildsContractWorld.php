<?php

namespace App\Tests\Integration\Api\ContractFreeze;

use App\Models\ActivityLog;
use App\Models\Allocation;
use App\Models\ApiKey;
use App\Models\Backup;
use App\Models\BackupHost;
use App\Models\Database;
use App\Models\DatabaseHost;
use App\Models\Egg;
use App\Models\EggVariable;
use App\Models\Mount;
use App\Models\Node;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Server;
use App\Models\ServerVariable;
use App\Models\Subuser;
use App\Models\Task;
use App\Models\User;
use App\Models\UserSSHKey;
use App\Services\Acl\Api\AdminAcl;
use Spatie\Permission\Models\Permission;

/**
 * Builds one canonical, fully pinned model graph for the contract freeze suite. Every
 * attribute that surfaces in an API response is set explicitly so consecutive runs,
 * parallel workers, and CI all produce identical JSON. Nothing here relies on the
 * seeded eggs, whose import is allowed to fail silently in the base TestCase.
 *
 * These tests only run against sqlite (see the contract-freeze group exclusion in CI),
 * so creating rows with explicit ids is safe.
 */
trait BuildsContractWorld
{
    protected User $contractAdmin;

    protected ApiKey $contractAdminKey;

    protected User $contractOwner;

    protected ApiKey $contractOwnerKey;

    protected User $contractSubuser;

    protected ApiKey $contractSubuserKey;

    protected Role $supportRole;

    protected Node $contractNode;

    protected Allocation $primaryAllocation;

    protected Allocation $secondaryAllocation;

    protected Allocation $unassignedAllocation;

    protected Egg $contractEgg;

    protected EggVariable $visibleVariable;

    protected EggVariable $hiddenVariable;

    protected Server $contractServer;

    protected DatabaseHost $contractDatabaseHost;

    protected Database $contractDatabase;

    protected Mount $contractMount;

    protected Backup $contractBackup;

    protected Schedule $contractSchedule;

    protected Task $contractTask;

    protected UserSSHKey $contractSshKey;

    protected Subuser $contractSubuserPivot;

    protected ActivityLog $contractActivityLog;

    protected ActivityLog $contractAccountActivityLog;

    protected function buildContractWorld(): void
    {
        $this->contractAdmin = User::factory()->create([
            'id' => 100,
            'uuid' => '00000000-0000-4000-8000-000000000100',
            'username' => 'contract.admin',
            'email' => 'admin@contract.test',
        ]);
        $this->contractAdmin->syncRoles(Role::getRootAdmin());

        $this->contractOwner = User::factory()->create([
            'id' => 101,
            'uuid' => '00000000-0000-4000-8000-000000000101',
            'username' => 'contract.owner',
            'email' => 'owner@contract.test',
            'external_id' => 'contract-owner-ext',
        ]);

        $this->contractSubuser = User::factory()->create([
            'id' => 102,
            'uuid' => '00000000-0000-4000-8000-000000000102',
            'username' => 'contract.subuser',
            'email' => 'subuser@contract.test',
        ]);

        // Role and Permission guard their id column, so these land on the next
        // autoincrement values; sqlite truncation resets those, keeping them stable.
        $this->supportRole = Role::create(['name' => 'Support']);
        $this->supportRole->givePermissionTo(Permission::create(['name' => 'viewList user']));
        $this->contractOwner->syncRoles($this->supportRole);

        $this->contractAdminKey = ApiKey::factory()->create([
            'id' => 100,
            'user_id' => $this->contractAdmin->id,
            'key_type' => ApiKey::TYPE_APPLICATION,
            'identifier' => 'papp_contract001',
            'token' => 'contractfreezeapplicationtoken01',
            'memo' => 'Contract freeze application key',
            'permissions' => [
                Server::RESOURCE_NAME => AdminAcl::READ | AdminAcl::WRITE,
                Node::RESOURCE_NAME => AdminAcl::READ | AdminAcl::WRITE,
                Allocation::RESOURCE_NAME => AdminAcl::READ | AdminAcl::WRITE,
                User::RESOURCE_NAME => AdminAcl::READ | AdminAcl::WRITE,
                Egg::RESOURCE_NAME => AdminAcl::READ | AdminAcl::WRITE,
                DatabaseHost::RESOURCE_NAME => AdminAcl::READ | AdminAcl::WRITE,
                Database::RESOURCE_NAME => AdminAcl::READ | AdminAcl::WRITE,
                Mount::RESOURCE_NAME => AdminAcl::READ | AdminAcl::WRITE,
                Role::RESOURCE_NAME => AdminAcl::READ | AdminAcl::WRITE,
            ],
        ]);

        $this->contractOwnerKey = ApiKey::factory()->create([
            'id' => 101,
            'user_id' => $this->contractOwner->id,
            'key_type' => ApiKey::TYPE_ACCOUNT,
            'identifier' => 'pacc_contract001',
            'token' => 'contractfreezeaccounttoken000001',
            'memo' => 'Contract freeze account key',
        ]);

        $this->contractSubuserKey = ApiKey::factory()->create([
            'id' => 102,
            'user_id' => $this->contractSubuser->id,
            'key_type' => ApiKey::TYPE_ACCOUNT,
            'identifier' => 'pacc_contract002',
            'token' => 'contractfreezeaccounttoken000002',
            'memo' => 'Contract freeze subuser key',
        ]);

        $this->contractNode = Node::factory()->create([
            'id' => 100,
            'name' => 'contract-node',
            'fqdn' => 'node.contract.test',
            'tags' => ['contract'],
        ]);

        // Node::creating always regenerates the uuid and daemon tokens, so the pinned
        // values have to be restored after the fact; saveQuietly keeps the observer out
        // of the way while the encrypted cast still applies to the token.
        $this->contractNode->forceFill([
            'uuid' => '00000000-0000-4000-8000-000000000200',
            'daemon_token_id' => 'contractnodetoken',
            'daemon_token' => 'contractfreezenodedaemontokenvalue000001',
        ])->saveQuietly();

        $this->primaryAllocation = Allocation::factory()->create([
            'id' => 100,
            'node_id' => $this->contractNode->id,
            'ip' => '192.0.2.10',
            'port' => 25565,
            'notes' => null,
        ]);

        $this->secondaryAllocation = Allocation::factory()->create([
            'id' => 101,
            'node_id' => $this->contractNode->id,
            'ip' => '192.0.2.10',
            'port' => 25566,
            'notes' => 'Secondary allocation',
        ]);

        $this->unassignedAllocation = Allocation::factory()->create([
            'id' => 102,
            'node_id' => $this->contractNode->id,
            'ip' => '192.0.2.10',
            'port' => 25567,
            'notes' => null,
        ]);

        $this->contractEgg = Egg::factory()->create([
            'id' => 100,
            'uuid' => '00000000-0000-4000-8000-000000000300',
            'name' => 'Contract Egg',
            'author' => 'egg@contract.test',
            'description' => 'A pinned egg for the contract freeze suite.',
            'docker_images' => ['Java 21' => 'ghcr.io/contract/java:21'],
            'startup_commands' => ['java -jar server.jar'],
        ]);

        $this->visibleVariable = EggVariable::factory()->create([
            'id' => 100,
            'egg_id' => $this->contractEgg->id,
            'name' => 'Server Jar',
            'description' => 'The jar file to run.',
            'env_variable' => 'SERVER_JARFILE',
            'default_value' => 'server.jar',
            'user_viewable' => true,
            'user_editable' => true,
            'rules' => ['required', 'string'],
        ]);

        $this->hiddenVariable = EggVariable::factory()->create([
            'id' => 101,
            'egg_id' => $this->contractEgg->id,
            'name' => 'Hidden Token',
            'description' => 'An internal variable users cannot see.',
            'env_variable' => 'HIDDEN_TOKEN',
            'default_value' => 'internal',
            'user_viewable' => false,
            'user_editable' => false,
            'rules' => ['required', 'string'],
        ]);

        $this->contractServer = Server::factory()->create([
            'id' => 100,
            'uuid' => '00000000-0000-4000-8000-000000000400',
            'uuid_short' => 'contract',
            'external_id' => 'contract-external-1',
            'name' => 'Contract Server',
            'description' => 'A pinned server for the contract freeze suite.',
            'owner_id' => $this->contractOwner->id,
            'node_id' => $this->contractNode->id,
            'allocation_id' => $this->primaryAllocation->id,
            'egg_id' => $this->contractEgg->id,
            'startup' => 'java -jar {{SERVER_JARFILE}}',
            'image' => 'ghcr.io/contract/java:21',
        ]);

        Allocation::query()->whereIn('id', [$this->primaryAllocation->id, $this->secondaryAllocation->id])
            ->update(['server_id' => $this->contractServer->id]);

        ServerVariable::query()->forceCreate([
            'id' => 100,
            'server_id' => $this->contractServer->id,
            'variable_id' => $this->visibleVariable->id,
            'variable_value' => 'custom.jar',
        ]);

        ServerVariable::query()->forceCreate([
            'id' => 101,
            'server_id' => $this->contractServer->id,
            'variable_id' => $this->hiddenVariable->id,
            'variable_value' => 'internal',
        ]);

        $this->contractSubuserPivot = Subuser::query()->forceCreate([
            'id' => 100,
            'user_id' => $this->contractSubuser->id,
            'server_id' => $this->contractServer->id,
            'permissions' => ['control.console', 'control.start', 'websocket.connect'],
        ]);

        $this->contractDatabaseHost = DatabaseHost::factory()->create([
            'id' => 100,
            'name' => 'contract-database-host',
            'host' => '192.0.2.20',
            'port' => 3306,
            'username' => 'contract',
            'password' => 'contract-host-password',
        ]);
        $this->contractDatabaseHost->nodes()->sync([$this->contractNode->id]);

        $this->contractDatabase = Database::factory()->create([
            'id' => 100,
            'server_id' => $this->contractServer->id,
            'database_host_id' => $this->contractDatabaseHost->id,
            'database' => 's100_contract',
            'username' => 'u100_contract',
            'password' => 'contract-database-password',
            'remote' => '%',
        ]);

        $this->contractMount = Mount::query()->forceCreate([
            'id' => 100,
            'uuid' => '00000000-0000-4000-8000-000000000500',
            'name' => 'contract-mount',
            'description' => 'A pinned mount for the contract freeze suite.',
            'source' => '/srv/contract',
            'target' => '/mnt/contract',
            'read_only' => true,
            'user_mountable' => false,
        ]);
        $this->contractMount->eggs()->sync([$this->contractEgg->id]);
        $this->contractMount->nodes()->sync([$this->contractNode->id]);
        $this->contractMount->servers()->sync([$this->contractServer->id]);

        $backupHost = BackupHost::factory()->create([
            'id' => 100,
            'name' => 'contract-backup-host',
        ]);

        $this->contractBackup = Backup::factory()->create([
            'id' => 100,
            'server_id' => $this->contractServer->id,
            'backup_host_id' => $backupHost->id,
            'uuid' => '00000000-0000-4000-8000-000000000600',
            'name' => 'Contract Backup',
            'bytes' => 1024,
            'is_locked' => false,
        ]);

        $this->contractSchedule = Schedule::query()->forceCreate([
            'id' => 100,
            'server_id' => $this->contractServer->id,
            'name' => 'Contract Schedule',
            'cron_day_of_week' => '*',
            'cron_month' => '*',
            'cron_day_of_month' => '*',
            'cron_hour' => '0',
            'cron_minute' => '0',
            'is_active' => true,
            'is_processing' => false,
            'only_when_online' => false,
            'next_run_at' => '2026-01-02 00:00:00',
        ]);

        $this->contractTask = Task::factory()->create([
            'id' => 100,
            'schedule_id' => $this->contractSchedule->id,
            'sequence_id' => 1,
            'action' => 'command',
            'payload' => 'say restarting soon',
            'time_offset' => 0,
        ]);

        $this->contractSshKey = UserSSHKey::factory()->create([
            'id' => 100,
            'user_id' => $this->contractOwner->id,
            'name' => 'Contract SSH Key',
        ]);

        $this->contractActivityLog = ActivityLog::query()->forceCreate([
            'id' => 100,
            'event' => 'server:power.start',
            'ip' => '192.0.2.1',
            'description' => null,
            'actor_type' => 'user',
            'actor_id' => $this->contractOwner->id,
            'api_key_id' => null,
            'properties' => [],
            'timestamp' => now(),
        ]);
        $this->contractActivityLog->subjects()->forceCreate([
            'subject_type' => 'server',
            'subject_id' => $this->contractServer->id,
        ]);

        // The account activity endpoint lists logs whose subject is the user, so it
        // needs its own entry; the server-subject log above never shows up there.
        $this->contractAccountActivityLog = ActivityLog::query()->forceCreate([
            'id' => 101,
            'event' => 'user:account.email-changed',
            'ip' => '192.0.2.1',
            'description' => null,
            'actor_type' => 'user',
            'actor_id' => $this->contractOwner->id,
            'api_key_id' => null,
            'properties' => [],
            'timestamp' => now(),
        ]);
        $this->contractAccountActivityLog->subjects()->forceCreate([
            'subject_type' => 'user',
            'subject_id' => $this->contractOwner->id,
        ]);
    }

    protected function actingAsContractAdmin(): static
    {
        return $this
            ->withHeader('Accept', 'application/vnd.panel.v1+json')
            ->withHeader('Authorization', 'Bearer ' . $this->contractAdminKey->identifier . 'contractfreezeapplicationtoken01');
    }

    protected function actingAsContractOwner(): static
    {
        return $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Authorization', 'Bearer ' . $this->contractOwnerKey->identifier . 'contractfreezeaccounttoken000001');
    }

    protected function actingAsContractSubuser(): static
    {
        return $this
            ->withHeader('Accept', 'application/json')
            ->withHeader('Authorization', 'Bearer ' . $this->contractSubuserKey->identifier . 'contractfreezeaccounttoken000002');
    }
}
