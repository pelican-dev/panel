<?php

return [
    'title' => 'Kontoübersicht',
    'email' => [
        'title' => 'E-Mail-Adresse aktualisieren',
        'button' => 'E-Mail aktualisieren',
        'updated' => 'Deine primäre E-Mail wurde aktualisiert.',
    ],
    'password' => [
        'title' => 'Passwort ändern',
        'button' => 'Passwort ändern',
        'requirements' => 'Dein neues Passwort sollte mindestens 8 Zeichen lang und einzigartig für diese Website sein.',
        'validation' => [
            'account_password' => 'Du musst dein Konto Passwort angeben.',
            'current_password' => 'Du musst dein aktuelles Passwort angeben.',
            'password_confirmation' => 'Die Passwortbestätigung stimmt nicht mit dem eingegebenen Passwort überein.',
        ],
        'updated' => 'Dein Passwort wurde aktualisiert.',
    ],
    'two_factor' => [
        'title' => 'Zweistufige (2-Faktor) Verifizierung',
        'button' => '2-Faktor-Authentifizierung konfigurieren',
        'disabled' => 'Zwei-Faktor-Authentifizierung wurde auf deinem Konto deaktiviert. Du wirst beim Anmelden nicht mehr aufgefordert, einen Token anzugeben.',
        'enabled' => 'Zwei-Faktor-Authentifizierung wurde auf deinem Konto aktiviert! Ab sofort musst du beim Einloggen den von deinem Gerät generierten Code zur Verfügung stellen.',
        'invalid' => 'Der angegebene Token ist ungültig.',
        'enable' => [
            'help' => 'Sie haben zurzeit keine 2-Faktor-Authentifizierung auf ihrem Konto aktiviert. Drücken Sie die untere Schaltfläche um es einzurichten.',
            'button' => 'Zweistufige (2-Faktor) Verifizierung aktivieren',
        ],
        'disable' => [
            'help' => 'Die Zweistufige (2-Faktor) Verifizierung ist derzeit für dein Konto aktiviert.',
            'title' => 'Zwei-Faktor-Authentifizierung deaktivieren',
            'field' => 'Token eingeben',
            'button' => 'Zweistufige (2-Faktor) Verifizierung deaktivieren',
        ],
        'setup' => [
            'title' => 'Zweistufige Verifizierung aktivieren',
            'subtitle' => 'Helfen Sie mit, Ihr Konto vor unbefugtem Zugriff zu schützen. Jedes Mal, wenn Sie sich anmelden, werden Sie nach einem Bestätigungscode gefragt.',
            'help' => 'Scannen Sie den obigen QR-Code mit der 2-Faktor-Authentifizierungsapp Ihrer Wahl. Geben Sie danach den 6-stelligen Code in das Feld unten ein.',
        ],

        'required' => [
            'title' => '2-Faktor erforderlich',
            'description' => 'Dein Konto muss die Zwei-Faktor-Authentifizierung aktiviert haben, um fortzufahren.',
        ],
    ],
];
