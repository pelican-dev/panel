<?php

return [
    'title' => 'Account Overview',
    'email' => [
        'title' => 'Actualizează emailul',
        'button' => 'Update Email',
        'updated' => 'Adresa ta de email a fost actualizată.',
    ],
    'password' => [
        'title' => 'Schimbă-ți parola',
        'button' => 'Update Password',
        'requirements' => 'Noua ta parolă ar trebui să aibă cel puțin 8 caractere.',
        'validation' => [
            'account_password' => 'You must provide your account password.',
            'current_password' => 'You must provide your current password.',
            'password_confirmation' => 'Password confirmation does not match the password you entered.',
        ],
        'updated' => 'Parola ta a fost actualizată.',
    ],
    'two_factor' => [
        'title' => 'Two-Step Verification',
        'button' => 'Configurează autentificarea cu doi factori',
        'disabled' => 'Autentificarea cu doi factori a fost dezactivată din contul tău Nu vei mai fi solicitat să furnizezi un token la autentificare.',
        'enabled' => 'Autentificarea cu doi factori a fost activată în contul tău! De acum înainte, când te conectezi, va trebui să introduci codul generat de pe dispozitivul tău.',
        'invalid' => 'Token-ul furnizat nu a fost valid.',
        'enable' => [
            'help' => 'You do not currently have two-step verification enabled on your account. Click the button below to begin configuring it.',
            'button' => 'Enable Two-Step',
        ],
        'disable' => [
            'help' => 'Two-step verification is currently enabled on your account.',
            'title' => 'Dezactivează autentificarea cu doi factori',
            'field' => 'Introdu token-ul',
            'button' => 'Disable Two-Step',
        ],
        'setup' => [
            'title' => 'Setează autentificarea cu doi factori',
            'subtitle' => "Help protect your account from unauthorized access. You'll be prompted for a verification code each time you sign in.",
            'help' => 'Nu poți scana codul? Introdu codul de mai jos din aplicație:',
        ],

        'required' => [
            'title' => '2-Factor Required',
            'description' => 'Your account must have two-factor authentication enabled in order to continue.',
        ],
    ],
];
