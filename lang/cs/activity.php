<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Přihlášení se nezdařilo',
        'success' => 'Přihlášen',
        'password-reset' => 'Obnovit heslo',
        'reset-password' => 'Požádáno o změnu hesla',
        'checkpoint' => 'Požadováno dvoufaktorové ověření',
        'recovery-token' => 'Použitý dvoufázový obnovovací token',
        'token' => 'Vyřešená dvoufázová výzva',
        'ip-blocked' => 'Blokovaný požadavek z neuvedené IP adresy pro :identifier',
        'sftp' => [
            'fail' => 'Selhalo přihlášení k SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Změněn e-mail z :old na :new',
            'password-changed' => 'Změněno heslo',
        ],
        'api-key' => [
            'create' => 'Vytvořen nový API klíč :identifier',
            'delete' => 'Odstraněný API klíč :identifier',
        ],
        'ssh-key' => [
            'create' => 'Přidán SSH klíč :fingerprint k účtu',
            'delete' => 'Odstraněný SSH klíč :fingerprint z účtu',
        ],
        'two-factor' => [
            'create' => 'Povolené doufázové ověření',
            'delete' => 'Vypnuté dvoufázové ověření',
        ],
    ],
    'server' => [
        'reinstall' => 'Přeinstalovaný server',
        'console' => [
            'command' => 'Proveden příkaz „:command“ na serveru',
        ],
        'power' => [
            'start' => 'Server byl spuštěn',
            'stop' => 'Server byl vypnut',
            'restart' => 'Server byl restartován',
            'kill' => 'Ukončen proces serveru',
        ],
        'backup' => [
            'download' => 'Záloha :name stažena',
            'delete' => 'Záloha :name smazána',
            'restore' => 'Obnovena záloha :name (smazané soubory: :truncate)',
            'restore-complete' => 'Dokončená obnova zálohy :name',
            'restore-failed' => 'Nepodařilo se dokončit obnovení zálohy :name',
            'start' => 'Zahájeno zálohování :name',
            'complete' => 'Označit zálohu :name jako dokončená',
            'fail' => 'Záloha :name označena jako neúspěšná',
            'lock' => 'Záloha :name uzamčena',
            'unlock' => 'Záloha :name odemčena',
        ],
        'database' => [
            'create' => 'Vytvořena nová databáze :name',
            'rotate-password' => 'Heslo pro databázi :name změněno',
            'delete' => 'Smazána databáze :name',
        ],
        'file' => [
            'compress_one' => 'Komprimováno :directory:file',
            'compress_other' => 'Komprimováno :count souborů v :directory',
            'read' => 'Zobrazen obsah :file',
            'copy' => 'Vytvořena kopie :file',
            'create-directory' => 'Vytvořen adresář :directory:name',
            'decompress' => 'Dekomprimováno :files souborů v :directory',
            'delete_one' => 'Smazáno :directory:files.0',
            'delete_other' => ':count souborů v :directory bylo smazáno',
            'download' => 'Staženo :file',
            'pull' => 'Stažen vzdálený soubor z :url do :directory',
            'rename_one' => 'Přejmenováno :directory:files.0.from na :directory:files.0.to',
            'rename_other' => 'Přejmenováno :count souborů v :directory',
            'write' => 'Přepsaný nový obsah v :file',
            'upload' => 'Zahájeno nahrávání souboru',
            'uploaded' => 'Nahráno :directory:file',
        ],
        'sftp' => [
            'denied' => 'Zablokován SFTP přístup z důvodu nedostatku oprávnění',
            'create_one' => 'Vytvořeno :files.0',
            'create_other' => 'Vytvořeno :count nových souborů',
            'write_one' => 'Změněn obsah :files.0',
            'write_other' => 'Změněn obsah :count souborů',
            'delete_one' => 'Smazáno :files.0',
            'delete_other' => 'Smazáno :count souborů',
            'create-directory_one' => 'Vytvořen adresář :files.0',
            'create-directory_other' => 'Vytvořeno :count adresářů',
            'rename_one' => 'Přejmenováno :files.0.from na :files.0.to',
            'rename_other' => 'Přejmenováno nebo přesunuto :count souborů',
        ],
        'allocation' => [
            'create' => 'Přidáno :alokace k serveru',
            'notes' => 'Aktualizovány poznámky pro :allocation z „:old“ na „:new“',
            'primary' => 'Nastavit :allocation jako primární alokaci serveru',
            'delete' => 'Odstraněno :allocation',
        ],
        'schedule' => [
            'create' => 'Vytvořen plán :name',
            'update' => 'Aktualizován plán :name',
            'execute' => 'Manuálně proveden plán :name',
            'delete' => 'Odstraněn plán :name',
        ],
        'task' => [
            'create' => 'Vytvořen nový úkol „:action“ pro plán :name',
            'update' => 'Aktualizován úkol „:action“ pro plán :name',
            'delete' => 'Odstraněn úkol pro plán :name',
        ],
        'settings' => [
            'rename' => 'Přejmenován server z :old na :new',
            'description' => 'Změněn popis serveru z :old na :new',
        ],
        'startup' => [
            'edit' => ':variable byla změněna z „:old“ na „:new“',
            'image' => 'Aktualizoval Docker Image pro server z :old na :new',
        ],
        'subuser' => [
            'create' => ':email přidán jako poduživatel',
            'update' => 'Aktualizována oprávnění poduživatele pro :email',
            'delete' => ':email odebrán jako poduživatel',
        ],
    ],
];
