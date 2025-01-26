<?php

return [
    'return_to_login' => 'Zurück zur Anmeldung',
    'sign_in' => 'Anmelden',
    'go_to_login' => 'Zum Login',
    'failed' => 'Es wurde kein Konto mit diesen Zugangsdaten gefunden.',

    'login' => [
        'title' => 'Zum Fortfahren anmelden',
        'button' => 'Anmelden',
        'required' => [
            'username_or_email' => 'Ein Benutzername oder eine E-Mail-Adresse muss angegeben werden.',
            'password' => 'Bitte geben Sie Ihr Kontopasswort ein.',
        ],
    ],

    'forgot_password' => [
        'title' => 'Passwort zurücksetzen anfordern',
        'label' => 'Passwort vergessen?',
        'label_help' => 'Geben Sie Ihre E-Mail-Adresse ein, um Anweisungen zum Zurücksetzen Ihres Passworts zu erhalten.',
        'button' => 'Konto wiederherstellen',
        'required' => [
            'email' => 'Eine gültige E-Mail-Adresse muss angegeben werden, um fortzufahren.',
        ],
    ],

    'reset_password' => [
        'button' => 'Zurücksetzen und Anmelden',
        'title' => 'Passwort zurücksetzen',
        'new_password' => 'Neues Passwort',
        'confirm_new_password' => 'Neues Passwort bestätigen',
        'requirement' => [
            'password' => 'Passwörter müssen mindestens 8 Zeichen lang sein.',
        ],
        'required' => [
            'password' => 'Ein neues Passwort ist erforderlich.',
            'password_confirmation' => 'Ihr neues Passwort stimmt nicht überein.',
        ],
        'validation' => [
            'password' => 'Ihr neues Passwort muss mindestens 8 Zeichen lang sein.',
            'password_confirmation' => 'Ihr neues Passwort stimmt nicht überein.',
        ],
    ],

    'checkpoint' => [
        'title' => 'Geräteüberprüfung',
        'recovery_code' => 'Wiederherstellungscode',
        'recovery_code_description' => 'Geben Sie einen der Wiederherstellungscodes ein, die Sie bei der Einrichtung der Zwei-Faktor-Authentifizierung für dieses Konto generiert haben, um fortzufahren.',
        'authentication_code' => 'Authentifizierungscode',
        'authentication_code_description' => 'Geben Sie das von Ihrem Gerät generierte Zwei-Faktor-Token ein.',
        'button' => 'Weiter',
        'lost_device' => 'Ich habe mein Gerät verloren',
        'have_device' => 'Ich habe mein Gerät',
    ],

    'two_factor' => [
        'label' => '2-Faktor Token',
        'label_help' => 'Dieses Konto benötigt eine zweite Authentifizierungsebene, um fortzufahren. Bitte geben Sie den von Ihrem Gerät generierten Code ein, um diesen Login abzuschließen.',
        'checkpoint_failed' => 'Der Zwei-Faktor-Authentifizierungstoken war ungültig.',
    ],

    'throttle' => 'Zu viele Anmeldeversuche. Bitte versuche es in :seconds Sekunden erneut.',
    'password_requirements' => 'Das Passwort muss mindestens 8 Zeichen lang sein und sollte auf dieser Seite eindeutig sein.',
    '2fa_must_be_enabled' => 'Der Administrator hat festgelegt, dass die 2-Faktor-Authentifizierung für deinen Account angeschaltet sein muss, damit du dieses Panel nutzen kannst.',
];
