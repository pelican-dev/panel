<?php

return [
    'nav_title' => 'Pluginy',
    'model_label' => 'Plugin',
    'model_label_plural' => 'Pluginy',

    'name' => 'Název',
    'update_available' => 'Aktualizace pro tento plugin je dostupná',
    'author' => 'Autor',
    'version' => 'Verze',
    'category' => 'Kategorie',
    'status' => 'Stav',
    'visit_website' => 'Navštívit webovou stránku',
    'settings' => 'Nastavení',
    'install' => 'Nainstalovat',
    'uninstall' => 'Odinstalovat',
    'update' => 'Aktualizovat',
    'enable' => 'Povolit',
    'disable' => 'Zakázat',
    'import_from_file' => 'Importovat ze souboru',
    'import_from_url' => 'Importovat z URL',
    'no_plugins' => 'Žádné pluginy',
    'all' => 'Vše',
    'change_load_order' => 'Změnit pořadí načítání',
    'apply_load_order' => 'Použít pořadí načítání',

    'enable_theme_modal' => [
        'heading' => 'Motiv je již povolen',
        'description' => 'Již máte jeden motiv povolený. Povolení více motivů může mít za následek vizuální chyby. Chcete pokračovat?',
    ],

    'status_enum' => [
        'not_installed' => 'Nenainstalováno',
        'disabled' => 'Deaktivováno',
        'enabled' => 'Aktivováno',
        'errored' => 'Chyba',
        'incompatible' => 'Nekompatibilní',
    ],

    'category_enum' => [
        'plugin' => 'Plugin',
        'theme' => 'Motiv',
        'language' => 'Jazykový balíček',
    ],

    'notifications' => [
        'installed' => 'Plugin nainstalován!',
        'install_error' => 'Plugin nelze nainstalovat',
        'uninstalled' => 'Plugin odinstalován',
        'uninstall_error' => 'Plugin nelze odinstalovat',
        'deleted' => 'Plugin byl smazán',
        'updated' => 'Plugin byl aktualizován',
        'update_error' => 'Plugin nelze aktualizovat',
        'enabled' => 'Plugin aktivován',
        'disabled' => 'Plugin deaktivován',
        'imported' => 'Plugin importován',
        'import_exists' => 'Plugin s tímto ID již existuje',
        'import_failed' => 'Plugin nelze importovat',
    ],
];
