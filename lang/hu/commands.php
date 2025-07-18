<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => 'Adj meg egy e-mail címet, amire exportálni tudjuk az eggs-t. Mindenképp valós e-mail cím legyen.',
            'url' => 'Az URL címnek kötelező, hogy "https://" vagy "http://"-el kezdődjön, attól függően, hogy SSL-t használsz-e vagy nem. Hogyha hibásan adod meg, rossz helyre fog menni.',
            'timezone' => 'Az időzónának egyeznie kell a PHP által támogatott időzónákkal. Hogyha nem vagy biztos, kérlek, látogasd meg a https://php.net/manual/en/timezones.php oldalt.',
        ],
        'redis' => [
            'note' => 'A Redis drivert választottad egy vagy több beállításhoz, kérlek add meg az érvényes kapcsolati adatokat alább. A legtöbb esetben használhatod az alapértelmezett értékeket, hacsak nem módosítottad a beállításaidat.',
            'comment' => 'Alapértelmezés szerint a Redis szerver felhasználóneve "default" és nincs jelszava, mivel helyileg fut és kívülről nem elérhető. Ha ez a helyzet, egyszerűen nyomj Enter-t érték megadása nélkül.',
            'confirm' => 'Úgy tűnik, a :field már definiálva van a Redis-hez, szeretnéd megváltoztatni?',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => 'Erősen ajánlott, hogy ne használd a "localhost" megnevezést adatbázis hosztként, mivel gyakran találkoztunk csatlakozási problémákkal. Ha helyi kapcsolatot szeretnél használni, inkább a "127.0.0.1" címet használd.',
        'DB_USERNAME_note' => 'A root fiók használata MySQL kapcsolathoz nemcsak erősen ellenjavallt, hanem ez az alkalmazás által sem engedélyezett. Létre kell hoznod egy MySQL felhasználót a szoftver számára.',
        'DB_PASSWORD_note' => 'Úgy tűnik, már van MySQL kapcsolati jelszavad megadva, szeretnéd megváltoztatni?',
        'DB_error_2' => 'A kapcsolati adataid NINCSENEK elmentve. Érvényes kapcsolati információkat kell megadnod a folytatáshoz.',
        'go_back' => 'Menj vissza és próbáld újra',
    ],
    'make_node' => [
        'name' => 'Add meg egy rövid azonosítót, ami segít megkülönböztetni ezt a node-ot a többitől',
        'description' => 'Add meg a node leírását az azonosításhoz',
        'scheme' => 'Add meg a "https"-t SSL-hez vagy "http"-t nem SSL kapcsolathoz',
        'fqdn' => 'Add meg a domain nevet (pl. node.pelda.hu) ami a daemonhoz való csatlakozáshoz lesz használva. IP címet csak akkor használhatsz, ha nem használsz SSL-t ehhez a node-hoz',
        'public' => 'Legyen ez a node nyilvános? Megjegyzés: ha privátra állítod egy node-ot, akkor letiltod a rá történő automatikus telepítést.',
        'behind_proxy' => 'A domain neved proxy mögött van?',
        'maintenance_mode' => 'A karbantartás be legyen kapcsolva?',
        'memory' => 'Add meg a maximum memóriát.',
        'memory_overallocate' => 'Add meg a memória túlallokálás mértékét, -1 letiltja az ellenőrzést, 0 pedig megakadályozza az új szerverek létrehozását',
        'disk' => 'Add meg a maximális lemezterületet',
        'disk_overallocate' => 'Add meg a lemezterület túlallokálás mértékét, -1 letiltja az ellenőrzést, 0 pedig megakadályozza az új szerverek létrehozását',
        'cpu' => 'Add meg a maximális CPU mennyiséget',
        'cpu_overallocate' => 'Add meg a CPU túlallokálás mértékét, -1 letiltja az ellenőrzést, 0 pedig megakadályozza az új szerverek létrehozását',
        'upload_size' => 'Add meg a maximális fájlfeltöltési méretet',
        'daemonListen' => 'Add meg a daemon figyelő portját',
        'daemonSFTP' => 'Add meg a daemon SFTP figyelő portját',
        'daemonSFTPAlias' => 'Add meg a daemon SFTP álnevét (üres is lehet).',
        'daemonBase' => 'Írd be a fő mappát',
        'success' => 'Sikeresen létrehoztál egy új node-ot :name néven, melynek azonosítója :id',
    ],
    'node_config' => [
        'error_not_exist' => 'A kiválasztott node nem létezik.',
        'error_invalid_format' => 'Érvénytelen formátum. Elfogadott opciók: yaml és json.',
    ],
    'key_generate' => [
        'error_already_exist' => 'Úgy tűnik, már be van állítva egy alkalmazás titkosítási kulcs. Ha folytatod ezt a folyamatot, az felülírja a meglévő kulcsot, és adatkárosodást okozhat a már titkosított adatoknál. NE FOLYTASD, HACSAK NEM TUDOD PONTOSAN, MIT CSINÁLSZ.',
        'understand' => 'Megértem a parancs végrehajtásának következményeit, és vállalom a felelősséget a titkosított adatok elvesztéséért.',
        'continue' => 'Biztosan folytatni szeretnéd? Az alkalmazás titkosítási kulcsának megváltoztatása ADATVESZTÉSHET vezethet.',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => 'Nincsenek végrehajtandó ütemezett feladatok a szerverekhez.',
            'error_message' => 'Hiba történt az ütemezés feldolgozása közben: ',
        ],
    ],
    'upgrade' => [
        'integrity' => 'Ez a parancs nem ellenőrzi a letöltött fájlok épségét. Kérlek, csak akkor folytasd, ha megbízol a letöltési forrásban. Ha nem szeretnél archívumot letölteni, használd a --skip-download kapcsolót, vagy válaszolj "nem"-mel a lenti kérdésre.',
        'source_url' => 'Letöltési forrás (beállítás --url= kapcsolóval):',
        'php_version' => 'Nem lehet végrehajtani az önfrisssítési folyamatot. A minimális szükséges PHP verzió 7.4.0, te pedig a következőt használod:',
        'skipDownload' => 'Szeretnéd letölteni és kicsomagolni a legújabb verzió archív fájljait?',
        'webserver_user' => 'A webszerver felhasználód <fg=blue>[{:user}]:</> lett észlelve, ez helyes?',
        'name_webserver' => 'Kérlek, add meg a nevét a futó webszerver folyamatnak. Általában "www-data", "nginx" vagy "apache".',
        'group_webserver' => 'A webszervered érzékelve lett <fg=blue>[{:group}]:</> csoportnak. Ez helyes?',
        'group_webserver_question' => 'Kérlek, add meg a nevét a csoportnak, amely futtatja a webszerver folyamatot. Általánosan ez a azonos a felhasználóddal.',
        'are_your_sure' => 'Biztos vagy benne, hogy futtatni akarod a frissítési folyamatot?',
        'terminated' => 'Frissítési folyamat fellett függesztve egy felhasználó által.',
        'success' => 'A kezelőfelület sikeresen frissítve lett. Kérlek, ellenőrizd, hogy frissítetted a Daemon-t.',

    ],
];
