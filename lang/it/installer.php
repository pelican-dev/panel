<?php

return [
    'title' => 'Installer del Pannello',
    'requirements' => [
        'title' => 'Requisiti del Server',
        'sections' => [
            'version' => [
                'title' => 'Versione PHP',
                'or_newer' => ':version o nuove',
                'content' => 'La tua versione di PHP è :version.',
            ],
            'extensions' => [
                'title' => 'Estensioni PHP',
                'good' => 'Tutte le Estensioni di PHP necessarie sono installate.',
                'bad' => 'Mancano le seguenti estensioni PHP: :extensions',
            ],
            'permissions' => [
                'title' => 'Permessi della Cartella',
                'good' => 'Tutte le cartelle hanno i permessi richiesti.',
                'bad' => 'Le seguenti cartelle hanno permessi errati: :folders',
            ],
        ],
        'exception' => 'Alcuni requisiti sono mancanti',
    ],
    'environment' => [
        'title' => 'Ambiente',
        'fields' => [
            'app_name' => 'Nome dell\'Applicazione',
            'app_name_help' => 'Questo sarà il nome del tuo pannello.',
            'app_url' => 'Indirizzo dell\'App',
            'app_url_help' => 'Questo sarà l\'url che userai per accedere al pannello.',
            'account' => [
                'section' => 'Utente Amministratore',
                'email' => 'E-Mail',
                'username' => 'Username',
                'password' => 'Password',
            ],
        ],
    ],
    'database' => [
        'title' => 'Database',
        'driver' => 'Database Driver',
        'driver_help' => 'Il driver utilizzato per il database del pannello. Consigliamo "SQLite".',
        'fields' => [
            'host' => 'Host Database',
            'host_help' => 'L\'host del tuo database. Assicurati che sia raggiungibile.',
            'port' => 'Porta Del Database',
            'port_help' => 'La porta del tuo database.',
            'path' => 'Percorso Database',
            'path_help' => 'Il percorso del file .sqlite relativo alla cartella del database.',
            'name' => 'Nome del database',
            'name_help' => 'Il nome del database del pannello.',
            'username' => 'Nome Utente del Database',
            'username_help' => 'Il nome dell\'utente del database.',
            'password' => 'Password del Database',
            'password_help' => 'La password dell\'utente del database. Può essere vuota.',
        ],
        'exceptions' => [
            'connection' => 'Connessione al Database fallita',
            'migration' => 'Migrazioni non riuscite',
        ],
    ],
    'egg' => [
        'title' => 'Eggs',
        'no_eggs' => 'Nessun Egg disponibile.',
        'background_install_started' => 'Installazione dell\'Egg Avviato',
        'background_install_description' => 'L\'installazione di :count eggs è stata messa in coda e continuerà in background.',
        'exceptions' => [
            'failed_to_update' => 'L\'aggiornamento dell\'indice delle uova non è riuscito.',
            'no_eggs' => 'Al momento non ci sono eggs da installare.',
            'installation_failed' => 'Impossibile installare le eggs selezionate. Si prega di importarle dopo l\'installazione tramite l\'elenco.',
        ],
    ],
    'session' => [
        'title' => 'Sessione',
        'driver' => 'Driver per la sessione',
        'driver_help' => 'Il driver utilizzato per la memorizzazione delle sessioni. Consigliamo "Filesystem" o "Database".',
    ],
    'cache' => [
        'title' => 'Cache',
        'driver' => 'Driver per la cache',
        'driver_help' => 'Il driver utilizzato per la cache. Si consiglia "Filesystem".',
        'fields' => [
            'host' => 'Host Redis',
            'host_help' => 'L\'host del tuo server redis. Assicurati che sia raggiungibile.',
            'port' => 'Porta Di Redis',
            'port_help' => 'La porta del tuo server redis.',
            'username' => 'Nome Utente Redis',
            'username_help' => 'Il nome del tuo utente redis. Può essere vuoto',
            'password' => 'Password di Redis',
            'password_help' => 'La password per il tuo utente redis. Può essere vuota.',
        ],
        'exception' => 'Connessione a Redis fallita',
    ],
    'queue' => [
        'title' => 'Coda',
        'driver' => 'Driver Della Coda',
        'driver_help' => 'Il driver utilizzato per la gestione delle code. Consigliamo "Database".',
        'fields' => [
            'done' => 'Ho fatto entrambi i passi qui sotto.',
            'done_validation' => 'Devi fare entrambi i passi prima di continuare!',
            'crontab' => 'Esegui il seguente comando per configurare il tuo crontab. Nota che <code>www-data</code> è il tuo utente del webserver. Su alcuni sistemi questo nome utente potrebbe essere diverso!',
            'service' => 'Per impostare il Queue Worker, è sufficiente lanciare questo comando.',
        ],
    ],
    'exceptions' => [
        'write_env' => 'Impossibile scrivere sul file .env',
        'migration' => 'Impossibile eseguire le migrazioni',
        'create_user' => 'Impossibile creare l\'utente amministratore',
    ],
    'finish' => 'Termina',
];
