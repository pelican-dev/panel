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
        'updated' => 'Vaša lozinka je ažurirana.',
    ],
    'two_factor' => [
        'title' => 'Two-Step Verification',
        'button' => 'Konfiguriraj 2 Faktor authentikaciju.',
        'disabled' => '2 Faktor authentikacija je isključena na vašem računu. Više vas nećemo pitati za token kada se prijavljate.',
        'enabled' => '2 Faktor authentikacija je uključena na vašem računu. Od sada kada se prijavljate morate upisati kod koji je vaš uređaj generirio.',
        'invalid' => 'Token je netočan.',
        'enable' => [
            'help' => 'You do not currently have two-step verification enabled on your account. Click the button below to begin configuring it.',
            'button' => 'Enable Two-Step',
        ],
        'disable' => [
            'help' => 'Two-step verification is currently enabled on your account.',
            'title' => 'Isključi dva faktor autentikaciju',
            'field' => 'Upiši token',
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
