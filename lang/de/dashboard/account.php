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
        'disabled' => 'Zwei-Faktor-Authentifizierung wurde auf Deinem Konto deaktiviert. Du wirst beim Anmelden nicht mehr aufgefordert, ein Token anzugeben.',
        'enabled' => 'Zwei-Faktor-Authentifizierung wurde auf deinem Konto aktiviert! Ab sofort müssen Sie beim Einloggen den von Ihrem Gerät generierten Code zur Verfügung stellen.',
        'invalid' => 'Der angegebene Token ist ungültig.',
        'setup' => [
            'title' => 'Zwei-Faktor-Authentifizierung einrichten',
            'help' => 'Code kann nicht gescannt werden? Gebe den Code unten in Deine Anwendung ein:',
            'field' => 'Token eingeben',
        ],
        'disable' => [
            'title' => 'Zwei-Faktor-Authentifizierung deaktivieren',
            'field' => 'Token eingeben',
        ],
    ],
];
