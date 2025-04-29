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

    public const ACTION_DATABASE_VIEW_PASSWORD = 'database.view_password';

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

    /**
     * All the permissions available on the system. Use Permission::permissionTabs() or Permission::permissions()
     *
     * @return array<int, array{
     *      name: string,
     *      description: string,
     *      icon: string,
     *      checkboxList: array{
     *          name: string,
     *          columns: int,
     *          options: array<int, array{
     *              name: string,
     *              label: string,
     *              description: string,
     *          }>
     *      }
     *  }>
     *
     * @see Permission::permissionTabs()
     */
    public static function permissionTabs(): array
    {
        return [
            [
                'name' => 'Console',
                'description' => trans('server/users.permissions.control_desc'),
                'icon' => 'tabler-terminal-2',
                'checkboxList' => [
                    'name' => 'control',
                    'options' => [
                        [
                            'name' => 'Console',
                            'label' => 'console',
                            'description' => trans('server/users.permissions.control_console'),
                        ],
                        [
                            'name' => 'Start',
                            'label' => 'start',
                            'description' => trans('server/users.permissions.control_start'),
                        ],
                        [
                            'name' => 'Stop',
                            'label' => 'stop',
                            'description' => trans('server/users.permissions.control_stop'),
                        ],
                        [
                            'name' => 'Restart',
                            'label' => 'restart',
                            'description' => trans('server/users.permissions.control_restart'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'User',
                'description' => trans('server/users.permissions.user_desc'),
                'icon' => 'tabler-users',
                'checkboxList' => [
                    'name' => 'user',
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.user_read'),
                        ],
                        [
                            'name' => 'Create',
                            'label' => 'create',
                            'description' => trans('server/users.permissions.user_create'),
                        ],
                        [
                            'name' => 'Update',
                            'label' => 'update',
                            'description' => trans('server/users.permissions.user_update'),
                        ],
                        [
                            'name' => 'Delete',
                            'label' => 'delete',
                            'description' => trans('server/users.permissions.user_delete'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'File',
                'description' => trans('server/users.permissions.file_desc'),
                'icon' => 'tabler-folders',
                'checkboxList' => [
                    'name' => 'file',
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.file_create'),
                        ],
                        [
                            'name' => 'Read Content',
                            'label' => 'read-content',
                            'description' => trans('server/users.permissions.file_read_content'),
                        ],
                        [
                            'name' => 'Create',
                            'label' => 'create',
                            'description' => trans('server/users.permissions.backup_create'),
                        ],
                        [
                            'name' => 'Update',
                            'label' => 'update',
                            'description' => trans('server/users.permissions.file_update'),
                        ],
                        [
                            'name' => 'Delete',
                            'label' => 'delete',
                            'description' => trans('server/users.permissions.file_delete'),
                        ],
                        [
                            'name' => 'Archive',
                            'label' => 'archive',
                            'description' => trans('server/users.permissions.file_archive'),
                        ],
                        [
                            'name' => 'SFTP',
                            'label' => 'sftp',
                            'description' => trans('server/users.permissions.file_sftp'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Backup',
                'description' => trans('server/users.permissions.backup_desc'),
                'icon' => 'tabler-download',
                'checkboxList' => [
                    'name' => 'backup',
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.backup_read'),
                        ],
                        [
                            'name' => 'Create',
                            'label' => 'create',
                            'description' => trans('server/users.permissions.backup_create'),
                        ],
                        [
                            'name' => 'Delete',
                            'label' => 'delete',
                            'description' => trans('server/users.permissions.backup_delete'),
                        ],
                        [
                            'name' => 'Download',
                            'label' => 'download',
                            'description' => trans('server/users.permissions.backup_download'),
                        ],
                        [
                            'name' => 'Restore',
                            'label' => 'restore',
                            'description' => trans('server/users.permissions.backup_restore'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Allocation',
                'description' => trans('server/users.permissions.allocation_desc'),
                'icon' => 'tabler-network',
                'checkboxList' => [
                    'name' => 'allocation',
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.allocation_read'),
                        ],
                        [
                            'name' => 'Create',
                            'label' => 'create',
                            'description' => trans('server/users.permissions.allocation_create'),
                        ],
                        [
                            'name' => 'Update',
                            'label' => 'update',
                            'description' => trans('server/users.permissions.allocation_update'),
                        ],
                        [
                            'name' => 'Delete',
                            'label' => 'delete',
                            'description' => trans('server/users.permissions.allocation_delete'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Startup',
                'description' => trans('server/users.permissions.startup_desc'),
                'icon' => 'tabler-question-mark',
                'checkboxList' => [
                    'name' => 'startup',
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.startup_read'),
                        ],
                        [
                            'name' => 'Update',
                            'label' => 'update',
                            'description' => trans('server/users.permissions.startup_update'),
                        ],
                        [
                            'name' => 'Docker Image',
                            'label' => 'docker-image',
                            'description' => trans('server/users.permissions.startup_docker_image'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Database',
                'description' => trans('server/users.permissions.database_desc'),
                'icon' => 'tabler-database',
                'checkboxList' => [
                    'name' => 'database',
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.database_read'),
                        ],
                        [
                            'name' => 'Create',
                            'label' => 'create',
                            'description' => trans('server/users.permissions.database_create'),
                        ],
                        [
                            'name' => 'Update',
                            'label' => 'update',
                            'description' => trans('server/users.permissions.database_update'),
                        ],
                        [
                            'name' => 'Delete',
                            'label' => 'delete',
                            'description' => trans('server/users.permissions.database_delete'),
                        ],
                        [
                            'name' => 'View Password',
                            'label' => 'view_password',
                            'description' => trans('server/users.permissions.database_view_password'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Schedule',
                'description' => trans('server/users.permissions.schedule_desc'),
                'icon' => 'tabler-clock',
                'checkboxList' => [
                    'name' => 'schedule',
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.schedule_read'),
                        ],
                        [
                            'name' => 'Create',
                            'label' => 'create',
                            'description' => trans('server/users.permissions.schedule_create'),
                        ],
                        [
                            'name' => 'Update',
                            'label' => 'update',
                            'description' => trans('server/users.permissions.schedule_update'),
                        ],
                        [
                            'name' => 'Delete',
                            'label' => 'delete',
                            'description' => trans('server/users.permissions.schedule_delete'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Settings',
                'description' => trans('server/users.permissions.settings_desc'),
                'icon' => 'tabler-settings',
                'checkboxList' => [
                    'name' => 'settings',
                    'options' => [
                        [
                            'name' => 'Rename',
                            'label' => 'rename',
                            'description' => trans('server/users.permissions.setting_rename'),
                        ],
                        [
                            'name' => 'Reinstall',
                            'label' => 'reinstall',
                            'description' => trans('server/users.permissions.setting_reinstall'),
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Activity',
                'description' => trans('server/users.permissions.activity_desc'),
                'icon' => 'tabler-stack',
                'checkboxList' => [
                    'name' => 'activity',
                    'options' => [
                        [
                            'name' => 'Read',
                            'label' => 'read',
                            'description' => trans('server/users.permissions.activity_read'),
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Converts Permission::permissionTabs() to Permission::permissions()
     * to retrieve them, and not directly access this array as it is subject to change.
     *
     * @return array<string, array{
     *      description: string,
     *      keys: array<string, string>
     *  }>
     *
     * @see Permission::permissionTabs()
     */
    protected function constructPermissions(): array
    {
        $return = [];
        foreach ($this::permissionTabs() as $permission) {
            $keys = [];
            foreach ($permission['checkboxList']['options'] as $key) {
                $keys[$key['label']] = $key['description'];
            }
            $return[$permission['checkboxList']['name']] = [
                'description' => $permission['description'],
                'keys' => $keys,
            ];
        }

        return $return;
    }

    protected function casts(): array
    {
        return [
            'subuser_id' => 'integer',
        ];
    }

    /**
     * Returns all the permissions available on the system for a user to have when controlling a server.
     */
    public static function permissions(): Collection
    {
        $static_permissions = [
            'websocket' => [
                'description' => 'Allows the user to connect to the server websocket, giving them access to view console output and realtime server stats.',
                'keys' => [
                    'connect' => 'Allows a user to connect to the websocket instance for a server to stream the console.',
                ],
            ],
        ];

        return Collection::make(array_merge($static_permissions, (new Permission())->constructPermissions()));
    }
}
