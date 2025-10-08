<?php

return [
    'title' => 'Paneel installatie wizard',
    'requirements' => [
        'title' => 'Server benodigdheden',
        'sections' => [
            'version' => [
                'title' => 'PHP versie',
                'or_newer' => ':version of nieuwer',
                'content' => 'Jouw PHP versie is :version.',
            ],
            'extensions' => [
                'title' => 'PHP extensies',
                'good' => 'Alle benodigde PHP extensies zijn geïnstalleerd.',
                'bad' => 'De volgende PHP extensies zijn niet geïnstalleerd: :extensions',
            ],
            'permissions' => [
                'title' => 'Bestandsmap permissies',
                'good' => 'Alle bestandsmappen hebben de juiste permissies.',
                'bad' => 'De volgende bestandsmappen hebben niet de juiste permissies: :permissions',
            ],
        ],
        'exception' => 'Sommige benodigdheden missen',
    ],
    'environment' => [
        'title' => 'Omgeving',
        'fields' => [
            'app_name' => 'Applicatie naam',
            'app_name_help' => 'Dit wordt de naam van je paneel.',
            'app_url' => 'App URL',
            'app_url_help' => 'Dit wordt de url vanaf waar je het paneel kan bezoeken.',
            'account' => [
                'section' => 'Administrator',
                'email' => 'E-mail',
                'username' => 'Gebruikersnaam',
                'password' => 'Wachtwoord',
            ],
        ],
    ],
    'database' => [
        'title' => 'Database',
        'driver' => 'Database stuurprogramma',
        'driver_help' => 'Het stuurprogramma dat wordt gebruikt voor de database van het paneel. We raden "SQLite" aan.',
        'fields' => [
            'host' => 'Database server host',
            'host_help' => 'De hostnaam van je database. Zorg ervoor dat deze toegankelijk is.',
            'port' => 'Database poort',
            'port_help' => 'De poort van je database server.',
            'path' => 'Database pad',
            'path_help' => 'Het relatieve pad dat leid naar je .sqlite bestand in de database map',
            'name' => 'Databasenaam',
            'name_help' => 'De naam van de database van het paneel',
            'username' => 'Database gebruiker',
            'username_help' => 'De naam van de database gebruiker.',
            'password' => 'Database wachtwoord',
            'password_help' => 'Het wachtwoord van je database gebruiker. Deze kan leeg zijn.',
        ],
        'exceptions' => [
            'connection' => 'Database verbinding mislukt',
            'migration' => 'Migraties mislukt',
        ],
    ],
    'session' => [
        'title' => 'Sessie',
        'driver' => 'Sessie stuurprogramma',
        'driver_help' => 'Het stuurprogramma dat wordt gebruikt om sessies op te slaan. We raden "Filesystem" of "Database" aan.',
    ],
    'cache' => [
        'title' => 'Cache',
        'driver' => 'Cache stuurprogramma',
        'driver_help' => 'Het stuurprogramma dat wordt gebruikt voor caching. We raden "Filesystem" aan.',
        'fields' => [
            'host' => 'Redis host',
            'host_help' => 'De hostnaam van je redis instantie. Zorg ervoor dat deze toegankelijk is.',
            'port' => 'Redis poort',
            'port_help' => 'De poort van je redis server.',
            'username' => 'Redis gebruikersnaam',
            'username_help' => 'De naam van je redis gebruiker. Deze kan leeg zijn.',
            'password' => 'Redis wachtwoord',
            'password_help' => 'Het wachtwoord van je redis gebruiker. Deze kan leeg zijn.',
        ],
        'exception' => 'Redis verbinding mislukt',
    ],
    'queue' => [
        'title' => 'Wachtrij',
        'driver' => 'Wachtrij stuurprogramma',
        'driver_help' => 'Het stuurprogramma dat wordt gebruikt om de wachtrij af te handelen. We randen "Database" aan.',
        'fields' => [
            'done' => 'Ik heb beide stappen hieronder doorlopen.',
            'done_validation' => 'Je moet beide stappen doorlopen om door te gaan.',
            'crontab' => 'Voer het volgende commando uit om uw crontab-configuratie in te stellen. Let op dat <code>www-data</code> uw webservergebruiker is. Op sommige systemen is deze gebruikersnaam misschien anders!',
            'service' => 'Om de wachtrij worker service op te zetten moet je de volgende opdracht uitvoeren.',
        ],
    ],
    'exceptions' => [
        'write_env' => 'Kan niet schrijven naar .env bestand',
        'migration' => 'Migraties konden niet worden uitgevoerd',
        'create_user' => 'Kon geen administrator maken',
    ],
    'next_step' => 'Volgende stap',
    'finish' => 'Afronden',
];
