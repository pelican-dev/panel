<?php

return [
    'title' => 'Instalator panelu',
    'requirements' => [
        'title' => 'Wymagania serwera',
        'sections' => [
            'version' => [
                'title' => 'Wersja PHP',
                'or_newer' => ':version lub nowsza',
                'content' => 'Twoja wersja PHP to :version.',
            ],
            'extensions' => [
                'title' => 'Rozszerzenia PHP',
                'good' => 'Wszystkie potrzebne rozszerzenia PHP są zainstalowane.',
                'bad' => 'Brakuje następujących rozszerzeń PHP: :extensions',
            ],
            'permissions' => [
                'title' => 'Uprawnienia folderów',
                'good' => 'Wszystkie foldery mają odpowiednie uprawnienia.',
                'bad' => 'Następujące foldery mają nieprawidłowe uprawnienia: :folders',
            ],
        ],
        'exception' => 'Brakuje niektórych wymogów',
    ],
    'environment' => [
        'title' => 'Środowisko',
        'fields' => [
            'app_name' => 'Nazwa aplikacji',
            'app_name_help' => 'To będzie nazwa Twojego panelu.',
            'app_url' => 'Adres URL aplikacji',
            'app_url_help' => 'Będzie to adres URL, pod którym uzyskasz dostęp do swojego Panelu.',
            'account' => [
                'section' => 'Administrator',
                'email' => 'Adres e-mail',
                'username' => 'Nazwa użytkownika',
                'password' => 'Hasło',
            ],
        ],
    ],
    'database' => [
        'title' => 'Baza danych',
        'driver' => 'Sterownik bazy danych',
        'driver_help' => 'Sterownik używany do bazy danych panelu. Zalecamy "SQLite".',
        'fields' => [
            'host' => 'Host bazy danych',
            'host_help' => 'Host bazy danych. Upewnij się, że jest osiągalny.',
            'port' => 'Port bazy danych',
            'port_help' => 'Port twojej bazy danych',
            'path' => 'Ścieżka do bazy danych',
            'path_help' => 'Ścieżka do pliku .sqlite względem folderu bazy danych.',
            'name' => 'Nazwa bazy danych',
            'name_help' => 'Nazwa bazy danych panelu.',
            'username' => 'Nazwa użytkownika bazy danych',
            'username_help' => 'Nazwa użytkownika bazy danych.',
            'password' => 'Hasło do bazy danych',
            'password_help' => 'Hasło użytkownika bazy danych. Może być puste.',
        ],
        'exceptions' => [
            'connection' => 'Nie udało się nawiązać połączenia z bazą danych.',
            'migration' => 'Migracja nie powiodła się',
        ],
    ],
    'egg' => [
        'title' => 'Jajka',
        'no_eggs' => 'Brak jajek',
        'background_install_started' => 'Rozpoczęto instalację jajka',
        'background_install_description' => 'Instalacja :count eggs została umieszczona w kolejce i będzie kontynuowana w tle.',
        'exceptions' => [
            'failed_to_update' => 'Nie udało się zaktualizować indeksu egg',
            'no_eggs' => 'W tej chwili nie ma dostępnych jajek do zainstalowania.',
            'installation_failed' => 'Nie udało się zainstalować wybranych jajek. Proszę je zaimportować po instalacji z listy jajek.',
        ],
    ],
    'session' => [
        'title' => 'Sesja',
        'driver' => 'Sterownik sesji',
        'driver_help' => 'Sterownik używany do przechowywania sesji. Zalecamy "System plików" lub "Baza danych".',
    ],
    'cache' => [
        'title' => 'Pamięć podręczna',
        'driver' => 'Sterownik pamięci podręcznej',
        'driver_help' => 'Sterownik używany do buforowania. Zalecamy „System plików”.',
        'fields' => [
            'host' => 'Host Redis',
            'host_help' => 'Host serwera redis. Upewnij się, że jest osiągalny.',
            'port' => 'Port Redis',
            'port_help' => 'Port serwera redis.',
            'username' => 'Nazwa użytkownika Redis',
            'username_help' => 'Nazwa użytkownika redis. Może być pusta.',
            'password' => 'Hasło Redis',
            'password_help' => 'Hasło dla użytkownika redis. Może być puste.',
        ],
        'exception' => 'Nie udało się nawiązać połączenia z Redis',
    ],
    'queue' => [
        'title' => 'Kolejka',
        'driver' => 'Sterownik kolejki',
        'driver_help' => 'Sterownik używany do obsługi kolejek. Zalecamy „Baza danych”.',
        'fields' => [
            'done' => 'Wykonałem oba poniższe kroki.',
            'done_validation' => 'Przed kontynuowaniem należy wykonać oba kroki!',
            'crontab' => 'Uruchom poniższe polecenie, aby skonfigurować crontab. Pamiętaj, że <code>www-data</code> to użytkownik Twojego serwera WWW
. W niektórych systemach nazwa ta może być inna!',
            'service' => 'Aby skonfigurować usługę kolejki zadań, wystarczy uruchomić następujące polecenie.',
        ],
    ],
    'exceptions' => [
        'write_env' => 'Nie można zapisać pliku .env',
        'migration' => 'Nie można uruchomić migracji',
        'create_user' => 'Nie można utworzyć użytkownika administratora.',
    ],
    'next_step' => 'Następny krok',
    'finish' => 'Zakończ',
];
