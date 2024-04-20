<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Nepodarilo sa prihlásiť',
        'success' => 'Prihlásený',
        'password-reset' => 'Resetovať heslo',
        'reset-password' => 'Požiadané o reset hesla',
        'checkpoint' => 'Požadované dvoj-faktorové overenie',
        'recovery-token' => 'Použitý dvoj-faktorový obnovovací token',
        'token' => 'Dvoj-faktorové overenie vyriešené',
        'ip-blocked' => 'Požiadavka bola zablokovaná z neuvedenej IP adresy pre :identifier ',
        'sftp' => [
            'fail' => 'Prihlásenie SFTP zlyhalo',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Email bol zmenený z :old na :new',
            'password-changed' => 'Heslo bolo zmenené',
        ],
        'api-key' => [
            'create' => 'Vytvorený nový API kľúč :identifier',
            'delete' => 'Odstránený API kľúč :identifier',
        ],
        'ssh-key' => [
            'create' => 'Pridaný SSH kľúč :fingerprint k účtu',
            'delete' => 'Zmazaný SSH kľúč :fingerprint z účtu',
        ],
        'two-factor' => [
            'create' => 'Dvoj-faktorové overenie zapnuté',
            'delete' => 'Dvoj-faktorové overenie vypnuté',
        ],
    ],
    'server' => [
        'reinstall' => 'Server bol preinštalovaný',
        'console' => [
            'command' => 'Príkaz ":command" sa vykonal na servery',
        ],
        'power' => [
            'start' => 'Server bol spustený',
            'stop' => 'Server bol zastavený',
            'restart' => 'Server bol reštartovaný',
            'kill' => 'Proces serveru bol vynútene ukončený',
        ],
        'backup' => [
            'download' => 'Záloha :name bola stiahnutá',
            'delete' => 'Záloha :name bola odstránená',
            'restore' => 'Záloha :name bola obnovená (zmazané súbory: :truncate)',
            'restore-complete' => 'Obnova zálohy :name bola dokončená',
            'restore-failed' => 'Obnova zálohy :name nebola ukončená úspešne',
            'start' => 'Spustenie zálohovania :name',
            'complete' => 'Záloha :name bola označená ako dokončená',
            'fail' => 'Záloha :name bola označená ako zlyhaná',
            'lock' => 'Záloha :name uzamknutá',
            'unlock' => 'Záloha :name odomknutá',
        ],
        'database' => [
            'create' => 'Bola vytvorená nová databáza :name',
            'rotate-password' => 'Heslo bolo zmenené pre databázu :name',
            'delete' => 'Odstránená databáza :name',
        ],
        'file' => [
            'compress_one' => 'Súbor :directory:file bol skomprimovaný',
            'compress_other' => 'Skomprimovaných :count súborov v :directory',
            'read' => 'Zobrazený obsah :file',
            'copy' => 'Vytvorená kópia :file',
            'create-directory' => 'Vytvorený priečinok :directory:name',
            'decompress' => 'Rozbalené :files v :directory',
            'delete_one' => 'Zmazané :directory:files.0',
            'delete_other' => 'Zmazaných :count súborov v :directory',
            'download' => 'Stiahnuté :file',
            'pull' => 'Stiahnutý vzdialený súbor z :url do :directory',
            'rename_one' => 'Premenované :directory:files.0.from to :directory:files.0.to',
            'rename_other' => 'Premenovaných :count súborov v :directory',
            'write' => 'Nový obsah zapísaný do :file',
            'upload' => 'Začalo nahrávanie súboru',
            'uploaded' => 'Nahrané :directory:file',
        ],
        'sftp' => [
            'denied' => 'SFTP prístup bol zablokovaný kvôli právam',
            'create_one' => 'Vytvorené :files.0',
            'create_other' => 'Vytvorených :count nových súborov',
            'write_one' => 'Upravený obsah :files.0',
            'write_other' => 'Upravený obsah :count súborov',
            'delete_one' => 'Zmazané :files.0',
            'delete_other' => 'Zmazaných :count súborov',
            'create-directory_one' => 'Vytvorený :files.0 priečinok',
            'create-directory_other' => 'Vytvorených :count priečinkov',
            'rename_one' => 'Premenované :files.0.from na :files.0.to',
            'rename_other' => 'Premenovaných alebo presunutých :count súborov',
        ],
        'allocation' => [
            'create' => 'Pridaná alokácia :allocation k serveru',
            'notes' => 'Aktualizované poznámky k :allocation z ":old" na ":new"',
            'primary' => 'Nastavená alokácia :allocation ako primárna alokácia serveru',
            'delete' => 'Alokácia :allocation bola zmazaná',
        ],
        'schedule' => [
            'create' => 'Vytvorené načasovanie :name',
            'update' => 'Aktualizované načasovanie :name',
            'execute' => 'Manuálne spustené načasovanie :name',
            'delete' => 'Zmazané načasovanie :name',
        ],
        'task' => [
            'create' => 'Vytvorená nová úloha ":action" pre načasovanie :name',
            'update' => 'Aktualizovaná úloha ":action" pre načasovanie :name',
            'delete' => 'Úloha pre načasovanie :name bola odstránená',
        ],
        'settings' => [
            'rename' => 'Server bol premenovaný z :old na :new',
            'description' => 'Popis servera bol zmenený z :old na :new',
        ],
        'startup' => [
            'edit' => 'Premenná :variable bola zmenená z ":old" na ":new"',
            'image' => 'Docker Image pre server bol aktualizovaný z :old na :new',
        ],
        'subuser' => [
            'create' => 'Pridaný :email ako podpoužívateľ',
            'update' => 'Aktualizované práva podpoužívateľa pre :email',
            'delete' => 'Odstránený :email ako podpoužívateľ',
        ],
    ],
];
