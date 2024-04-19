<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Log ind mislykkedes',
        'success' => 'Logget ind',
        'password-reset' => 'Nulstil adgangskode',
        'reset-password' => 'Anmodet om nulstilling af adgangskode',
        'checkpoint' => '2-factor godkendelse anmodet',
        'recovery-token' => '2-factor gendannelses-token brugt',
        'token' => 'Løst 2-factor udfordring',
        'ip-blocked' => 'Blokeret anmodning fra ikke-listet IP-adresse for :identifier',
        'sftp' => [
            'fail' => 'SFTP log ind mislykkedes',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Skiftede e-mail fra :old til :new',
            'password-changed' => 'Adgangskode ændret',
        ],
        'api-key' => [
            'create' => 'Ny API-nøgle oprettet :identifier',
            'delete' => 'API-nøgle slettet :identifier',
        ],
        'ssh-key' => [
            'create' => 'SSH-nøgle :fingerprint tilføjet til konto',
            'delete' => 'SSH-nøgle :fingerprint fjernet fra konto',
        ],
        'two-factor' => [
            'create' => '2-factor godkendelse aktiveret',
            'delete' => '2-factor godkendelse deaktiveret',
        ],
    ],
    'server' => [
        'reinstall' => 'Server geninstalleret',
        'console' => [
            'command' => 'Udført ":command" på serveren',
        ],
        'power' => [
            'start' => 'Server startet',
            'stop' => 'Server stoppet',
            'restart' => 'Server genstartet',
            'kill' => 'Dræbte serverprocessen',
        ],
        'backup' => [
            'download' => 'Hentede :name backup',
            'delete' => 'Slettede :name backup',
            'restore' => 'Gendannede :name backup (slettede filer: :truncate)',
            'restore-complete' => 'Genoprettelse af :name backup fuldført',
            'restore-failed' => 'Genoprettelse af :name backup mislykkedes',
            'start' => 'Startede en ny backup :name',
            'complete' => 'Backup :name markeret som fuldført',
            'fail' => 'Markeret :name backup som mislykket',
            'lock' => 'Låst :name backup',
            'unlock' => 'Oplåst :name backup',
        ],
        'database' => [
            'create' => 'Oprettet ny database :name',
            'rotate-password' => 'Adgangskode roteret for database :name',
            'delete' => 'Slettet database :name',
        ],
        'file' => [
            'compress_one' => 'Komprimeret :directory:file',
            'compress_other' => 'Komprimeret :count filer i :directory',
            'read' => 'Indholdet af :file blev set',
            'copy' => 'Kopi af :file oprettet',
            'create-directory' => 'Mappen :directory:name oprettet',
            'decompress' => 'Dekomprimeret :files i :directory',
            'delete_one' => 'Slettede :directory:files.0',
            'delete_other' => 'Slettede :count filer i :directory',
            'download' => 'Hentede :file',
            'pull' => 'Hentede en fjernfil fra :url til :directory',
            'rename_one' => 'Omdøbte :directory:files.0.from til :directory:files.0.to',
            'rename_other' => 'Omdøbte :count filer i :directory',
            'write' => 'Skrev nyt indhold til :file',
            'upload' => 'Begyndte en filoverførsel',
            'uploaded' => 'Uploadet :directory:file',
        ],
        'sftp' => [
            'denied' => 'Blokeret SFTP adgang på grund af tilladelser',
            'create_one' => 'Oprettede :files.0',
            'create_other' => 'Oprettede :count nye filer',
            'write_one' => 'Ændrede indholdet af :files.0',
            'write_other' => 'Ændrede indholdet af :count filer',
            'delete_one' => 'Slettede :files.0',
            'delete_other' => 'Slettede :count filer',
            'create-directory_one' => 'Oprettede mappen :files.0',
            'create-directory_other' => 'Oprettede :count mapper',
            'rename_one' => 'Omdøbte :files.0.from til :files.0.to',
            'rename_other' => 'Omdøbte eller flyttede :count filer',
        ],
        'allocation' => [
            'create' => 'Tilføjede :allocation til serveren',
            'notes' => 'Opdaterede noterne for :allocation fra ":old" til ":new"',
            'primary' => 'Satte :allocation som primær servertildeling',
            'delete' => 'Slettede :allocation tildeling',
        ],
        'schedule' => [
            'create' => 'Oprettede :name tidsplan',
            'update' => 'Opdaterede :name tidsplan',
            'execute' => 'Manuelt udført :name tidsplan',
            'delete' => 'Slettede :name tidsplan',
        ],
        'task' => [
            'create' => 'Oprettede en ny ":action" opgave for :name tidsplan',
            'update' => 'Opdaterede ":action" opgaven for :name tidsplan',
            'delete' => 'Slettede en opgave for :name tidsplan',
        ],
        'settings' => [
            'rename' => 'Omdøbte serveren fra :old til :new',
            'description' => 'Skiftede server beskrivelse fra :old til :new',
        ],
        'startup' => [
            'edit' => 'Skiftede :variable variabel fra ":old" til ":new"',
            'image' => 'Opdaterede Docker Image for serveren fra :old til :new',
        ],
        'subuser' => [
            'create' => 'Tilføjede :email som en underbruger',
            'update' => 'Opdaterede underbruger rettighederne for :email',
            'delete' => 'Fjernede :email som underbruger',
        ],
    ],
];
