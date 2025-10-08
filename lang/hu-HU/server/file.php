<?php

return [
    'title' => 'Fájlok',
    'name' => 'Név',
    'size' => 'Méret',
    'modified_at' => 'Módosítva',
    'actions' => [
        'open' => 'Nyitva',
        'download' => 'Letöltés',
        'copy' => [
            'title' => 'Másolás',
            'notification' => 'Fájl másolva',
        ],
        'upload' => [
            'title' => 'Feltöltés',
            'from_files' => 'Fájlok feltöltése',
            'from_url' => 'Feltöltés URL-ből',
            'url' => 'URL',
        ],
        'rename' => [
            'title' => 'Átnevezés',
            'file_name' => 'Fájlnév',
            'notification' => 'Fájl átnevezve',
        ],
        'move' => [
            'title' => 'Mozgatás',
            'directory' => 'Mappa',
            'directory_hint' => 'Add meg az új könyvtárat a jelenlegi könyvtárhoz képest.
',
            'new_location' => 'Új hely',
            'new_location_hint' => 'Add meg a fájl vagy mappa helyét a jelenlegi könyvtárhoz képest.
',
            'notification' => 'A fájl átmozgatva',
            'bulk_notification' => ':count fájl át lett helyezve ide: :directory',
        ],
        'permissions' => [
            'title' => 'Jogosultságok',
            'read' => 'Olvasás',
            'write' => 'Írás',
            'execute' => 'Futtatás',
            'owner' => 'Tulajdonos',
            'group' => 'Csoport',
            'public' => 'Nyilvános',
            'notification' => 'A jogosultságok :mode értékre változtak.
',
        ],
        'archive' => [
            'title' => 'Archívum',
            'archive_name' => 'Archívum neve',
            'notification' => 'Archívum létrehozva',
        ],
        'unarchive' => [
            'title' => 'Visszaállítás archívumból',
            'notification' => 'Visszaállítás befejezve',
        ],
        'new_file' => [
            'title' => 'Új fájl',
            'file_name' => 'Új fájl neve',
            'syntax' => 'Szintaxis kiemelés',
            'create' => 'Létrehozás',
        ],
        'new_folder' => [
            'title' => 'Új mappa',
            'folder_name' => 'Új fájl neve',
        ],
        'global_search' => [
            'title' => 'Globális keresés',
            'search_term' => 'Kifejezés keresése',
            'search_term_placeholder' => 'Adj meg egy keresési kifejezést, pl. *.txt.',
            'search' => 'Keresés',
            'search_for_term' => 'Keresés :term',
        ],
        'delete' => [
            'notification' => 'Fájl törölve',
            'bulk_notification' => ':count fájl törölve lett.
',
        ],
        'edit' => [
            'title' => ':file szerkesztése',
            'save_close' => 'Mentés és bezárás',
            'save' => 'Mentés',
            'cancel' => 'Mégse',
            'notification' => 'Fájl mentve',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> túl nagy!',
            'body' => 'Maximum :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> nem található!',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> a könyvtár',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> már létezik!',
        ],
        'files_node_error' => [
            'title' => 'A fájlt nem lehet betölteni.',
        ],
        'pelicanignore' => [
            'title' => 'Már szerkezted a  <code>.pelicanignore</code> fájlt!',
            'body' => 'Bármely itt felsorolt fájl vagy könyvtár ki lesz zárva a biztonsági mentésekből. A helyettesítő karakterek használhatók csillaggal (<code>*</code>).
<br>Egy korábbi szabályt meg lehet szüntetni azzal, hogy felülírod egy felkiáltójel (<code>!</code>) hozzáfűzésével.',
        ],
    ],
];
