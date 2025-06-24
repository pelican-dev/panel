<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Traits\HasValidation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Permission extends Model implements Validatable
{
    use HasFactory, HasValidation;

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'subuser_permission';

    /**
     * Constants defining different permissions available.
     */
    public const ACTION_WEBSOCKET_CONNECT = 'websocket.connect';

    public const ACTION_CONTROL_CONSOLE = 'control.console';

    public const ACTION_CONTROL_START = 'control.start';

    public const ACTION_CONTROL_STOP = 'control.stop';

    public const ACTION_CONTROL_RESTART = 'control.restart';

    public const ACTION_DATABASE_READ = 'database.read';

    public const ACTION_DATABASE_CREATE = 'database.create';

    public const ACTION_DATABASE_UPDATE = 'database.update';

    public const ACTION_DATABASE_DELETE = 'database.delete';

    public const ACTION_DATABASE_VIEW_PASSWORD = 'database.view-password';

    public const ACTION_SCHEDULE_READ = 'schedule.read';

    public const ACTION_SCHEDULE_CREATE = 'schedule.create';

    public const ACTION_SCHEDULE_UPDATE = 'schedule.update';

    public const ACTION_SCHEDULE_DELETE = 'schedule.delete';

    public const ACTION_USER_READ = 'user.read';

    public const ACTION_USER_CREATE = 'user.create';

    public const ACTION_USER_UPDATE = 'user.update';

    public const ACTION_USER_DELETE = 'user.delete';

    public const ACTION_BACKUP_READ = 'backup.read';

    public const ACTION_BACKUP_CREATE = 'backup.create';

    public const ACTION_BACKUP_DELETE = 'backup.delete';

    public const ACTION_BACKUP_DOWNLOAD = 'backup.download';

    public const ACTION_BACKUP_RESTORE = 'backup.restore';

    public const ACTION_ALLOCATION_READ = 'allocation.read';

    public const ACTION_ALLOCATION_CREATE = 'allocation.create';

    public const ACTION_ALLOCATION_UPDATE = 'allocation.update';

    public const ACTION_ALLOCATION_DELETE = 'allocation.delete';

    public const ACTION_FILE_READ = 'file.read';

    public const ACTION_FILE_READ_CONTENT = 'file.read-content';

    public const ACTION_FILE_CREATE = 'file.create';

    public const ACTION_FILE_UPDATE = 'file.update';

    public const ACTION_FILE_DELETE = 'file.delete';

    public const ACTION_FILE_ARCHIVE = 'file.archive';

    public const ACTION_FILE_SFTP = 'file.sftp';

    public const ACTION_STARTUP_READ = 'startup.read';

    public const ACTION_STARTUP_UPDATE = 'startup.update';

    public const ACTION_STARTUP_DOCKER_IMAGE = 'startup.docker-image';

    public const ACTION_SETTINGS_RENAME = 'settings.rename';

    public const ACTION_SETTINGS_DESCRIPTION = 'settings.description';

    public const ACTION_SETTINGS_REINSTALL = 'settings.reinstall';

    public const ACTION_ACTIVITY_READ = 'activity.read';

    public $timestamps = false;

    /**
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'subuser_id' => ['required', 'numeric', 'min:1'],
        'permission' => ['required', 'string'],
    ];

    protected function casts(): array
    {
        return [
            'subuser_id' => 'integer',
        ];
    }

    /**
     * All the permissions available on the system.
     *
     * @return array<int, array{
     *      name: string,
     *      icon: string,
     *      permissions: string[]
     *  }>
     */
    public static function permissionData(): array
    {
        return [
            [
                'name' => 'control',
                'icon' => 'tabler-terminal-2',
                'permissions' => ['console', 'start', 'stop', 'restart'],
            ],
            [
                'name' => 'user',
                'icon' => 'tabler-users',
                'permissions' => ['read', 'create', 'update', 'delete'],
            ],
            [
                'name' => 'file',
                'icon' => 'tabler-files',
                'permissions' => ['read', 'read-content', 'create', 'update', 'delete', 'archive', 'sftp'],
            ],
            [
                'name' => 'backup',
                'icon' => 'tabler-file-zip',
                'permissions' => ['read', 'create', 'delete', 'download', 'restore'],
            ],
            [
                'name' => 'allocation',
                'icon' => 'tabler-network',
                'permissions' => ['read', 'create', 'update', 'delete'],
            ],
            [
                'name' => 'startup',
                'icon' => 'tabler-player-play',
                'permissions' => ['read', 'update', 'docker-image'],
            ],
            [
                'name' => 'database',
                'icon' => 'tabler-database',
                'permissions' => ['read', 'create', 'update', 'delete', 'view-password'],
            ],
            [
                'name' => 'schedule',
                'icon' => 'tabler-clock',
                'permissions' => ['read', 'create', 'update', 'delete'],
            ],
            [
                'name' => 'settings',
                'icon' => 'tabler-settings',
                'permissions' => ['rename', 'description', 'reinstall'],
            ],
            [
                'name' => 'activity',
                'icon' => 'tabler-stack',
                'permissions' => ['read'],
            ],
        ];
    }

    /**
     * Returns all the permissions available on the system for a user to have when controlling a server.
     */
    public static function permissions(): Collection
    {
        $permissions = [
            'websocket' => [
                'description' => 'Allows the user to connect to the server websocket, giving them access to view console output and realtime server stats.',
                'keys' => [
                    'connect' => 'Allows a user to connect to the websocket instance for a server to stream the console.',
                ],
            ],
        ];

        foreach (static::permissionData() as $data) {
            $permissions[$data['name']] = [
                'description' => trans('server/users.permissions.' . $data['name'] . '_desc'),
                'keys' => collect($data['permissions'])->mapWithKeys(fn ($key) => [$key => trans('server/users.permissions.' . $data['name'] . '_' . str($key)->replace('-', '_'))])->toArray(),
            ];
        }

        return collect($permissions);
    }
}
