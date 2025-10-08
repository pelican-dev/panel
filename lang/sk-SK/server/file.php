<?php

return [
    'title' => 'Súbory',
    'name' => 'Meno',
    'size' => 'Veľkosť',
    'modified_at' => 'Upravené',
    'actions' => [
        'open' => 'Otvoriť',
        'download' => 'Stiahnuť',
        'copy' => [
            'title' => 'Skopírovať',
            'notification' => 'Súbor skopírovaný',
        ],
        'upload' => [
            'title' => 'Nahrať',
            'from_files' => 'Nahrať súbory',
            'from_url' => 'Nahrať z URL',
            'url' => 'URL',
        ],
        'rename' => [
            'title' => 'Premenovať',
            'file_name' => 'Názov súboru',
            'notification' => 'Súbor premenovaný',
        ],
        'move' => [
            'title' => 'Presunúť',
            'directory' => 'Adresár',
            'directory_hint' => 'Zadajte nový adresár, relatívne k súčasnému adresáru.',
            'new_location' => 'Nové umiestnenie',
            'new_location_hint' => 'Zadajte umiestnenie tohto súboru alebo priečinku, relatívne k súčasnému adresáru.',
            'notification' => 'Súbor presunutý',
            'bulk_notification' => ':count Súbory presunuté do :directory',
        ],
        'permissions' => [
            'title' => 'Oprávnenia',
            'read' => 'Prečítať',
            'write' => 'Zapísať',
            'execute' => 'Spustiť',
            'owner' => 'Majiteľ',
            'group' => 'Skupina',
            'public' => 'Verejné',
            'notification' => 'Oprávnenia zmenené na :mode',
        ],
        'archive' => [
            'title' => 'Archív',
            'archive_name' => 'Názov archívu',
            'notification' => 'Archív vytvorený',
        ],
        'unarchive' => [
            'title' => 'Rozbaliť',
            'notification' => 'Rozbalovanie dokončené',
        ],
        'new_file' => [
            'title' => 'Nový súbor',
            'file_name' => 'Názov nového súboru',
            'syntax' => 'Zvýrazňovanie syntaxe',
            'create' => 'Vytvoriť',
        ],
        'new_folder' => [
            'title' => 'Nový priečinok',
            'folder_name' => 'Názov nového priečinku',
        ],
        'global_search' => [
            'title' => 'Globálne vyhľadávanie',
            'search_term' => 'Hľadaný výraz',
            'search_term_placeholder' => 'Zadajte hľadaný výraz, napr. *.txt',
            'search' => 'Hľadať',
            'search_for_term' => 'Hľadať :term',
        ],
        'delete' => [
            'notification' => 'Súbor vymazaný',
            'bulk_notification' => ':count súborov bolo vymazaných',
        ],
        'edit' => [
            'title' => 'Upravuje sa: :file',
            'save_close' => 'Uložiť a zatvoriť',
            'save' => 'Uložiť',
            'cancel' => 'Zrušiť',
            'notification' => 'Súbor uložený',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> je príliš veľký!',
            'body' => 'Max je :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> nebol nájdený!',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> je priečinok',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> už existuje!',
        ],
        'files_node_error' => [
            'title' => 'Nepodarilo sa načítať súbory!',
        ],
        'pelicanignore' => [
            'title' => 'Upravujete <code>.pelicanignore</code> súbor!',
            'body' => 'Všetky súbory alebo adresáre uvedené tu budú vylúčené zo záloh. Zástupné znaky sú podporované pomocou hviezdičky (<code>*</code>).<br>Predchádzajúce pravidlo môžete zrušiť pridaním výkričníka (<code>!</code>) na začiatok.',
        ],
    ],
];
