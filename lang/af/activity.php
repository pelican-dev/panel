<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Kon nie aanmeld nie',
        'success' => 'Aangemeld',
        'password-reset' => 'Wagwoord herstel',
        'reset-password' => 'Versoek wagwoordterugstelling',
        'checkpoint' => 'Twee-faktor-stawing versoek',
        'recovery-token' => 'Gebruik twee-faktor-hersteltoken',
        'token' => 'Twee-faktor uitdaging opgelos',
        'ip-blocked' => 'Geblokkeerde versoek van ongelyste IP-adres vir :identifier',
        'sftp' => [
            'fail' => 'Kon nie SFTP aanmeld nie',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'E-pos verander van :oud na :nuut',
            'password-changed' => 'Verander wagwoord',
        ],
        'api-key' => [
            'create' => 'Skep nuwe API-sleutel:identifiseerder',
            'delete' => 'Geskrap API-sleutel:identifiseerder',
        ],
        'ssh-key' => [
            'create' => 'SSH-sleutel :vingerafdruk by rekening gevoeg',
            'delete' => 'SSH-sleutel :vingerafdruk van rekening verwyder',
        ],
        'two-factor' => [
            'create' => 'Geaktiveerde twee-faktor-autagtiging',
            'delete' => 'Gedeaktiveerde twee-faktor-aut',
        ],
    ],
    'server' => [
        'reinstall' => 'Herinstalleer bediener',
        'console' => [
            'command' => '":opdrag" op die bediener uitgevoer',
        ],
        'power' => [
            'start' => 'Het die bediener begin',
            'stop' => 'Het die bediener gestop',
            'restart' => 'Het die bediener herbegin',
            'kill' => 'Het die bedienerproses doodgemaak',
        ],
        'backup' => [
            'download' => 'Het die :name rugsteun afgelaai',
            'delete' => 'Het die :name rugsteun uitgevee',
            'restore' => 'Het die :name-rugsteun herstel (geskrap lêers: :truncate)',
            'restore-complete' => 'Voltooide herstel van die :name rugsteun',
            'restore-failed' => 'Kon nie die herstel van die :name rugsteun voltooi nie',
            'start' => 'Het \'n nuwe rugsteun :name begin',
            'complete' => 'Het die :name-rugsteun as voltooi gemerk',
            'fail' => 'Het die :name-rugsteun as voltooi gemerk',
            'lock' => 'Het die :name rugsteun uitgevee',
            'unlock' => 'Het die :name rugsteun afgelaai',
        ],
        'database' => [
            'create' => 'Create new database file',
            'rotate-password' => 'Wagwoord geroteer vir databasis :naam',
            'delete' => 'Geskrap databasis :naam',
        ],
        'file' => [
            'compress_one' => 'Saamgeperste :directory:lêer',
            'compress_other' => 'Saamgeperste :count lêers in :directory',
            'read' => 'Het die inhoud van :file bekyk',
            'copy' => 'Het \'n kopie van :file geskep',
            'create-directory' => 'Geskep gids :gids:naam',
            'decompress' => 'Gedekomprimeerde :lêers in :directory',
            'delete_one' => 'Geskrap :gids:lêers.0',
            'delete_other' => 'Saamgeperste :count lêers in :directory',
            'download' => 'Afgelaai: lêer',
            'pull' => 'Het \'n afstandlêer afgelaai vanaf :url na :directory',
            'rename_one' => 'Hernoem :gids:lêers.0.van na :gids:lêers.0.na',
            'rename_other' => 'Hernoem :count lêers in :directory',
            'write' => 'Het nuwe inhoud na :file geskryf',
            'upload' => 'Het \'n lêeroplaai begin',
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
