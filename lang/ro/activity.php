<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Autentificare eșuată',
        'success' => 'Autentificare reușită',
        'password-reset' => 'Resetare parolă',
        'reset-password' => 'Cerere de resetare a parolei',
        'checkpoint' => 'Solicitare de autentificare în doi pași',
        'recovery-token' => 'Utilizat token de recuperare în doi pași',
        'token' => 'Soluționat provocarea în doi pași',
        'ip-blocked' => 'Cerere blocată de la adresa IP necunoscută pentru :identifier',
        'sftp' => [
            'fail' => 'Autentificare SFTP eșuată',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Email a fost schimbat de la :old la :new',
            'password-changed' => 'Schimbat parola',
        ],
        'api-key' => [
            'create' => 'Crează cheie API nouă :identifier',
            'delete' => 'Șters cheia API :identifier',
        ],
        'ssh-key' => [
            'create' => 'Adăugat cheie SSH :fingerprint la cont',
            'delete' => 'Șters cheia SSH :fingerprint din cont',
        ],
        'two-factor' => [
            'create' => 'Activat autentificarea în doi pași',
            'delete' => 'Dezactivat autentificarea în doi pași',
        ],
    ],
    'server' => [
        'reinstall' => 'Reinstalare server',
        'console' => [
            'command' => 'Executat ":command" pe server',
        ],
        'power' => [
            'start' => 'Serverul a fost pornit',
            'stop' => 'Serverul a fost oprit',
            'restart' => 'Serverul a fost repornit',
            'kill' => 'Procesul serverului a fost oprit',
        ],
        'backup' => [
            'download' => 'Descărcat backup-ul :name',
            'delete' => 'Șters backup-ul :name',
            'restore' => 'Restaurat backup-ul :name (fișiere șterse: :truncate)',
            'restore-complete' => 'Restaurarea backup-ului :name a fost finalizată',
            'restore-failed' => 'Restaurarea backup-ului :name a eșuat',
            'start' => 'A început un nou backup :name',
            'complete' => 'Marcat backup-ul :name ca finalizat',
            'fail' => 'Marcat backup-ul :name ca eșuat',
            'lock' => 'Blocat backup-ul :name',
            'unlock' => 'Deblocat backup-ul :name',
        ],
        'database' => [
            'create' => 'Creată nouă bază de date :name',
            'rotate-password' => 'Parola rotită pentru baza de date :name',
            'delete' => 'Baza de date :name a fost ștearsă',
        ],
        'file' => [
            'compress_one' => 'Comprimat :directory:file',
            'compress_other' => ':count fișiere comprimate în :directory',
            'read' => 'Vizualizați conținutul :file',
            'copy' => 'A fost creată o copie a :file',
            'create-directory' => 'Director creat :directory:name',
            'decompress' => 'Dezcompresat :files în :directory',
            'delete_one' => 'Șters :directory:files.0',
            'delete_other' => ':count fișiere șterse în :directory',
            'download' => 'Descărcat :file',
            'pull' => 'Descărcat un fișier de la distanță de la :url la :directory',
            'rename_one' => 'Redenumit :directory:files.0.from la :directory:files.0.to',
            'rename_other' => ':count fișiere redenumite în :directory',
            'write' => 'Scris conținut nou în :file',
            'upload' => 'A început o încărcare de fișiere',
            'uploaded' => 'Încărcat :directory:file',
        ],
        'sftp' => [
            'denied' => 'Accesul SFTP a fost blocat din cauza permisiunilor',
            'create_one' => 'Creat :files.0',
            'create_other' => 'Creat :count fișiere noi',
            'write_one' => 'Modificat conținutul :files.0',
            'write_other' => 'Modificat conținutul a :count fișiere',
            'delete_one' => 'Șters :files.0',
            'delete_other' => 'Șters :count fișiere',
            'create-directory_one' => 'Creat directorul :files.0',
            'create-directory_other' => 'Creat :count directoare',
            'rename_one' => 'Redenumit :files.0.from la :files.0.to',
            'rename_other' => 'Redenumit sau mutat :count fișiere',
        ],
        'allocation' => [
            'create' => 'Adăugat :allocation la server',
            'notes' => 'Actualizate notele pentru :allocation de la ":old" la ":new"',
            'primary' => 'Setat :allocation ca alocare principală a serverului',
            'delete' => 'Șters alocarea :allocation',
        ],
        'schedule' => [
            'create' => 'Creat programul :name',
            'update' => 'Actualizat programul :name',
            'execute' => 'Executat manual programul :name',
            'delete' => 'Șters programul :name',
        ],
        'task' => [
            'create' => 'Creat o nouă sarcină ":action" pentru programul :name',
            'update' => 'Actualizat sarcina ":action" pentru programul :name',
            'delete' => 'Șters o sarcină pentru programul :name',
        ],
        'settings' => [
            'rename' => 'Redenumit serverul de la :old la :new',
            'description' => 'Schimbat descrierea serverului de la :old la :new',
        ],
        'startup' => [
            'edit' => 'Schimbat variabila :variable de la ":old" la ":new"',
            'image' => 'Actualizat imaginea Docker pentru server de la :old la :new',
        ],
        'subuser' => [
            'create' => 'Adăugat :email ca subutilizator',
            'update' => 'Actualizate permisiunile subutilizatorului pentru :email',
            'delete' => 'Eliminat :email ca subutilizator',
        ],
    ],
];
