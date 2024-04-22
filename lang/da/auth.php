<?php

return [
    'sign_in' => 'Log ind',
    'go_to_login' => 'Gå til log ind',
    'failed' => 'Ingen konto fundet med de angivne oplysninger.',

    'forgot_password' => [
        'label' => 'Glemt adgangskode?',
        'label_help' => 'Indtast din kontos e-mailadresse for at modtage instruktioner om nulstilling af din adgangskode.',
        'button' => 'Gendan konto',
    ],

    'reset_password' => [
        'button' => 'Nulstil adgangskode og log ind',
    ],

    'two_factor' => [
        'label' => '2-Factor Token',
        'label_help' => 'Denne konto kræver en anden form for godkendelse for at fortsætte. Indtast venligst koden genereret af din enhed for at fuldføre dette login.',
        'checkpoint_failed' => '2-factor godkendelses-token var ugyldig.',
    ],

    'throttle' => 'For mange login forsøg. Prøv igen om :sekunder sekunder.',
    'password_requirements' => 'Adgangskoden skal være mindst 8 tegn lang og bør være unik for dette website.',
    '2fa_must_be_enabled' => 'Administratoren har krævet, at 2-factor godkendelse skal være aktiveret for din konto for at bruge panelet.',
];
