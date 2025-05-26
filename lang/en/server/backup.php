<?php

return [
    'limit_reached' => 'Backup limit reached',
    'new_backup' => 'Create Backup',
    'backup_created' => 'Backup Created',
    'backup_failed' => 'Backup failed',
    'list' => [
        'name' => 'Name',
        'ignore' => 'Ignored Files & Directories',
        'lock' => 'Lock?',
        'lock_help' => 'Prevents this backup from being deleted until explicitly unlocked.',
        'size' => 'Size',
        'created' => 'Created',
        'status' => 'Status',
        'lock_status' => 'Lock Status',
        'lockable' => [
            'lock' => 'Lock',
            'unlock' => 'Unlock',
        ],
        'restore_help' => 'Your server will be stopped. You will not be able to control the power state, access the file manager, or create additional backups until this process is completed.',
        'restore_confirm' => 'Delete all files before restoring backup?',
        'restore_failed' => [
            'cannot_restore' => 'Backup Restore Failed',
            'cannot_restore_desc' => 'This server is not currently in a state that allows for a backup to be restored.',
            'restore_incomplete' => 'Backup Restore Failed',
            'restore_incomplete_desc' => 'This backup cannot be restored at this time: not completed or failed.',
        ],
        'restoring' => 'Restoring Backup',
        'delete' => 'Delete Backup',
        'delete_confirm' => 'Do you wish to delete :backup ?',
    ],
];