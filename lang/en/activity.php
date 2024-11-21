<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Failed log in',
        'success' => 'Logged in',
        'password-reset' => 'Password reset',
        'reset-password' => 'Requested password reset',
        'checkpoint' => 'Two-factor authentication requested',
        'recovery-token' => 'Used two-factor recovery token',
        'token' => 'Solved two-factor challenge',
        'ip-blocked' => 'Blocked request from unlisted IP address for :identifier',
        'sftp' => [
            'fail' => 'Failed SFTP log in',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Changed email from <b>:old</b> to <b>:new</b>',
            'password-changed' => 'Changed password',
        ],
        'api-key' => [
            'create' => 'Created new API key :identifier',
            'delete' => 'Deleted API key :identifier',
        ],
        'ssh-key' => [
            'create' => 'Added SSH key :fingerprint to account',
            'delete' => 'Removed SSH key :fingerprint from account',
        ],
        'two-factor' => [
            'create' => 'Enabled two-factor auth',
            'delete' => 'Disabled two-factor auth',
        ],
    ],
    'server' => [
        'reinstall' => 'Reinstalled server',
        'console' => [
            'command' => 'Executed ":command" on the server',
        ],
        'power' => [
            'start' => 'Started the server',
            'stop' => 'Stopped the server',
            'restart' => 'Restarted the server',
            'kill' => 'Killed the server process',
        ],
        'backup' => [
            'download' => 'Downloaded the <b>:name</b> backup',
            'delete' => 'Deleted the <b>:name</b> backup',
            'restore' => 'Restored the <b>:name</b> backup (deleted files: :truncate)',
            'restore-complete' => 'Completed restoration of the <b>:name</b> backup',
            'restore-failed' => 'Failed to complete restoration of the <b>:name</b> backup',
            'start' => 'Started a new backup <b>:name</b>',
            'complete' => 'Marked the <b>:name</b> backup as complete',
            'fail' => 'Marked the <b>:name</b> backup as failed',
            'lock' => 'Locked the <b>:name</b> backup',
            'unlock' => 'Unlocked the <b>:name</b> backup',
        ],
        'database' => [
            'create' => 'Created new database <b>:name</b>',
            'rotate-password' => 'Password rotated for database <b>:name</b>',
            'delete' => 'Deleted database <b>:name</b>',
        ],
        'file' => [
            'compress_one' => 'Compressed :directory:file',
            'compress_other' => 'Compressed :count files in :directory',
            'read' => 'Viewed the contents of :file',
            'copy' => 'Created a copy of :file',
            'create-directory' => 'Created directory :directory<b>:name</b>',
            'decompress' => 'Decompressed :files in :directory',
            'delete_one' => 'Deleted :directory:files.0',
            'delete_other' => 'Deleted :count files in :directory',
            'download' => 'Downloaded :file',
            'pull' => 'Downloaded a remote file from :url to :directory',
            'rename_one' => 'Renamed :directory:files.0.from to :directory:files.0.to',
            'rename_other' => 'Renamed :count files in :directory',
            'write' => 'Wrote new content to :file',
            'upload' => 'Began a file upload',
            'uploaded' => 'Uploaded :directory:file',
        ],
        'sftp' => [
            'denied' => 'Blocked SFTP access due to permissions',
            'create_one' => 'Created :files.0',
            'create_other' => 'Created :count new files',
            'write_one' => 'Modified the contents of :files.0',
            'write_other' => 'Modified the contents of :count files',
            'delete_one' => 'Deleted :files.0',
            'delete_other' => 'Deleted :count files',
            'create-directory_one' => 'Created the :files.0 directory',
            'create-directory_other' => 'Created :count directories',
            'rename_one' => 'Renamed :files.0.from to :files.0.to',
            'rename_other' => 'Renamed or moved :count files',
        ],
        'allocation' => [
            'create' => 'Added :allocation to the server',
            'notes' => 'Updated the notes for :allocation from "<b>:old</b>" to "<b>:new</b>"',
            'primary' => 'Set :allocation as the primary server allocation',
            'delete' => 'Deleted the :allocation allocation',
        ],
        'schedule' => [
            'create' => 'Created the <b>:name</b> schedule',
            'update' => 'Updated the <b>:name</b> schedule',
            'execute' => 'Manually executed the <b>:name</b> schedule',
            'delete' => 'Deleted the <b>:name</b> schedule',
        ],
        'task' => [
            'create' => 'Created a new "<b>:action</b>" task for the <b>:name</b> schedule',
            'update' => 'Updated the "<b>:action</b>" task for the <b>:name</b> schedule',
            'delete' => 'Deleted a task for the <b>:name</b> schedule',
        ],
        'settings' => [
            'rename' => 'Renamed the server from <b>:old</b> to <b>:new</b>',
            'description' => 'Changed the server description from <b>:old</b> to <b>:new</b>',
        ],
        'startup' => [
            'edit' => 'Changed the <b>:variable</b> variable from "<b>:old</b>" to "<b>:new</b>"',
            'image' => 'Updated the Docker Image for the server from <b>:old</b> to <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'Added <b>:email</b> as a subuser',
            'update' => 'Updated the subuser permissions for <b>:email</b>',
            'delete' => 'Removed <b>:email</b> as a subuser',
        ],
    ],
];
