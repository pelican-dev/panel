<?php

return [
    'email' => [
        'title' => 'Aktualisiere deine E-Mail',
        'updated' => 'Deine E-Mail-Adresse wurde aktualisiert.',
    ],
    'password' => [
        'title' => 'Ändere dein Passwort',
        'requirements' => 'Dein neues Passwort sollte mindestens 8 Zeichen lang sein.',
        'updated' => 'Dein Passwort wurde aktualisiert.',
    ],
    'two_factor' => [
        'button' => '2-Faktor-Authentifizierung konfigurieren',
        'disabled' => 'Zwei-Faktor-Authentifizierung wurde auf deinem Konto deaktiviert. Du wirst beim Anmelden nicht mehr aufgefordert, einen Token anzugeben.',
        'enabled' => 'Zwei-Faktor-Authentifizierung wurde auf deinem Konto aktiviert! Ab sofort musst du beim Einloggen den von deinem Gerät generierten Code zur Verfügung stellen.',
        'invalid' => 'Der angegebene Token ist ungültig.',
        'setup' => [
            'title' => 'Zwei-Faktor-Authentifizierung einrichten',
            'help' => 'Code kann nicht gescannt werden? Gebe den unteren Code in deine Anwendung ein:',
            'field' => 'Token eingeben',
        ],
        'disable' => [
            'title' => 'Zwei-Faktor-Authentifizierung deaktivieren',
            'field' => 'Token eingeben',
        ],
    ],
];
