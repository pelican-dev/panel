<?php

return [
    'title' => 'Account Overview',
    'email' => [
        'title' => 'Aktualizovat e-mail',
        'button' => 'Update Email',
        'updated' => 'E-mailová adresa byla úspěšně změněna.',
    ],
    'password' => [
        'title' => 'Změnit heslo',
        'button' => 'Update Password',
        'requirements' => 'Vaše heslo by mělo mít délku alespoň 8 znaků.',
        'validation' => [
            'account_password' => 'You must provide your account password.',
            'current_password' => 'You must provide your current password.',
            'password_confirmation' => 'Password confirmation does not match the password you entered.',
        ],
        'updated' => 'Vaše heslo bylo změněno.',
    ],
    'two_factor' => [
        'title' => 'Two-Step Verification',
        'button' => 'Nastavení dvoufázového ověření',
        'disabled' => 'Dvoufázové ověřování bylo na vašem účtu zakázáno. Po přihlášení již nebudete vyzváni k poskytnutí tokenu.',
        'enabled' => 'Dvoufázové ověřování bylo na vašem účtu povoleno! Od nynějška při přihlášení budete muset zadat kód vygenerovaný vaším zařízením.',
        'invalid' => 'Zadaný token není platný.',
        'enable' => [
            'help' => 'You do not currently have two-step verification enabled on your account. Click the button below to begin configuring it.',
            'button' => 'Enable Two-Step',
        ],
        'disable' => [
            'help' => 'Two-step verification is currently enabled on your account.',
            'title' => 'Zakázat dvoufázové ověření',
            'field' => 'Zadejte token',
            'button' => 'Disable Two-Step',
        ],
        'setup' => [
            'title' => 'Nastavit dvoufázové ověřování',
            'subtitle' => "Help protect your account from unauthorized access. You'll be prompted for a verification code each time you sign in.",
            'help' => 'Nelze naskenovat kód? Zadejte kód níže do vaší aplikace:',
        ],

        'required' => [
            'title' => '2-Factor Required',
            'description' => 'Your account must have two-factor authentication enabled in order to continue.',
        ],
    ],
];
