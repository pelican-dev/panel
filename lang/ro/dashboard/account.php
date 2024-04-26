<?php

return [
    'title' => 'Account Overview',
    'email' => [
        'title' => 'Update Email Address',
        'button' => 'Update Email',
        'updated' => 'Your primary email has been updated.',
    ],
    'password' => [
        'title' => 'Update Password',
        'button' => 'Update Password',
        'requirements' => 'Your new password should be at least 8 characters in length and unique to this website.',
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
            'title' => 'Enable Two-Step Verification',
            'subtitle' => "Help protect your account from unauthorized access. You'll be prompted for a verification code each time you sign in.",
            'help' => 'Scan the QR code above using the two-step authentication app of your choice. Then, enter the 6-digit code generated into the field below.',
        ],

        'required' => [
            'title' => '2-Factor Required',
            'description' => 'Your account must have two-factor authentication enabled in order to continue.',
        ],
    ],
];
