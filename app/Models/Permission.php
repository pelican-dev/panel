<?php

namespace App\Models;

/** @deprecated */
class Permission
{
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
}
