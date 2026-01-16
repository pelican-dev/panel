<?php

return [
    'title' => 'Instalátor panelu',
    'requirements' => [
        'title' => 'Požadavky na server',
        'sections' => [
            'version' => [
                'title' => 'PHP verze',
                'or_newer' => ':version nebo novější',
                'content' => 'Vaše PHP verze je :version.',
            ],
            'extensions' => [
                'title' => 'PHP rozšíření',
                'good' => 'Všechny potřebné PHP rozšíření jsou nainstalována.',
                'bad' => 'Následující PHP rozšíření chybí: :extensions',
            ],
            'permissions' => [
                'title' => 'Oprávnění složky',
                'good' => 'Všechny složky mají správné oprávnění.',
                'bad' => 'Následující složky mají špatné oprávnění: :folders',
            ],
        ],
        'exception' => 'Některé požadavky chybí',
    ],
    'environment' => [
        'title' => 'Prostředí',
        'fields' => [
            'app_name' => 'Název aplikace',
            'app_name_help' => 'Toto bude název vašeho panelu.',
            'app_url' => 'Odkaz aplikace',
            'app_url_help' => 'Toto bude adresa URL, ze které budete přistupovat k vašemu panelu.',
            'account' => [
                'section' => 'Admin uživatel',
                'email' => 'E-mail',
                'username' => 'Přezdívka',
                'password' => 'Heslo',
            ],
        ],
    ],
    'database' => [
        'title' => 'Databáze',
        'driver' => 'Ovladač databáze',
        'driver_help' => 'Ovladač používaný pro panelovou databázi. Doporučujeme „SQLite“.',
        'fields' => [
            'host' => 'Hostitel databáze',
            'host_help' => 'Hostitel vaší databáze. Ověřte, že se na něj lze dostat.',
            'port' => 'Port databáze',
            'port_help' => 'Port vaší databáze.',
            'path' => 'Cesta k databázi',
            'path_help' => 'Cesta vašeho .sqlite souboru vzhledem ke složce databáze.',
            'name' => 'Název databáze',
            'name_help' => 'Název databáze panelu.',
            'username' => 'Uživatelské jméno k databázi',
            'username_help' => 'Jméno uživatele vaší databáze.',
            'password' => 'Heslo databáze',
            'password_help' => 'Heslo uživatele databáze. Může být prázdné.',
        ],
        'exceptions' => [
            'connection' => 'Spojení s databází se nezdařilo',
            'migration' => 'Přesun dat se nezdařil',
        ],
    ],
    'egg' => [
        'title' => 'Vejce',
        'no_eggs' => 'Žádná vejce nejsou k dispozici',
        'background_install_started' => 'Instalace vejce byla spuštěna',
        'background_install_description' => 'Instalace :count vajec byla zařazena do fronty a bude pokračovat na pozadí.',
        'exceptions' => [
            'failed_to_update' => 'Nepodařilo se aktualizovat index vejce',
            'no_eggs' => 'V tuto chvíli nejsou k dispozici žádná vejce.',
            'installation_failed' => 'Nepodařilo se nainstalovat vybraná vejce. Importujte je prosím po instalaci přes seznam vajec.',
        ],
    ],
    'session' => [
        'title' => 'Relace',
        'driver' => 'Ovladač relace',
        'driver_help' => 'Ovladač používaný pro ukládání relací. Doporučujeme "Souborový systém" nebo "Databáze".',
    ],
    'cache' => [
        'title' => 'Mezipaměť',
        'driver' => 'Ovladač mezipaměti',
        'driver_help' => 'Ovladač používaný pro ukládání do mezipaměti. Doporučujeme "Souborový systém".',
        'fields' => [
            'host' => 'Redis hostitel',
            'host_help' => 'Hostitel vašeho redis serveru. Ověřte, že se na něj lze dostat.',
            'port' => 'Redis port',
            'port_help' => 'Port vašeho redis serveru.',
            'username' => 'Redis uživatelské jméno',
            'username_help' => 'Jméno redis uživatele. Může být prázdné',
            'password' => 'Redis heslo',
            'password_help' => 'Heslo redis uživatele. Může být prázdné.',
        ],
        'exception' => 'Spojení s redis se nezdařilo',
    ],
    'queue' => [
        'title' => 'Fronta',
        'driver' => 'Řadič fronty',
        'driver_help' => 'Řadič používaný pro udržení fronty. Doporučujeme "Databáze".',
        'fields' => [
            'done' => 'Udělal jsem oba kroky níže.',
            'done_validation' => 'Před pokračováním musíte udělat oba kroky!',
            'crontab' => 'Spusťte následující příkaz pro nastavení crontab. Všimněte si, že <code>www-data</code> je váš uživatel webového serveru. Na některých systémech se toto uživatelské jméno může lišit!',
            'service' => 'Pro nastavení služby workeru ve frontě stačí spustit následující příkaz.',
        ],
    ],
    'exceptions' => [
        'write_env' => 'Nelze zapsat do souboru .env',
        'migration' => 'Migrace nelze spustit',
        'create_user' => 'Nelze vytvořit admin uživatele',
    ],
    'next_step' => 'Další krok',
    'finish' => 'Dokončit',
];
