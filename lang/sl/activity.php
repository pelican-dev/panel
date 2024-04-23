<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Neuspešna prijava',
        'success' => 'Prijavljen',
        'password-reset' => 'Ponastavitev gesla',
        'reset-password' => 'Zahtevana ponastavitev gesla',
        'checkpoint' => 'Zahtevano dvostopenjsko preverjanje pristnosti',
        'recovery-token' => 'Uporabljen žeton za dvostopenjsko obnovitev',
        'token' => 'Rešen izziv z dvema faktorjema',
        'ip-blocked' => 'Blokirana zahteva z neprijavljenega naslova IP za :identifier',
        'sftp' => [
            'fail' => 'Neuspela prijava v SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Spremenil e-pošto iz :old v :new',
            'password-changed' => 'Spremenjeno geslo',
        ],
        'api-key' => [
            'create' => 'Ustvarjen nov ključ API :identifier',
            'delete' => 'Izbrisan ključ API :identifier',
        ],
        'ssh-key' => [
            'create' => 'Dodan ključ SSH fingerprint na račun',
            'delete' => 'Odstranjen ključ SSH :fingerprint iz računa',
        ],
        'two-factor' => [
            'create' => 'Omogočeno dvostopenjsko preverjanje pristnosti',
            'delete' => 'Onemogočeno dvostopenjsko preverjanje pristnosti',
        ],
    ],
    'server' => [
        'reinstall' => 'Ponovno nameščen strežnik',
        'console' => [
            'command' => 'Izveden ":command" v strežniku',
        ],
        'power' => [
            'start' => 'Zagon strežnika',
            'stop' => 'Ustavil strežnik',
            'restart' => 'Ponovni zagon strežnika',
            'kill' => 'Ubil proces strežnika',
        ],
        'backup' => [
            'download' => 'Prenesena varnostna kopija :name',
            'delete' => 'Odstranjena varnostna kopija :name',
            'restore' => 'Obnovljena varnostna kopija :name (izbrisane datoteke: :name)',
            'restore-complete' => 'Zaključena obnova varnostne kopije :name',
            'restore-failed' => 'Ni uspelo dokončati obnovitve varnostne kopije :name',
            'start' => 'Začela se je nova varnostna kopija :name',
            'complete' => 'Varnostno kopijo :name označite kot dokončano',
            'fail' => 'Varnostno kopijo :name označite kot neuspešno',
            'lock' => 'Zaklenjena varnostna kopija :name',
            'unlock' => 'Odklepanje varnostne kopije :name',
        ],
        'database' => [
            'create' => 'Ustvarjena nova zbirka podatkov :name',
            'rotate-password' => 'Vrtenje gesla za podatkovno zbirko :name',
            'delete' => 'Izbrisana zbirka podatkov :name',
        ],
        'file' => [
            'compress_one' => 'Stisnjeno :directory:file',
            'compress_other' => 'Stisnjeno :count datotek v :directory',
            'read' => 'Ogledal si je vsebino :file',
            'copy' => 'Ustvarjena kopija datoteke :file',
            'create-directory' => 'Ustvarjen imenik :directory:name',
            'decompress' => 'Dekomprimirane :files v :directory',
            'delete_one' => 'Izbrisano :directory:files.0',
            'delete_other' => 'Izbrisano :count datotek v :directory',
            'download' => 'Preneseno :file',
            'pull' => 'Prenos oddaljene datoteke iz :url v :imenik',
            'rename_one' => 'Preimenovanje :directory:files.0.from v :directory:files.0.to',
            'rename_other' => 'Preimenovanje :count datotek v :directory',
            'write' => 'Zapisal novo vsebino v :file',
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
