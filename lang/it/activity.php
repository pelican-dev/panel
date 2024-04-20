<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Accesso non riuscito',
        'success' => 'Accesso effettuato',
        'password-reset' => 'Reimposta Password',
        'reset-password' => 'Richiedi reimpostazione della password',
        'checkpoint' => 'Autenticazione a due fattori necessaria',
        'recovery-token' => 'Token di recupero a due fattori utilizzato',
        'token' => 'Verifica a due fattori risolta',
        'ip-blocked' => 'Richiesta bloccata dall\'indirizzo IP non elencato per :identifier',
        'sftp' => [
            'fail' => 'Accesso SFTP non riuscito',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Email modificata da :old a :new',
            'password-changed' => 'Password modificata',
        ],
        'api-key' => [
            'create' => 'Creata una nuova chiave API :identifier',
            'delete' => 'Chiave API eliminata :identifier',
        ],
        'ssh-key' => [
            'create' => 'Chiave SSH :fingerprint aggiunta all\'account',
            'delete' => 'Chiave SSH :impronta rimossa dall\'account',
        ],
        'two-factor' => [
            'create' => 'Autenticazione a due fattori attivata',
            'delete' => 'Autenticazione a due fattori disattivata',
        ],
    ],
    'server' => [
        'reinstall' => 'Server reinstallato',
        'console' => [
            'command' => 'Eseguito ":command" sul server',
        ],
        'power' => [
            'start' => 'Server avviato',
            'stop' => 'Server arrestato',
            'restart' => 'Server riavviato',
            'kill' => 'Processo del server terminato',
        ],
        'backup' => [
            'download' => 'Backup :name scaricato',
            'delete' => 'Backup :name eliminato',
            'restore' => 'Ripristinato il backup :name (file eliminati: :truncate)',
            'restore-complete' => 'Ripristino completato del backup :name',
            'restore-failed' => 'Impossibile completare il ripristino del backup :name',
            'start' => 'Avviato un nuovo backup :name',
            'complete' => 'Contrassegnato il backup :name come completato',
            'fail' => 'Contrassegnato il backup :name come fallito',
            'lock' => 'Bloccato il backup :name',
            'unlock' => 'Sbloccato il backup :name',
        ],
        'database' => [
            'create' => 'Creato un nuovo database :name',
            'rotate-password' => 'Password ruotata per il database :name',
            'delete' => 'Database eliminato :name',
        ],
        'file' => [
            'compress_one' => 'Compresso :directory:file',
            'compress_other' => 'File :count compressi in :directory',
            'read' => 'Visualizzato il contenuto di :file',
            'copy' => 'Creato una copia di :file',
            'create-directory' => 'Cartella creata :directory:name',
            'decompress' => 'Decompresso :files in :directory',
            'delete_one' => 'Eliminato :directory:files.0',
            'delete_other' => 'Eliminati :count file in :directory',
            'download' => 'Scaricato :file',
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
