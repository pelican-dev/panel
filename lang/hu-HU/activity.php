<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'Sikertelen bejelentkezés',
        'success' => 'Bejelentkezve',
        'password-reset' => 'Jelszó helyreállítás',
        'checkpoint' => 'Két-faktoros hitelesítési kérelem',
        'recovery-token' => 'Két-faktoros helyreállítási kulcs használata',
        'token' => 'Sikeres két-faktoros hitelesítés',
        'ip-blocked' => 'Blokkolt kérés a következő nem listázott IP-címről <b>:identifier</b>',
        'sftp' => [
            'fail' => 'Sikertelen SFTP bejelentkezés',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'Az email megváltoztatva a következőről: <b>:old</b> erre: <b>:new</b>',
            'password-changed' => 'Jelszó megváltoztatva',
        ],
        'api-key' => [
            'create' => 'Új API kulcs létrehozva <b>:identifier</b>',
            'delete' => 'API kulcs törölve <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => 'SSH kulcs létrehozva a fiókhoz <b>:fingerprint</b>',
            'delete' => 'SSH kulcs eltávolítva <b>:fingerprint</b>',
        ],
        'two-factor' => [
            'create' => 'Két-faktoros hitelesítés bekapcsolva',
            'delete' => 'Két-faktoros hitelesítés kikapcsolva',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'Végrehajtott parancs: "<b>:command</b>" a szerveren',
        ],
        'power' => [
            'start' => 'Szerver elindítva',
            'stop' => 'Szerver leállítva',
            'restart' => 'Szerver újraindítva',
            'kill' => 'Szerver folyamat leállítva',
        ],
        'backup' => [
            'download' => 'Letöltötted a(z) <b>:name</b> mentést',
            'delete' => 'Törölted a(z) <b>:name</b> mentést',
            'restore' => 'Visszaállítottad a(z) <b>:name</b> mentést (törölt fájlok: <b>:truncate</b>)',
            'restore-complete' => 'Sikeresen visszaállítottad a(z) <b>:name</b> mentést',
            'restore-failed' => 'Nem sikerült visszaállítani a(z) <b>:name</b> mentést',
            'start' => 'Új mentés elindítva: <b>:name</b>',
            'complete' => 'A(z) <b>:name</b> mentés sikeresnek jelölve',
            'fail' => 'A(z) <b>:name</b> mentés sikertelennek jelölve',
            'lock' => 'A(z) <b>:name</b> mentés zárolva',
            'unlock' => 'A(z) <b>:name</b> mentés feloldva',
            'rename' => 'A mentés neve „<b>:old_name</b>”„<b>:new_name</b>”-re változott.',
        ],
        'database' => [
            'create' => 'Új adatbázis létrehozva: <b>:name</b>',
            'rotate-password' => 'Jelszó megváltoztatva a(z) <b>:name</b> adatbázishoz',
            'delete' => 'Adatbázis törölve: <b>:name</b>',
        ],
        'file' => [
            'compress' => 'Tömörítve: <b>:directory:files</b>|Tömörítve <b>:count</b> fájl a(z) <b>:directory</b> könyvtárban',
            'read' => 'Megnézted a(z) <b>:file</b> tartalmát',
            'copy' => 'Másolat készítve a(z) <b>:file</b> fájlról',
            'create-directory' => 'Könyvtár létrehozva: <b>:directory:name</b>',
            'decompress' => 'Kicsomagolva: <b>:file</b> a(z) <b>:directory</b> könyvtárba',
            'delete' => 'Törölve: <b>:directory:files</b>|Törölve <b>:count</b> fájl a(z) <b>:directory</b> könyvtárban',
            'download' => 'Letöltötted a(z) <b>:file</b> fájlt',
            'pull' => 'Távoli fájl letöltve innen: <b>:url</b> ide: <b>:directory</b>',
            'rename' => 'Áthelyezve/átnevezve: <b>:from</b> erre: <b>:to</b>|Áthelyezve/átnevezve <b>:count</b> fájl a(z) <b>:directory</b> könyvtárban',
            'write' => 'Új tartalom írva a(z) <b>:file</b> fájlba',
            'upload' => 'Elkezdte egy fájl feltöltését',
            'uploaded' => 'Feltöltve: <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => 'SFTP hozzáférés megtagadva hiányzó jogosultságok miatt',
            'create' => 'Létrehozva: <b>:files</b>|Létrehozva <b>:count</b> új fájl',
            'write' => 'Módosítva: <b>:files</b>|Módosítva <b>:count</b> fájl tartalma',
            'delete' => 'Törölve: <b>:files</b>|Törölve <b>:count</b> fájl',
            'create-directory' => 'Létrehozva: <b>:files</b> könyvtár|Létrehozva <b>:count</b> könyvtár',
            'rename' => 'Átnevezve: <b>:from</b> erre: <b>:to</b>|Átnevezve/áthelyezve <b>:count</b> fájl',
        ],
        'allocation' => [
            'create' => 'Hozzáadva: <b>:allocation</b> a szerverhez',
            'notes' => 'Megjegyzés frissítve: <b>:allocation</b> erről: "<b>:old</b>" erre: "<b>:new</b>"',
            'primary' => 'Beállítva: <b>:allocation</b> mint elsődleges szerver allokáció',
            'delete' => 'Törölve: <b>:allocation</b> allokáció',
        ],
        'schedule' => [
            'create' => '<b>:name</b> időzítő létrehozva',
            'update' => '<b>:name</b> időzítő frissítve',
            'execute' => '<b>:name</b> időzítő manuálisan végrehajtva',
            'delete' => '<b>:name</b> időzítő törölve',
        ],
        'task' => [
            'create' => 'Új feladat létrehozva: "<b>:action</b>" a(z) <b>:name</b> időzítőben',
            'update' => 'Frissítve: "<b>:action</b>" feladat a(z) <b>:name</b> időzítőben',
            'delete' => 'Törölve: "<b>:action</b>" feladat a(z) <b>:name</b> időzítőből',
        ],
        'settings' => [
            'rename' => 'A szerver átnevezve erről: "<b>:old</b>" erre: "<b>:new</b>"',
            'description' => 'A szerver leírása megváltoztatva erről: "<b>:old</b>" erre: "<b>:new</b>"',
            'reinstall' => 'Szerver újratelepítve',
        ],
        'startup' => [
            'edit' => 'Megváltoztatva: <b>:variable</b> változó erről: "<b>:old</b>" erre: "<b>:new</b>"',
            'image' => 'Docker Image frissítve erről: <b>:old</b> erre: <b>:new</b>',
        ],
        'subuser' => [
            'create' => 'Hozzáadva: <b>:email</b> mint alfelhasználó',
            'update' => 'Frissítve: <b>:email</b> alfelhasználó jogosultságai',
            'delete' => 'Eltávolítva: <b>:email</b> alfelhasználó',
        ],
        'crashed' => 'A szerver összeomlott',
    ],
];
