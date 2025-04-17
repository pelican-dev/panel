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
        'checkpoint' => 'Two-factor authentication requested',
        'recovery-token' => 'Used two-factor recovery token',
        'token' => 'Solved two-factor challenge',
        'ip-blocked' => 'Blocked request from unlisted IP address for <b>:identifier</b>',
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
            'create' => 'Created new API key <b>:identifier</b>',
            'delete' => 'Deleted API key <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'Added SSH key <b>:fingerprint</b> to account',
            'delete' => 'Removed SSH key <b>:fingerprint</b> from account',
        ],
        'two-factor' => [
            'create' => 'Enabled two-factor auth',
            'delete' => 'Disabled two-factor auth',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Executed "<b>:command</b>" on the server',
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
            'restore' => 'Restored the <b>:name</b> backup (deleted files: <b>:truncate</b>)',
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
            'compress' => 'Compressed <b>:directory:files</b>|Compressed <b>:count</b> files in <b>:directory</b>',
            'read' => 'Viewed the contents of <b>:file</b>',
            'copy' => 'Created a copy of <b>:file</b>',
            'create-directory' => 'Created directory <b>:directory:name</b>',
            'decompress' => 'Decompressed <b>:file</b> in <b>:directory</b>',
            'delete' => 'Deleted <b>:directory:files</b>|Deleted <b>:count</b> files in <b>:directory</b>',
            'download' => 'Downloaded <b>:file</b>',
            'pull' => 'Downloaded a remote file from <b>:url</b> to <b>:directory</b>',
            'rename' => 'Moved/ Renamed <b>:from</b> to <b>:to</b>|Moved/ Renamed <b>:count</b> files in <b>:directory</b>',
            'write' => 'Wrote new content to <b>:file</b>',
            'upload' => 'Began a file upload',
            'uploaded' => 'Uploaded <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'Blocked SFTP access due to permissions',
            'create' => 'Created <b>:files</b>|Created <b>:count</b> new files',
            'write' => 'Modified the contents of <b>:files</b>|Modified the contents of <b>:count</b> files',
            'delete' => 'Deleted <b>:files</b>|Deleted <b>:count</b> files',
            'create-directory' => 'Created the <b>:files</b> directory|Created <b>:count</b> directories',
            'rename' => 'Renamed <b>:from</b> to <b>:to</b>|Renamed or moved <b>:count</b> files',
        ],
        'allocation' => [
            'create' => 'Added <b>:allocation</b> to the server',
            'notes' => 'Updated the notes for <b>:allocation</b> from "<b>:old</b>" to "<b>:new</b>"',
            'primary' => 'Set <b>:allocation</b> as the primary server allocation',
            'delete' => 'Deleted the <b>:allocation</b> allocation',
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
            'delete' => 'Deleted the "<b>:action</b>" task for the <b>:name</b> schedule',
        ],
        'settings' => [
            'rename' => 'Renamed the server from "<b>:old</b>" to "<b>:new</b>"',
            'description' => 'Changed the server description from "<b>:old</b>" to "<b>:new</b>"',
            'reinstall' => 'Reinstalled server',
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
        'crashed' => 'Server crashed',
    ],
];
