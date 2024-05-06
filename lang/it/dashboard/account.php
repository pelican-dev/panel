<?php

return [
    'title' => 'Account Overview',
    'email' => [
        'title' => 'Aggiorna la tua email',
        'button' => 'Update Email',
        'updated' => 'Il tuo indirizzo email e stato aggiornato.',
    ],
    'password' => [
        'title' => 'Cambia la tua password',
        'button' => 'Update Password',
        'requirements' => 'La tua nuova password deve essere lunga almeno 8 caratteri.',
        'validation' => [
            'account_password' => 'You must provide your account password.',
            'current_password' => 'You must provide your current password.',
            'password_confirmation' => 'Password confirmation does not match the password you entered.',
        ],
        'updated' => 'La password è stata aggiornata.',
    ],
    'two_factor' => [
        'title' => 'Two-Step Verification',
        'button' => 'Configura l\'autenticazione a due fattori',
        'disabled' => 'L\'autenticazione a due fattori è stata disabilitata sul tuo account. Non ti sarà più richiesto di fornire un token durante l\'accesso.',
        'enabled' => 'L\'autenticazione a due fattori è stata abilitata sul tuo account! D\'ora in poi, quando accedi, ti sarà richiesto di fornire il codice generato dal tuo dispositivo.',
        'invalid' => 'Il token fornito non è valido.',
        'enable' => [
            'help' => 'You do not currently have two-step verification enabled on your account. Click the button below to begin configuring it.',
            'button' => 'Enable Two-Step',
        ],
        'disable' => [
            'help' => 'Two-step verification is currently enabled on your account.',
            'title' => 'Disabilita l\'autenticazione a due fattori',
            'field' => 'Inserisci il token',
            'button' => 'Disable Two-Step',
        ],
        'setup' => [
            'title' => 'Imposta l\'autenticazione a due fattori',
            'subtitle' => "Help protect your account from unauthorized access. You'll be prompted for a verification code each time you sign in.",
            'help' => 'Non puoi scansionare il codice? Inserisci il codice qui sotto nella tua applicazione:',
        ],

        'required' => [
            'title' => '2-Factor Required',
            'description' => 'Your account must have two-factor authentication enabled in order to continue.',
        ],
    ],
];
