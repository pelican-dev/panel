<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Innlogging feilet',
        'success' => 'Logget inn',
        'password-reset' => 'Tilbakestill passord',
        'reset-password' => 'Tilbakestilling av passord forespurt',
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
            'email-changed' => 'Changed email from :old to :new',
            'password-changed' => 'Endret passord',
        ],
        'api-key' => [
            'create' => 'Created new API key :identifier',
            'delete' => 'Deleted API key :identifier',
        ],
        'ssh-key' => [
            'create' => 'SSH-nøkkel :fingerprint ble lagt til kontoen',
            'delete' => 'SSH-nøkkel :fingerprint ble fjernet fra kontoen',
        ],
        'two-factor' => [
            'create' => 'Enabled two-factor auth',
            'delete' => 'Disabled two-factor auth',
        ],
    ],
    'server' => [
        'reinstall' => 'Reinstallete server',
        'console' => [
            'command' => 'Utføre ":command" på serveren',
        ],
        'power' => [
            'start' => 'Startet serveren',
            'stop' => 'Stoppet serveren',
            'restart' => 'Restartet serveren',
            'kill' => 'Drepte serverprosessen',
        ],
        'backup' => [
            'download' => 'Lastet ned :name sikkerhetskopi',
            'delete' => 'Sikkerhetskopi :name ble slettet',
            'restore' => 'Restored the :name backup (deleted files: :truncate)',
            'restore-complete' => 'Completed restoration of the :name backup',
            'restore-failed' => 'Failed to complete restoration of the :name backup',
            'start' => 'Started a new backup :name',
            'complete' => 'Marked the :name backup as complete',
            'fail' => 'Marked the :name backup as failed',
            'lock' => 'Locked the :name backup',
            'unlock' => 'Unlocked the :name backup',
        ],
        'database' => [
            'create' => 'Created new database :name',
            'rotate-password' => 'Password rotated for database :name',
            'delete' => 'Slettet databasen :name',
        ],
        'file' => [
            'compress_one' => 'Compressed :directory:file',
            'compress_other' => 'Compressed :count files in :directory',
            'read' => 'Viewed the contents of :file',
            'copy' => 'Opprettet en kopi av :file',
            'create-directory' => 'Created directory :directory:name',
            'decompress' => 'Decompressed :files in :directory',
            'delete_one' => 'Slettet :directory:files.0',
            'delete_other' => 'Deleted :count files in :directory',
            'download' => 'Lastet ned :file',
            'pull' => 'Downloaded a remote file from :url to :directory',
            'rename_one' => 'Renamed :directory:files.0.from to :directory:files.0.to',
            'rename_other' => 'Renamed :count files in :directory',
            'write' => 'Wrote new content to :file',
            'upload' => 'Startet filopplasting',
            'uploaded' => 'Lastet opp :katalog:file',
        ],
        'sftp' => [
            'denied' => 'Tilgang til SFTP er blokkert på grunn av tilgangsstyring',
            'create_one' => 'Opprettet :files.0',
            'create_other' => 'Opprettet :count nye filer',
            'write_one' => 'Modified the contents of :files.0',
            'write_other' => 'Modified the contents of :count files',
            'delete_one' => 'Slettet :files.0',
            'delete_other' => 'Slettet :count filer',
            'create-directory_one' => 'Opprettet :files.0 mappen',
            'create-directory_other' => 'Opprettet :count mapper',
            'rename_one' => 'Endret navn på :files.0.from til :files.0.to',
            'rename_other' => 'Renamed or moved :count files',
        ],
        'allocation' => [
            'create' => 'Added :allocation to the server',
            'notes' => 'Updated the notes for :allocation from ":old" to ":new"',
            'primary' => 'Set :allocation as the primary server allocation',
            'delete' => 'Deleted the :allocation allocation',
        ],
        'schedule' => [
            'create' => 'Created the :name schedule',
            'update' => 'Updated the :name schedule',
            'execute' => 'Manually executed the :name schedule',
            'delete' => 'Deleted the :name schedule',
        ],
        'task' => [
            'create' => 'Created a new ":action" task for the :name schedule',
            'update' => 'Updated the ":action" task for the :name schedule',
            'delete' => 'Deleted a task for the :name schedule',
        ],
        'settings' => [
            'rename' => 'Renamed the server from :old to :new',
            'description' => 'Changed the server description from :old to :new',
        ],
        'startup' => [
            'edit' => 'Changed the :variable variable from ":old" to ":new"',
            'image' => 'Updated the Docker Image for the server from :old to :new',
        ],
        'subuser' => [
            'create' => 'Added :email as a subuser',
            'update' => 'Updated the subuser permissions for :email',
            'delete' => 'Removed :email as a subuser',
        ],
    ],
];
