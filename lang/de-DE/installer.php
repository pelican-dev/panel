<?php

return [
    'title' => 'Panel-Installation',
    'requirements' => [
        'title' => 'Server Anforderungen',
        'sections' => [
            'version' => [
                'title' => 'PHP Version',
                'or_newer' => ':version oder neuer',
                'content' => 'Ihre PHP Version ist :version.',
            ],
            'extensions' => [
                'title' => 'PHP Erweiterungen',
                'good' => 'Alle erforderlichen PHP-Erweiterungen sind installiert.',
                'bad' => 'Die folgenden PHP-Erweiterungen fehlen: :extensions',
            ],
            'permissions' => [
                'title' => 'Ordnerberechtigungen',
                'good' => 'Alle Ordner haben die richtigen Berechtigungen.',
                'bad' => 'Die folgenden Ordner haben falsche Berechtigungen: :folders',
            ],
        ],
        'exception' => 'Einige Anforderungen fehlen.',
    ],
    'environment' => [
        'title' => 'Umgebung',
        'fields' => [
            'app_name' => 'App Name',
            'app_name_help' => 'Dies wird der Name Ihres Panels sein.',
            'app_url' => 'App URL',
            'app_url_help' => 'Dies wird die URL sein, über die Sie auf Ihr Panel zugreifen können.',
            'account' => [
                'section' => 'Administrator',
                'email' => 'E-Mail',
                'username' => 'Benutzername',
                'password' => 'Passwort',
            ],
        ],
    ],
    'database' => [
        'title' => 'Datenbank',
        'driver' => 'Datenbank Treiber',
        'driver_help' => 'Der für die Panel-Datenbank verwendete Treiber. Wir empfehlen „SQLite“.',
        'fields' => [
            'host' => 'Datenbank Host',
            'host_help' => 'Der Host Ihrer Datenbank. Stellen Sie sicher, dass er erreichbar ist.',
            'port' => 'Datenbank-Port',
            'port_help' => 'Der Port Ihrer Datenbank.',
            'path' => 'Datenbankpfad',
            'path_help' => 'Der Pfad Ihrer .sqlite-Datei relativ zum Datenbankordner.',
            'name' => 'Datenbank Name',
            'name_help' => 'Der Name der Panel-Datenbank.',
            'username' => 'Datenbank Benutzername',
            'username_help' => 'Der Name Ihres Datenbankbenutzers.',
            'password' => 'Datenbank Passwort',
            'password_help' => 'Das Passwort Ihres Datenbankbenutzers. Kann leer sein.',
        ],
        'exceptions' => [
            'connection' => 'Datenbankverbindung fehlgeschlagen',
            'migration' => 'Migrationen fehlgeschlagen',
        ],
    ],
    'session' => [
        'title' => 'Sitzung',
        'driver' => 'Sitzungstreiber',
        'driver_help' => 'Der für die Speicherung von Sitzungen verwendete Treiber. Wir empfehlen „Dateisystem“ oder „Datenbank“.',
    ],
    'cache' => [
        'title' => 'Cache',
        'driver' => 'Cache-Treiber',
        'driver_help' => 'Der für das Caching verwendete Treiber. Wir empfehlen „Filesystem“.',
        'fields' => [
            'host' => 'Redis-Host',
            'host_help' => 'Der Host Ihres Redis-Servers. Stellen Sie sicher, dass er erreichbar ist.',
            'port' => 'Redis-Port',
            'port_help' => 'Der Port Ihres Redis-Servers.',
            'username' => 'Redis Benutzername',
            'username_help' => 'Der Name Ihres Redis-Benutzers. Kann leer bleiben.',
            'password' => 'Redis-Passwort',
            'password_help' => 'Das Passwort für Ihren Redis-Benutzer. Kann leer sein.',
        ],
        'exception' => 'Redis connection failed',
    ],
    'queue' => [
        'title' => 'Warteschlange',
        'driver' => 'Warteschlangentreiber',
        'driver_help' => 'Der für die Verwaltung von Warteschlangen verwendete Treiber. Wir empfehlen „Datenbank“.',
        'fields' => [
            'done' => 'Ich habe beide unten aufgeführten Schritte durchgeführt.',
            'done_validation' => 'Sie müssen beide Schritte ausführen, bevor Sie fortfahren können!',
            'crontab' => 'Führen Sie den folgenden Befehl aus, um Ihre crontab einzurichten. Beachten Sie, dass <code>www-data</code> Ihr Webserver-Benutzer ist. Auf einigen Systemen kann dieser Benutzername abweichen!',
            'service' => 'Um den Queue-Worker-Dienst einzurichten, müssen Sie lediglich den folgenden Befehl ausführen.',
        ],
    ],
    'exceptions' => [
        'write_env' => '',
        'migration' => 'Migrationen konnten nicht ausgeführt werden',
        'create_user' => 'Admin-Benutzer konnte nicht erstellt werden',
    ],
    'next_step' => 'Nächster Schritt',
    'finish' => 'Fertigstellen',
];
