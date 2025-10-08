<?php

return [
    'title' => 'Panel telepítő',
    'requirements' => [
        'title' => 'Szerver követelmény',
        'sections' => [
            'version' => [
                'title' => 'PHP Verzió',
                'or_newer' => ':version vagy újabb',
                'content' => 'jelenlegi PHP verzió:',
            ],
            'extensions' => [
                'title' => 'PHP Bővítmények',
                'good' => 'Minden PHP Bővítmény telepítve van.',
                'bad' => 'A következő PHP kiterjesztések hiányoznak: :extensions',
            ],
            'permissions' => [
                'title' => 'Mappa Engedélyek',
                'good' => 'Összes mappának kell a megfelelő engedélyek.',
                'bad' => 'A következő mappáknak rossz engedélyük van: :folders',
            ],
        ],
        'exception' => 'Pár követelmény hiányzik',
    ],
    'environment' => [
        'title' => 'Környezet',
        'fields' => [
            'app_name' => 'App Neve',
            'app_name_help' => 'Ez lesz a paneled neve',
            'app_url' => 'App URL',
            'app_url_help' => 'Ez lesz az URL amivel eléred a panelt.',
            'account' => [
                'section' => 'Admin felhasználó',
                'email' => 'E-Mail',
                'username' => 'Felhasználónév',
                'password' => 'Jelszó',
            ],
        ],
    ],
    'database' => [
        'title' => 'Adatbázis',
        'driver' => 'Adatbázis Szoftver',
        'driver_help' => 'A szoftver a panel adatbázisához. Ajánljuk a "SQLite" szoftvert.',
        'fields' => [
            'host' => 'Adatbázis kiszolgáló',
            'host_help' => 'Ez lesz az adatbázis kiszolgáló. Ügyelj arra hogy elérhető legyen.',
            'port' => 'Adatbázis Port',
            'port_help' => 'Ez lesz az adatbázis portja.',
            'path' => 'Adatbázis elérési út',
            'path_help' => 'A elérési útvonala a .sqlite fájlhoz.',
            'name' => 'Adatbázis név',
            'name_help' => 'A panel adatbázisának a neve.',
            'username' => 'Adatbázis felhasználónév',
            'username_help' => 'Ez lesz az adatbázis felhasználóneve.',
            'password' => 'Adatbázis jelszó',
            'password_help' => 'Ez lesz az adatbázis jelszava',
        ],
        'exceptions' => [
            'connection' => 'Sikertelen kapcsolódás az adatbázishoz!',
            'migration' => 'Áthelyezés sikertelen',
        ],
    ],
    'session' => [
        'title' => 'Munkamenet',
        'driver' => 'Session Driver',
        'driver_help' => 'A szoftver a munkamenet mentéséhez. Ajánljuk a "Fájlrendszer"-t vagy "Adatbázis" opciót.',
    ],
    'cache' => [
        'title' => 'Gyorsítótár',
        'driver' => 'Gyorsítótár meghajtó',
        'driver_help' => 'A szoftver a gyorsítótárhoz. Ajánljuk a "Fájlrendszer".',
        'fields' => [
            'host' => 'Redis host',
            'host_help' => 'A redis szerver kiszolgálója. Ügyelj arra hogy elérhető legyen.',
            'port' => 'Redis port',
            'port_help' => 'Redis szerver portja.',
            'username' => 'Redis felhasználónév',
            'username_help' => 'A neve a Redis felhasználónak. Lehet üres is.',
            'password' => 'Redis Jelszó',
            'password_help' => 'A redis szerver jelszava. Lehet üres is.',
        ],
        'exception' => 'Redis szerver csatlakozás sikertelen',
    ],
    'queue' => [
        'title' => 'Várólista',
        'driver' => 'Várólista szoftver',
        'driver_help' => 'A szoftver ami kezeli a várólistát. Ajánljuk az "Adatbázis" opciót.',
        'fields' => [
            'done' => 'Megtettem mind kettő lépést lent.',
            'done_validation' => 'Mind kettő lépést teljesítened kell folytatás elött.',
            'crontab' => '',
            'service' => 'A várólista szoftver telepítéséhez egyszerűen csak a következő parancsokat kell le futtatni.',
        ],
    ],
    'exceptions' => [
        'write_env' => 'Sikertelen .env fájlba írás.',
        'migration' => 'Átvétel futtatása sikertelen',
        'create_user' => 'Adminisztrátor felhasználó létrehozása sikertelen.',
    ],
    'next_step' => 'Következő',
    'finish' => 'Befejezés',
];
