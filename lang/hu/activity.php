<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Sikertelen bejelenzkezés',
        'success' => 'Bejelentkezve',
        'password-reset' => 'Jelszó helyreállítás',
        'reset-password' => 'Jelszó helyreállítási kérelem',
        'checkpoint' => 'Két-faktoros hitelesítési kérelem',
        'recovery-token' => 'Két-faktoros helyreállítási kulcs használata',
        'token' => 'Sikeres két-faktoros hitelesítés',
        'ip-blocked' => 'Blokkolt kérés a következő nem listázott IP-címről: :identifier',
        'sftp' => [
            'fail' => 'Sikertelen SFTP bejelentkezés',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Email cím megváltoztatva :old -ról :new -ra',
            'password-changed' => 'Jelszó megváltoztatva',
        ],
        'api-key' => [
            'create' => 'Új API kulcs létrehozva :identifier',
            'delete' => 'API kulcs törölve :identifier',
        ],
        'ssh-key' => [
            'create' => 'SSH kulcs :fingerprint hozzáadva a fiókhoz',
            'delete' => 'SSH kulcs :fingerprint törölve a fiókból',
        ],
        'two-factor' => [
            'create' => 'Két-faktoros hitelesítés bekapcsolva',
            'delete' => 'Két-faktoros hitelesítés kikapcsolva',
        ],
    ],
    'server' => [
        'reinstall' => 'Szerver újratelepítve',
        'console' => [
            'command' => 'Végrehajtott ":command" parancs a szerveren',
        ],
        'power' => [
            'start' => 'Szerver elindítva',
            'stop' => 'Szerver leállítva',
            'restart' => 'Szerver újraindítva',
            'kill' => 'Szerver folyamat leállítva',
        ],
        'backup' => [
            'download' => ':name biztonsági mentés letöltve',
            'delete' => ':name biztonsági mentés törölve',
            'restore' => ':name biztonsági mentés helyreállítva. (törölt fájlok :truncate)',
            'restore-complete' => ':name biztonsági mentés helyreállítása befejezve',
            'restore-failed' => 'Nem sikerült visszaállítani a :name biztonsági mentést',
            'start' => 'Új biztonsági mentés :name',
            'complete' => ':name biztonsági mentés megjelölve befejezettként',
            'fail' => ':name biztonsági mentés sikertelennek jelölve',
            'lock' => ':name biztonsági mentés zárolva',
            'unlock' => ':name biztonsági mentés zárolása feloldva',
        ],
        'database' => [
            'create' => 'Új adatbázis létrehozva :name',
            'rotate-password' => 'Új jelszó létrehozva a(z) :name adatbázishoz',
            'delete' => ':name adatbázis törölve',
        ],
        'file' => [
            'compress_one' => ':directory:file tömörítve',
            'compress_other' => ':count tömörített fájl a :directory könyvtárban',
            'read' => 'Megtekintette a :file tartalmát',
            'copy' => 'Másolatot készített a :file -ról',
            'create-directory' => 'Könyvtár létrehozva :directory:name',
            'decompress' => 'Kicsomagolva :files a :directory könyvtárban',
            'delete_one' => ':directory:files.0 törölve',
            'delete_other' => ':count fájl törölve a :directory könyvtárban',
            'download' => ':file letölve',
            'pull' => 'Egy távoli fájl letöltve a :url -ról a :directory könyvtárba',
            'rename_one' => ':directory:files.0 átnevezve :directory:files.0.to -ra',
            'rename_other' => ':count fájl átnevezve a :directory könyvtárban',
            'write' => 'Új tartalom hozzáadva a :file -hoz',
            'upload' => 'Elkezdte egy fájl feltöltését',
            'uploaded' => ':direcotry:file feltöltve',
        ],
        'sftp' => [
            'denied' => 'SFTP hozzáférés megtagadva hiányzó jogosultságok miatt',
            'create_one' => 'Létrehozva :files.0',
            'create_other' => 'Létrehozva :count új fájl',
            'write_one' => ':files.0 tartalma módosítva',
            'write_other' => ':count fájl tartalma módosítva',
            'delete_one' => 'Törölve :files.0',
            'delete_other' => 'Törölve :count db fájl',
            'create-directory_one' => ':files.0 könyvtár létrehozva',
            'create-directory_other' => ':count darab könyvtár létrehozva',
            'rename_one' => 'Átnevezve :files.0.from -ról :files.0.to -ra',
            'rename_other' => 'Átnevezett vagy áthelyezett :count darab fájlt',
        ],
        'allocation' => [
            'create' => ':allocation allokáció hozzáadva a szerverhez',
            'notes' => 'Jegyzet frissítve :allocation -ról :new -ra',
            'primary' => ':allocation beállítása elsődlegesként',
            'delete' => ':allocation allokáció törölve',
        ],
        'schedule' => [
            'create' => ':name ütemezés létrehozva',
            'update' => ':name ütemezés frissítve',
            'execute' => ':name ütemezés manuálisan futtatva',
            'delete' => ':name ütemezés törölve',
        ],
        'task' => [
            'create' => 'Új ":action" feladat létrehozva a :name ütemezéshez',
            'update' => '":action" feladat frissítve a :name ütemezésnél',
            'delete' => '":action" feladat törölve a :name ütemezésnél',
        ],
        'settings' => [
            'rename' => 'Szerver átnevezve :old -ról :new -ra',
            'description' => 'Szerver leírás módosítva :old -ról :new -ra',
        ],
        'startup' => [
            'edit' => ':variable módosítva :old -ról :new -ra',
            'image' => 'Docker image frissítve ennél a szervernél :old -ról :new -ra',
        ],
        'subuser' => [
            'create' => ':email hozzáadva al- felhasználóként',
            'update' => ':email al-fiók jogosultságai frissítve',
            'delete' => ':email al-fiók eltávolítva',
        ],
    ],
];
