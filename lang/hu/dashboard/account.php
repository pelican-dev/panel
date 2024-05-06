<?php

return [
    'title' => 'Account Overview',
    'email' => [
        'title' => 'Email címed frissítése',
        'button' => 'Update Email',
        'updated' => 'Az email címed frissítve lett.',
    ],
    'password' => [
        'title' => 'Jelszavad megváltoztatása',
        'button' => 'Update Password',
        'requirements' => 'Az új jelszavadnak legalább 8 karakter hosszúnak kell lennie.',
        'validation' => [
            'account_password' => 'You must provide your account password.',
            'current_password' => 'You must provide your current password.',
            'password_confirmation' => 'Password confirmation does not match the password you entered.',
        ],
        'updated' => 'A jelszavad frissítve lett.',
    ],
    'two_factor' => [
        'title' => 'Two-Step Verification',
        'button' => 'Két-faktoros hitelesítés beállítása',
        'disabled' => 'A két-faktoros hitelesítés ki van kapcsolva a fiókodnál. Bejelentkezéskor nem szükséges már megadnod a két-faktoros kulcsot.',
        'enabled' => 'Két-faktoros hitelesítés be van kapcsolva a fiókodnál! Ezentúl bejelentkezésnél meg kell adnod a két-faktoros kulcsot, amit a hitelesítő alkalmazás generál.',
        'invalid' => 'A megadott kulcs érvénytelen.',
        'enable' => [
            'help' => 'You do not currently have two-step verification enabled on your account. Click the button below to begin configuring it.',
            'button' => 'Enable Two-Step',
        ],
        'disable' => [
            'help' => 'Two-step verification is currently enabled on your account.',
            'title' => 'Két-faktoros hitelesítés kikapcsolása',
            'field' => 'Kulcs megadása',
            'button' => 'Disable Two-Step',
        ],
        'setup' => [
            'title' => 'Két-faktoros hitelesítés beállítása',
            'subtitle' => "Help protect your account from unauthorized access. You'll be prompted for a verification code each time you sign in.",
            'help' => 'Nem tudod bescannelni a kódot? Írd be az alábbi kulcsot az alkalmazásba:',
        ],

        'required' => [
            'title' => '2-Factor Required',
            'description' => 'Your account must have two-factor authentication enabled in order to continue.',
        ],
    ],
];
