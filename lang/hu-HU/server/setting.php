<?php

return [
    'title' => 'Beállítások',
    'server_info' => [
        'title' => 'Szerver Információ',
        'information' => 'Információ',
        'name' => 'Kiszolgáló neve',
        'server_name' => 'Szerver neve: :name',
        'notification_name' => 'Szerver név frissítve',
        'description' => 'Szerver leírás',
        'notification_description' => 'Szerver leírás frissítve',
        'failed' => 'Sikertelen',
        'uuid' => 'Szerver UUID',
        'uuid_short' => 'Szerver ID',
        'node_name' => 'Csomópont neve',
        'limits' => [
            'title' => 'Korlátok',
            'unlimited' => 'Korlátlan',
            'of' => 'of',
            'cpu' => 'CPU',
            'memory' => 'Memória',
            'disk' => 'Lemezterület',
            'backups' => 'Biztonsági mentések',
            'databases' => 'Adatbázisok',
            'allocations' => 'Kiosztások',
            'no_allocations' => 'Nincs további kiosztás.
',
        ],
        'sftp' => [
            'title' => 'SFTP információk',
            'connection' => 'Kapcsolat',
            'action' => 'Kapcsolódás az SFTPhez',
            'username' => 'Felhasználónév',
            'password' => 'Jelszó',
            'password_body' => 'Az SFTP jelszavad megeggyezik azzal a jelszóval amit a panel fiókodnál használsz.',
        ],
    ],
    'reinstall' => [
        'title' => 'Szerver újratelepítése',
        'body' => 'A szerver újratelepítése leállítja azt, majd újra futtatja a telepítő scriptet, amely eredetileg beállította.
',
        'body2' => 'Ez a folyamat során egyes fájlok törlődhetnek vagy módosulhatnak, ezért folytatás előtt készíts biztonsági mentést az adataidról.
',
        'action' => 'Újratelepítés',
        'modal' => 'Biztosan újratelepíted a szervert?
',
        'modal_description' => 'Ez a folyamat során egyes fájlok törlődhetnek vagy módosulhatnak, ezért folytatás előtt készíts biztonsági mentést az adataidról.
',
        'yes' => 'Igen, telepítsd újra',
        'notification_start' => 'Újratelepítés elindítva',
        'notification_fail' => 'Sikertelen újratelepítés',
    ],
];
