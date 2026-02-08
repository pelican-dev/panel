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
        'success' => 'Přihlášen/a',
        'password-reset' => 'Obnovit heslo',
        'checkpoint' => 'Požadováno dvoufaktorové ověření',
        'recovery-token' => 'Použitý dvoufázový obnovovací token',
        'token' => 'Vyřešená dvoufaktorová výzva',
        'ip-blocked' => 'Zablokován požadavek z neuvedené IP adresy pro <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Selhalo přihlášení SFTP',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'Změněno uživatelské jméno z <b>:old</b> na <b>:new</b>',
            'email-changed' => 'Změněný e-mail z <b>:old</b> na <b>:new</b>',
            'password-changed' => 'Změněné heslo',
        ],
        'api-key' => [
            'create' => 'Vytvořen nový API klíč <b>:identifier</b>',
            'delete' => 'Smazán API klíč <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'Přidán SSH klíč <b>:fingerprint</b> k účtu',
            'delete' => 'Odstraněn SSH klíč <b>:fingerprint</b> z účtu',
        ],
        'two-factor' => [
            'create' => 'Povoleno dvoufaktorové ověření',
            'delete' => 'Zakázáno dvoufaktorové ověření',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Proveden příkaz "<b>:command</b>“ na serveru',
        ],
        'power' => [
            'start' => 'Server byl spuštěn',
            'stop' => 'Server byl vypnut',
            'restart' => 'Server byl restartován',
            'kill' => 'Ukončen proces serveru',
        ],
        'backup' => [
            'download' => 'Stáhnuto <b>:name</b> zálohu',
            'delete' => 'Smazána záloha <b>:name</b>',
            'restore' => 'Obnovena záloha <b>:name</b> (smazané soubory: <b>:truncate</b>)',
            'restore-complete' => 'Dokončená obnova zálohy <b>:name</b>',
            'restore-failed' => 'Nepodařilo se dokončit obnovení zálohy <b>:name</b>',
            'start' => 'Nová záloha byla spuštěna <b>:name</b>',
            'complete' => 'Označil <b>:name</b> záloha jako kompletní',
            'fail' => 'Záloha označena jako neúspěšná <b>:name</b>',
            'lock' => 'Uzamčeno <b>:name</b> záloha',
            'unlock' => 'Odemknul <b>:name</b> zálohu',
            'rename' => 'Záloha přejmenovaná z "<b>:old_name</b>" na "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => 'Vytvořena nová databáze <b>:name</b>',
            'rotate-password' => 'Heslo pro databázi <b>:name</b>',
            'delete' => 'Smazána databáze <b>:name</b><b>',
        ],
        'file' => [
            'compress' => 'Komprimováno <b>:directory:files</b>|Komprimováno <b>:count</b> souborů v <b>:directory</b>',
            'read' => 'Zobrazen obsah <b>:file</b>',
            'copy' => 'Vytvořena kopie <b>:file</b>',
            'create-directory' => 'Vytvořený adresář <b>:directory:name</b>',
            'decompress' => 'Dekomprimován <b>:file</b> v <b>:directory</b>',
            'delete' => 'Smazáno <b>:directory:files</b>|Smazáno <b>:count</b> souborů v <b>:directory</b>',
            'download' => 'Staženo <b>:file</b>',
            'pull' => 'Stáhnout vzdálený soubor z <b>:url</b> do <b>:directory</b>',
            'rename' => 'Přesunuto / přejmenováno <b>:from</b> to <b>:to</b>|Moved/ přejmenováno <b>:count</b> souborů v <b>:directory</b>',
            'write' => 'Přepsaný nový obsah v <b>:file</b>',
            'upload' => 'Zahájeno nahrávání souboru',
            'uploaded' => 'Nahráno <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'Zablokován přístup SFTP z důvodu oprávnění',
            'create' => 'Vytvořeno <b>:files</b>|Vytvořeno <b>:count</b> nových souborů',
            'write' => 'Upravil obsah <b>:files</b>|Upravil obsah <b>:count</b> souborů',
            'delete' => 'Smazáno <b>:files</b>|Smazáno <b>:count</b> souborů',
            'create-directory' => 'Vytvořil adresář <b>:files</b> | Vytvořil <b>:count</b> adresáře',
            'rename' => 'Přejmenováno <b>:z</b> na <b>:to</b>|přejmenováno nebo přesunuto <b>:count</b> souborů',
        ],
        'allocation' => [
            'create' => 'Přidáno <b>:allocace</b> na server',
            'notes' => 'Aktualizovány poznámky pro <b>:allocation</b> z "<b>:old</b>" na "<b>:new</b>"',
            'primary' => 'Nastavil <b>:allocation</b> jako primární rozvržení serveru',
            'delete' => 'Smazáno <b>:allocation</b> alokace',
        ],
        'schedule' => [
            'create' => 'Vytvořil/a plán <b>:name</b>',
            'update' => 'Aktualizován plán <b>:name</b>',
            'execute' => 'Manuálně provést plán <b>:name</b>',
            'delete' => 'Smazán plán <b>:name</b>',
        ],
        'task' => [
            'create' => 'Vytvořil nový úkol "<b>:action</b>" pro <b>:name</b> plán',
            'update' => 'Aktualizoval úkol "<b>:action</b>" pro plán <b>:name</b>',
            'delete' => 'Odstraněna akce "<b>:action</b>" pro plán <b>:name</b>',
        ],
        'settings' => [
            'rename' => 'Přejmenoval server z "<b>:old</b>" na "<b>:new</b>"',
            'description' => 'Změnil popis serveru z "<b>:old</b>" na "<b>:new</b>"',
            'reinstall' => 'Server přeinstalován',
        ],
        'startup' => [
            'edit' => 'Změnil proměnnou <b>:variable</b> z "<b>:old</b>" na "<b>:new</b>"',
            'image' => 'Aktualizoval Docker Image pro server z <b>:old</b> na <b>:new</b>',
            'command' => 'Aktualizován příkaz pro spuštění pro server z <b>:old</b> na <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'Přidáno <b>:email</b> jako poduživatel',
            'update' => 'Aktualizována oprávnění poduživatele pro <b>:email</b>',
            'delete' => 'Odstraněno <b>:email</b> jako poduživatel',
        ],
        'crashed' => 'Server havaroval',
    ],
];
