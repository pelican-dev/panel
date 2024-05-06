<?php

return [
    'title' => 'Account Overview',
    'email' => [
        'title' => 'Zaktualizuj swój e-mail',
        'button' => 'Update Email',
        'updated' => 'Twój adres e-mail został zaktualizowany.',
    ],
    'password' => [
        'title' => 'Zmień swoje hasło',
        'button' => 'Update Password',
        'requirements' => 'Twoje nowe hasło powinno mieć co najmniej 8 znaków.',
        'validation' => [
            'account_password' => 'You must provide your account password.',
            'current_password' => 'You must provide your current password.',
            'password_confirmation' => 'Password confirmation does not match the password you entered.',
        ],
        'updated' => 'Twoje hasło zostało zaktualizowane.',
    ],
    'two_factor' => [
        'title' => 'Two-Step Verification',
        'button' => 'Skonfiguruj uwierzytelnianie dwuetapowe',
        'disabled' => 'Uwierzytelnianie dwuetapowe zostało wyłączone na Twoim koncie. Nie będziesz już proszony o podanie tokenu podczas logowania.',
        'enabled' => 'Uwierzytelnianie dwuetapowe zostało włączone na Twoim koncie! Od teraz podczas logowania będziesz musiał podać kod wygenerowany przez swoje urządzenie.',
        'invalid' => 'Podany token jest nieprawidłowy.',
        'enable' => [
            'help' => 'You do not currently have two-step verification enabled on your account. Click the button below to begin configuring it.',
            'button' => 'Enable Two-Step',
        ],
        'disable' => [
            'help' => 'Two-step verification is currently enabled on your account.',
            'title' => 'Wyłącz uwierzytelnianie dwuetapowe',
            'field' => 'Wprowadź token',
            'button' => 'Disable Two-Step',
        ],
        'setup' => [
            'title' => 'Skonfiguruj uwierzytelnianie dwuetapowe.',
            'subtitle' => "Help protect your account from unauthorized access. You'll be prompted for a verification code each time you sign in.",
            'help' => 'Nie udało Ci się zeskanować kodu? Wprowadź poniższy kod do swojej aplikacji:',
        ],

        'required' => [
            'title' => '2-Factor Required',
            'description' => 'Your account must have two-factor authentication enabled in order to continue.',
        ],
    ],
];
