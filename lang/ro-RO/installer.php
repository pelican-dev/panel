<?php

return [
    'title' => 'Instalator panou',
    'requirements' => [
        'title' => 'Cerințele serverului',
        'sections' => [
            'version' => [
                'title' => 'Versiune PHP',
                'or_newer' => ':version sau mai nouă',
                'content' => 'Versiunea ta de PHP este :version.',
            ],
            'extensions' => [
                'title' => 'Extensii PHP',
                'good' => 'Toate extensiile PHP necesare sunt instalate.',
                'bad' => 'Următoarele extensii PHP lipsesc: :extensions',
            ],
            'permissions' => [
                'title' => 'Permisiuni Director',
                'good' => 'Toate directoarele au permisiunile corecte.',
                'bad' => 'Următoarele directoare au permisiuni incorecte: :folders',
            ],
        ],
        'exception' => 'Unele cerințe lipsesc',
    ],
    'environment' => [
        'title' => 'Mediu',
        'fields' => [
            'app_name' => 'Numele aplicației',
            'app_name_help' => 'Acesta va fi numele panoului tău.',
            'app_url' => 'URL-ul aplicației',
            'app_url_help' => 'Aceasta va fi URL-ul de la care accesezi panoul tău.',
            'account' => [
                'section' => 'Utilizator admin',
                'email' => 'E-mail',
                'username' => 'Nume de utilizator',
                'password' => 'Parolă',
            ],
        ],
    ],
    'database' => [
        'title' => 'Bază de Date',
        'driver' => 'Driver bază de date',
        'driver_help' => 'Driver-ul folosit pentru baza de date a panoului. Recomandăm "SQLite".',
        'fields' => [
            'host' => 'Gazda bazei de date',
            'host_help' => 'Gazda bazei tale de date. Asigură-te că este accesibilă.',
            'port' => 'Portul bazei de date',
            'port_help' => 'Portul bazei tale de date.',
            'path' => 'Calea bazei de date',
            'path_help' => 'Calea fișierului tău .sqlite, relativă la folderul bazei de date.',
            'name' => 'Numele bazei de date',
            'name_help' => 'Numele bazei de date a panoului.',
            'username' => 'Numele utilizatorului bazei de date',
            'username_help' => 'Numele utilizatorului bazei tale de date.',
            'password' => 'Parola bazei de date',
            'password_help' => 'Parola utilizatorului bazei de date. Poate fi goală.',
        ],
        'exceptions' => [
            'connection' => 'Conexiunea bazei de date a eșuat',
            'migration' => 'Migrarea a eșuat',
        ],
    ],
    'session' => [
        'title' => 'Sesiune',
        'driver' => 'Driver Sesiune',
        'driver_help' => 'Driver-ul folosit pentru stocarea sesiunilor. Recomandăm "Filesystem" sau "Database".',
    ],
    'cache' => [
        'title' => 'Cache',
        'driver' => 'Driver Cache',
        'driver_help' => 'Driver-ul folosit pentru caching. Recomandăm "Filesystem".',
        'fields' => [
            'host' => 'Gazda Redis',
            'host_help' => 'Gazda serverului tău Redis. Asigură-te că este accesibilă.',
            'port' => 'Port Redis',
            'port_help' => 'Portul serverului tău de redis.',
            'username' => 'Nume Utilizator Redis',
            'username_help' => 'Numele utilizatorului de redis. Poate fi gol',
            'password' => 'Parolă Redis',
            'password_help' => 'Parola pentru utilizatorul de redis. Poate fi goală.',
        ],
        'exception' => 'Conexiunea la Redis a eșuat',
    ],
    'queue' => [
        'title' => 'Listă de așteptare',
        'driver' => 'Driver pentru lista de așteptare',
        'driver_help' => 'Driver-ul folosit pentru gestionarea listei de așteptare. Recomandăm "Database".',
        'fields' => [
            'done' => 'Am efectuat ambii pași de mai jos.',
            'done_validation' => 'Trebuie să faci ambii pași înainte de a continua!',
            'crontab' => 'Rulează următoarea comandă pentru a-ți configura crontab-ul. Ține cont că <code>www-data</code> este utilizatorul serverului tău web. Pe unele sisteme, acest nume de utilizator poate fi diferit!',
            'service' => 'Pentru a configura serviciul queue worker, trebuie doar să rulezi următoarea comandă.',
        ],
    ],
    'exceptions' => [
        'write_env' => 'Nu s-a putut scrie în fișierul .env',
        'migration' => 'Nu s-au putut rula migrările',
        'create_user' => 'Nu s-a putut crea utilizatorul admin',
    ],
    'next_step' => 'Următorul Pas',
    'finish' => 'Finalizare',
];
