<?php

return [
    'title' => 'Konto Oversigt',
    'email' => [
        'title' => 'Opdater Email Adresse',
        'button' => 'Opdater Email',
        'updated' => 'Din primære email er blevet opdateret',
    ],
    'password' => [
        'title' => 'Opdater Adgangskode',
        'button' => 'Opdater Adgangskode',
        'requirements' => 'Din nye adgangskode skal være mindst 8 tegn langt og unikt for dette websted.',
        'validation' => [
            'account_password' => 'Du skal angive adgangskoden til din konto.',
            'current_password' => 'Du skal angive din nuværende adgangskode.',
            'password_confirmation' => 'Password confirmation does not match the password you entered.',
        ],
        'updated' => 'Din adgangskode er blevet opdateret.',
    ],
    'two_factor' => [
        'title' => 'Two-Step Verification',
        'button' => 'Konfigurer 2-Faktor godkendelse',
        'disabled' => '2-faktor godkendelse er blevet deaktiveret på din konto. Du vil ikke længere blive bedt om at angive en token ved login.',
        'enabled' => '2-faktor godkendelse er blevet aktiveret på din konto! Fra nu af, når du logger ind, vil du blive bedt om at angive koden genereret af din enhed.',
        'invalid' => 'Den angivne nøgle var ugyldig.',
        'enable' => [
            'help' => 'You do not currently have two-step verification enabled on your account. Click the button below to begin configuring it.',
            'button' => 'Enable Two-Step',
        ],
        'disable' => [
            'help' => 'Two-step verification is currently enabled on your account.',
            'title' => 'Deaktiver 2-faktor godkendelse',
            'field' => 'Indtast nøgle',
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
