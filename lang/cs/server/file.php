<?php

return [
    'title' => 'Soubory',
    'name' => 'Název',
    'size' => 'Velikost',
    'modified_at' => 'Změněno v',
    'actions' => [
        'open' => 'Otevřít',
        'download' => 'Stáhnout',
        'copy' => [
            'title' => 'Kopírovat',
            'notification' => 'Soubor zkopírován',
        ],
        'upload' => [
            'title' => 'Nahrát',
            'from_files' => 'Nahrát soubory',
            'from_url' => 'Nahrát z URL',
            'url' => 'URL',
            'drop_files' => 'Přetáhněte soubory k nahrání',
            'success' => 'Soubory úspěšně nahrány',
            'failed' => 'Nahrávání se nezdařilo.',
            'header' => 'Nahrávání souborů',
            'error' => 'Při nahrávání %s došlo k chybě',
        ],
        'rename' => [
            'title' => 'Přejmenovat',
            'file_name' => 'Název souboru',
            'notification' => 'Soubor byl přejmenován',
        ],
        'move' => [
            'title' => 'Přesunout',
            'directory' => 'Adresář',
            'directory_hint' => 'Zadejte nový adresář vzhledem k aktuálnímu adresáři.',
            'new_location' => 'Nové místo',
            'new_location_hint' => 'Zadejte umístění tohoto souboru nebo složky vzhledem k aktuální složce.',
            'notification' => 'Soubor byl přesunut',
            'bulk_notification' => ':count Soubory byly přesunuty do :directory',
        ],
        'permissions' => [
            'title' => 'Oprávnění',
            'read' => 'Čtení',
            'write' => 'Zápis',
            'execute' => 'Vykonat',
            'owner' => 'Vlastník',
            'group' => 'Skupina',
            'public' => 'Veřejné',
            'notification' => 'Oprávnění změněna na :mode',
        ],
        'archive' => [
            'title' => 'Archivovat',
            'archive_name' => 'Název archivu',
            'notification' => 'Archiv vytvořen',
            'extension' => 'Rozšíření',
        ],
        'unarchive' => [
            'title' => 'Odarchivovat',
            'notification' => 'Odarchivování dokončeno',
        ],
        'new_file' => [
            'title' => 'Nový soubor',
            'file_name' => 'Název nového souboru',
            'syntax' => 'Zvýraznění syntaxe',
            'create' => 'Vytvořit',
        ],
        'new_folder' => [
            'title' => 'Nová složka',
            'folder_name' => 'Název nové složky',
        ],
        'nested_search' => [
            'title' => 'Vnořené hledání',
            'search_term' => 'Hledaný výraz',
            'search_term_placeholder' => 'Zadejte hledaný výraz, například *.txt',
            'search' => 'Hledat',
            'search_for_term' => 'Hledaný výraz',
        ],
        'delete' => [
            'notification' => 'Soubor byl smazán',
            'bulk_notification' => ':count souborů byly smazány',
        ],
        'edit' => [
            'title' => 'Upravení: :file',
            'save_close' => 'Uložit & zavřít',
            'save' => 'Uložit',
            'cancel' => 'Zrušit',
            'notification' => 'Soubor uložen',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>: jméno</code> je příliš dlouhé!',
            'body' => 'Maximum je :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> nebylo nalezeno!',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> je složka',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> již existuje!',
        ],
        'files_node_error' => [
            'title' => 'Nelze načíst soubory!',
        ],
        'pelicanignore' => [
            'title' => 'Upravujete <code>.pelicanignore</code> soubor!',
            'body' => 'Všechny soubory nebo adresáře uvedené v tomto seznamu budou ze zálohování vyloučeny. Podporovány jsou zástupné znaky pomocí hvězdičky (<code>*</code>).<br>Předchozí pravidlo můžete zrušit přidáním vykřičníku (<code>!</code>) na začátek.',
        ],
    ],
];
