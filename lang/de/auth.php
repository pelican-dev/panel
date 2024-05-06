<?php

return [
    'return_to_login' => 'Zurück zum Login',
    'failed' => 'Es wurde kein Konto mit diesen Zugangsdaten gefunden.',

    'login' => [
        'title' => 'Zum Fortfahren anmelden',
        'button' => 'Anmelden',
        'required' => [
            'username_or_email' => 'Es muss ein Benutzername oder eine E-Mail angegeben werden.',
            'password' => 'Bitte gebe dein Account-Passwort ein.',
        ],
    ],

    'forgot_password' => [
        'title' => 'Passwort-Reset anfordern',
        'label' => 'Passwort vergessen?',
        'label_help' => 'Geben Sie Ihre E-Mail Adresse ein, um Anweisungen zum Zurücksetzen Ihres Passworts zu erhalten.',
        'button' => 'Konto wiederherstellen',
        'required' => [
            'email' => 'Eine gültige E-Mail-Adresse muss angegeben werden, um fortzufahren.',
        ],
    ],

    'reset_password' => [
        'title' => 'Passwort zurücksetzen',
        'button' => 'Zurücksetzen und Anmelden',
        'new_password' => 'Neues Passwort',
        'confirm_new_password' => 'Neues Passwort bestätigen',
        'requirement' => [
            'password' => 'Passwörter müssen mindestens 8 Zeichen lang sein.',
        ],
        'required' => [
            'password' => 'Ein neues Passwort ist erforderlich.',
            'password_confirmation' => 'Dein neues Passwort stimmt nicht überein.',
        ],
        'validation' => [
            'password' => 'Dein neues Passwort sollte mindestens 8 Zeichen lang sein.',
            'password_confirmation' => 'Dein neues Passwort stimmt nicht überein.',
        ],
    ],

    'checkpoint' => [
        'title' => 'Geräte-Checkpoint',
        'recovery_code' => 'Wiederherstellungscode',
        'recovery_code_description' => 'Um fortzufahren geben Sie einen ihrer Recovery-Codes ein, die generiert wurden als Sie 2-Faktor-Authentifizierung auf diesem Account aktiviert haben.',
        'authentication_code' => 'Authentifizierungscode',
        'authentication_code_description' => 'Geben Sie den von Ihrem Gerät generierten Zwei-Faktor-Token ein.',
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
    'password_requirements' => 'Das Passwort muss mindestens 8 Zeichen lang sein und sollte auf dieser Seite einzigartig sein.',
    '2fa_must_be_enabled' => 'Der Administrator hat festgelegt, dass die 2-Faktor-Authentifizierung für deinen Account aktiviert sein muss, damit du das Panel nutzen kannst.',
];
