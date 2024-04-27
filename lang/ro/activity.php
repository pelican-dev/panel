<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Autentificare nereușită',
        'success' => 'Conectat',
        'password-reset' => 'Resetează parola',
        'reset-password' => 'Solicitarea parolei a fost transmisă',
        'checkpoint' => 'Autentificare cu doi factori solicitată',
        'recovery-token' => 'Token de recuperare utilizat',
        'token' => 'Provocarea cu doi factori a fost rezolvată',
        'ip-blocked' => 'Solicitare blocată de la adresa IP nelistată pentru :identifier',
        'sftp' => [
            'fail' => 'Conectare SFTP nereușită',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'E-mail schimbat din :old în :new',
            'password-changed' => 'Parolă modificată',
        ],
        'api-key' => [
            'create' => 'A creat o nouă cheie API :identifier',
            'delete' => 'Cheia API ştearsă :identifier',
        ],
        'ssh-key' => [
            'create' => 'Cheia SSH adăugată :fingerprint în cont',
            'delete' => 'Cheia SSH :fingerprint a fost eliminată din cont',
        ],
        'two-factor' => [
            'create' => 'Autorizare doi factori activată',
            'delete' => 'Autorizare doi factori dezactivată',
        ],
    ],
    'server' => [
        'reinstall' => 'Server reinstalat',
        'console' => [
            'command' => 'Execut ":command" pe server',
        ],
        'power' => [
            'start' => 'A pornit serverul',
            'stop' => 'A oprit serverul',
            'restart' => 'Repornire server',
            'kill' => 'Procesul serverului a fost oprit',
        ],
        'backup' => [
            'download' => 'S-a descărcat copia de rezervă :name',
            'delete' => 'Copie de rezervă :name a fost ștearsă',
            'restore' => 'Copie de rezervă :name restaurată (fișiere șterse: :truncate)',
            'restore-complete' => 'Restaurare finalizată a copiei de rezervă :name',
            'restore-failed' => 'Restaurarea copiei de rezervă :name a eșuat',
            'start' => 'A început o copie de rezervă nouă :name',
            'complete' => 'Marcat ca complet copia de siguranță :name',
            'fail' => 'Marcat backup :name ca fiind eșuat',
            'lock' => 'S-a blocat copia de rezervă :name',
            'unlock' => 'S-a deblocat copia de rezervă :name',
        ],
        'database' => [
            'create' => 'Noua bază de date :name',
            'rotate-password' => 'Parolă resetată pentru baza de date :name',
            'delete' => 'Baza de date ştearsă :name',
        ],
        'file' => [
            'compress_one' => 'Comprimat :directory:file',
            'compress_other' => 'Fișierele :count comprimate în :director',
            'read' => 'A vizualizat conținutul din :file',
            'copy' => 'A creat o copie a :file',
            'create-directory' => 'Director creat :director:directory:name',
            'decompress' => 'Dezarhivat :files in :directory',
            'delete_one' => 'Șters :directory:files.0',
            'delete_other' => 'Fișierele :count șterse din :directory',
            'download' => ':file descărcat',
            'pull' => 'S-a descărcat un fișier de la distanță din :url în :directory',
            'rename_one' => 'Redenumit :directory:files.0.de la :directory:files.0.to',
            'rename_other' => 'Redenumite :count fișiere în :directory',
            'write' => 'Conținut nou în :file',
            'upload' => 'Începe încărcarea unui fișier',
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
