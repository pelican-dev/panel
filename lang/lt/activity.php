<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Nepavyko prisijungti',
        'success' => 'Prisijungta',
        'password-reset' => 'Slaptažodžio atkūrimas',
        'reset-password' => 'Paprašyta pakeisti slaptažodį iš naujo',
        'checkpoint' => 'Prašyta Dviejų faktorių autentifikacija',
        'recovery-token' => 'Naudota dviejų faktorių atgavimo tokeną',
        'token' => 'Įvykdytas dviejų faktorių iššūkis',
        'ip-blocked' => 'Užblokuota užklausa iš neįtraukto į sąrašą IP adreso :identifier',
        'sftp' => [
            'fail' => 'Nepavyko prisijungti prie SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Pakeitė el. pašto adresas iš :old į :new',
            'password-changed' => 'Pakeitė slaptažodį',
        ],
        'api-key' => [
            'create' => 'Sukurtė naują API raktą :identifier',
            'delete' => 'Ištrinė API raktą :identifier',
        ],
        'ssh-key' => [
            'create' => 'Pridėjo SSH raktą :fingerprint: prie paskyros',
            'delete' => 'Ištrynė SSH raktą :fingerprint: iš paskyros',
        ],
        'two-factor' => [
            'create' => 'Įjungė dviejų faktorių autentifikaciją',
            'delete' => 'Išjungė dviejų faktorių autentifikaciją',
        ],
    ],
    'server' => [
        'reinstall' => 'Perinstaliavo serverį',
        'console' => [
            'command' => 'Serveryje įvykdė komandą ":command"',
        ],
        'power' => [
            'start' => 'Įjungė serverį',
            'stop' => 'Sustabdė serverį',
            'restart' => 'Perkrovė serverį',
            'kill' => 'Nužudė serverio procesą',
        ],
        'backup' => [
            'download' => 'Persisiuntė :name atsarginę kopiją',
            'delete' => 'Ištrynė :name atsarginę kopiją',
            'restore' => 'Atkūrė :name atsarginę kopiją(ištrinti failai: :truncate)',
            'restore-complete' => 'Pabaigė restoraciją atsarginės kopijos :name',
            'restore-failed' => 'Nepavyko pabaigti :name atsarginės kopijos restauracijos',
            'start' => 'Pradėjo naują atsarginę kopiją :name',
            'complete' => 'Pažymėjo atsarginę kopiją :name kaip užbaigtą',
            'fail' => 'Pažymėjo atsarginę kopiją :name kaip nepavykusią',
            'lock' => 'Užrakino atsarginę kopiją :name',
            'unlock' => 'Atrakino atsarginę kopiją :name',
        ],
        'database' => [
            'create' => 'Sukūrė naują databazę :name',
            'rotate-password' => 'Pakeitė slaptažodį databazei :name',
            'delete' => 'Ištrynė databazę :name',
        ],
        'file' => [
            'compress_one' => 'Suspaudė :directory:file',
            'compress_other' => 'Kompresavo :count failų aplanke :directory',
            'read' => 'Pažiūrėjo failo :file turinį',
            'copy' => 'Sukūrė kopija failo :file',
            'create-directory' => 'Sukūrė aplanką :directory:name',
            'decompress' => 'Dekompresavo :files aplanke :directory',
            'delete_one' => 'Ištrynė :directory:files.0',
            'delete_other' => 'Ištrynė :count failų aplanke :directory',
            'download' => 'Persisiuntė :file',
            'pull' => 'Persisiuntė nuotolinį failą iš :url į :directory',
            'rename_one' => 'Pervadino :directory:files.0.from į :directory.files.0.to',
            'rename_other' => 'Pervadino :count failų aplanke :directory',
            'write' => 'Parašė naują turinį faile :file',
            'upload' => 'Pradėjo failo įkėlimą',
            'uploaded' => 'Įkėlė :directory:file',
        ],
        'sftp' => [
            'denied' => 'Užblokavo SFTP prieeigą, dėl teisių',
            'create_one' => 'Sukūrė :files.0',
            'create_other' => 'Sukūrė :count naujų failų',
            'write_one' => 'Modifikavo failo :files.0 turinius',
            'write_other' => 'Modifikavo :count failų turinius',
            'delete_one' => 'Ištrynė :files.0',
            'delete_other' => 'Ištrynė :count failų',
            'create-directory_one' => 'Sukūrė :files.0 aplanką',
            'create-directory_other' => 'Sukūrė :count aplankų',
            'rename_one' => 'Pervadino :files.0.from į :files.0.to',
            'rename_other' => 'Pervadino arba perkėlė :count failų',
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
