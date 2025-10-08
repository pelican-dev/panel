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
        'success' => 'Conectat',
        'password-reset' => 'Resetare parolă',
        'checkpoint' => 'Autentificare cu doi factori solicitată',
        'recovery-token' => 'Token de recuperare pentru doi factori utilizat',
        'token' => 'Provocarea doi factori rezolvată',
        'ip-blocked' => 'Solicitare blocată de la o adresă IP nelistată pentru <b>:Identifier</b>',
        'sftp' => [
            'fail' => 'Conectare SFTP nereușită',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'E-mail schimbat din <b>:old</b> în <b>:new</b>',
            'password-changed' => 'Parolă modificată',
        ],
        'api-key' => [
            'create' => 'S-a creat o nouă cheie API <b>:identifier</b>',
            'delete' => 'Cheia API ştearsă <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'Cheia SSH <b>:fingerprint</b> adăugată la cont',
            'delete' => 'Cheia SSH <b>:fingerprint</b> a fost eliminată din cont',
        ],
        'two-factor' => [
            'create' => 'Autorizare doi factori activată',
            'delete' => 'Autorizare doi factori dezactivată',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Executat "<b>:command</b>pe server',
        ],
        'power' => [
            'start' => 'A pornit serverul',
            'stop' => 'A oprit serverul',
            'restart' => 'Repornire server',
            'kill' => 'Procesul serverului a fost oprit',
        ],
        'backup' => [
            'download' => 'S-a descărcat copia de rezervă :name',
            'delete' => 'S-a șters copia de rezervă <b>:name</b>',
            'restore' => 'S-a restaurat copia de rezervă <b>:name</b> (fișiere șterse: <b>:truncate</b>)',
            'restore-complete' => 'Restaurare finalizată a copiei de rezervă <b>:name</b>',
            'restore-failed' => 'Realizarea restaurării copiei de rezervă <b>:name</b> a eșuat',
            'start' => 'S-a creat o copie de rezervă nouă :name',
            'complete' => 'Marcat copia de rezervă <b>:name</b> ca și completă',
            'fail' => 'Marcat copia de rezervă <b>:name</b> ca și eșuată',
            'lock' => 'Am blocat copia de rezervă <b>:name</b>',
            'unlock' => 'Am deblocat copia de rezervă <b>:name</b>',
            'rename' => 'Copia de rezervă "<b>:old_name</b>" a fost redenumită în "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => 'A creat o nouă bază de date <b>:name</b>',
            'rotate-password' => 'Parolă resetată pentru baza de date :name',
            'delete' => 'Baza de date <b>:name</b> a fost ştearsă',
        ],
        'file' => [
            'compress' => 'Comprimat <b>:directory:files</b><unk> Comprimat <b>:count</b> fișiere în <b>:directory</b>',
            'read' => 'A vizualizat conținutul din :file',
            'copy' => 'A creat o copie a :file',
            'create-directory' => 'Directorul creat <b>:directory:name</b>',
            'decompress' => 'Dezarhivat <b>:file</b> în <b>:directory</b>',
            'delete' => 'S-au șters <b>:directory:files</b><unk> <b>:count</b> fișiere din <b>:directory</b>',
            'download' => 'Descărcat <b>:file</b>',
            'pull' => 'S-a descărcat un fișier de la distanță de la <b>:url</b> în <b>:directory</b>',
            'rename' => 'Mutat/Redenumit <b>:from</b> în <b>:to</b>|Mutate/ Redenumite <b>:count</b> fișiere în <b>:directory</b>',
            'write' => 'A scris conținut nou în :file',
            'upload' => 'Începe încărcarea unui fișier',
            'uploaded' => 'Încărcat :directory:file',
        ],
        'sftp' => [
            'denied' => 'Acces SFTP blocat datorită permisiunilor',
            'create' => 'Creat <b>:files</b><unk> Creat <b>:count</b> fișiere noi',
            'write' => 'S-a modificat conţinutul fişierelor <b>:files</b>Modificat conţinutul fişierelor <b>:count</b>',
            'delete' => 'S-a șters <b>:files</b><unk> Fișiere șterse <b>:count</b>',
            'create-directory' => 'A creat directoarele <b>:files</b> Creat <b>:count</b>',
            'rename' => 'Redenumit <b>:from</b> la <b>:to</b><unk> Redenumite sau mutate <b>:count</b> fișiere',
        ],
        'allocation' => [
            'create' => 'A adăugat <b>:allocation</b> la server',
            'notes' => 'Notele au fost actualizate pentru <b>:allocation</b> de la "<b>:old</b>" la "<b>:new</b>"',
            'primary' => 'Setează <b>:allocation</b> ca alocare principală pentru server',
            'delete' => 'S-a șters alocarea <b>:allocation</b>',
        ],
        'schedule' => [
            'create' => 'S-a creat programul <b>:name</b>',
            'update' => 'S-a actualizat programul <b>:name</b>',
            'execute' => 'Executat manual programul <b>:name</b>',
            'delete' => 'S-a șters programul <b>:name</b>',
        ],
        'task' => [
            'create' => 'S-a creat o nouă sarcina“<b>:action</b>pentru programul <b>:name</b>',
            'update' => 'S-a actualizat sarcina<b>:action</b>pentru programul <b>:name</b>',
            'delete' => 'Sarcina "<b>:action</b>" pentru programul <b>:name</b> a fost ștearsă',
        ],
        'settings' => [
            'rename' => 'Redenumit serverul din "<b>:old</b>" în "<b>:new</b>"',
            'description' => 'A schimbat descrierea serverului din "<b>:old</b>" în "<b>:new</b>"',
            'reinstall' => 'Server reinstalat',
        ],
        'startup' => [
            'edit' => 'A modificat variabila <b>:variable</b> din "<b>:old</b>" în "<b>:new</b>"',
            'image' => 'A actualizat imaginea Docker pentru server de la <b>:old</b> la <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'A fost adăugat <b>:email</b> ca subuser',
            'update' => 'S-au actualizat permisiunile de subuser pentru <b>:email</b>',
            'delete' => 'A fost eliminat <b>:email</b> ca subuser',
        ],
        'crashed' => 'Serverul s-a oprit forțat',
    ],
];
