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
        'requirements' => 'Your new password should be at least 8 characters in length and unique to this website.',
        'validation' => [
            'account_password' => 'You must provide your account password.',
            'current_password' => 'You must provide your current password.',
            'password_confirmation' => 'Password confirmation does not match the password you entered.',
        ],
        'updated' => 'Dein Passwort wurde aktualisiert.',
    ],
    'two_factor' => [
        'title' => 'Two-Step Verification',
        'button' => '2-Faktor-Authentifizierung konfigurieren',
        'disabled' => 'Zwei-Faktor-Authentifizierung wurde auf deinem Konto deaktiviert. Du wirst beim Anmelden nicht mehr aufgefordert, einen Token anzugeben.',
        'enabled' => 'Zwei-Faktor-Authentifizierung wurde auf deinem Konto aktiviert! Ab sofort musst du beim Einloggen den von deinem Gerät generierten Code zur Verfügung stellen.',
        'invalid' => 'Der angegebene Token ist ungültig.',
        'enable' => [
            'help' => 'You do not currently have two-step verification enabled on your account. Click the button below to begin configuring it.',
            'button' => 'Enable Two-Step',
        ],
        'disable' => [
            'help' => 'Two-step verification is currently enabled on your account.',
            'title' => 'Zwei-Faktor-Authentifizierung deaktivieren',
            'field' => 'Token eingeben',
            'button' => 'Disable Two-Step',
        ],
        'setup' => [
            'title' => 'Enable Two-Step Verification',
            'subtitle' => "Help protect your account from unauthorized access. You'll be prompted for a verification code each time you sign in.",
            'help' => 'Scan the QR code above using the two-step authentication app of your choice. Then, enter the 6-digit code generated into the field below.',
        ],

        'required' => [
            'title' => '2-Faktor erforderlich',
            'description' => 'Dein Konto muss die Zwei-Faktor-Authentifizierung aktiviert haben, um fortzufahren.',
        ],
    ],
];
