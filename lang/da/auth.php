<?php

return [
    'return_to_login' => 'Tilbage til Login',
    'failed' => 'Ingen konto fundet med de angivne oplysninger.',

    'login' => [
        'title' => 'Log ind for at fortsætte',
        'button' => 'Log ind',
        'required' => [
            'username_or_email' => 'Et brugernavn eller e-mail skal angives.',
            'password' => 'Indtast venligst din nuværende adgangskode.',
        ],
    ],

    'forgot_password' => [
        'title' => 'Anmod om nulstilling af adgangskode',
        'label' => 'Glemt adgangskode?',
        'label_help' => 'Indtast din kontos e-mailadresse for at modtage instruktioner om nulstilling af din adgangskode.',
        'button' => 'Send Email',
        'required' => [
            'email' => 'En gyldig e-mailadresse skal angives for at fortsætte.',
        ],
    ],

    'reset_password' => [
        'title' => 'Nulstil adgangskode',
        'button' => 'Nulstil adgangskode',
        'new_password' => 'Ny adgangskode',
        'confirm_new_password' => 'Bekræft ny adgangskode',
        'requirement' => [
            'password' => 'Adgangskoder skal være på mindst 8 tegn.',
        ],
        'required' => [
            'password' => 'En ny adgangskode er påkrævet.',
            'password_confirmation' => 'Din nye adgangskode er ikke ens.',
        ],
        'validation' => [
            'password' => 'Din nye adgangskode skal være mindst 8 tegn lang.',
            'password_confirmation' => 'Din nye adgangskode er ikke ens.',
        ],
    ],

    'checkpoint' => [
        'title' => 'Device Checkpoint',
        'recovery_code' => 'Gendannelseskode',
        'recovery_code_description' => 'Indtast en af de gendannelseskoder, der genereres, når du opsætter 2-faktor godkendelse på denne konto for at fortsætte.',
        'authentication_code' => 'Godkendelseskode',
        'authentication_code_description' => 'Indtast det 2-faktor token din enhed har genereret.',
        'button' => 'Fortsæt',
        'lost_device' => 'Jeg har mistet min enhed',
        'have_device' => 'Jeg har min enhed',
    ],

    'two_factor' => [
        'label' => '2-Faktor Token',
        'label_help' => 'Denne konto kræver en anden form for godkendelse for at fortsætte. Indtast venligst koden genereret af din enhed for at fuldføre dette login.',
        'checkpoint_failed' => '2-faktor godkendelses-token var ugyldig.',
    ],

    'throttle' => 'For mange login forsøg. Prøv igen om :sekunder sekunder.',
    'password_requirements' => 'Adgangskoden skal være mindst 8 tegn lang og bør være unik for dette website.',
    '2fa_must_be_enabled' => 'Administratoren har krævet, at 2-faktor godkendelse skal være aktiveret for din konto for at bruge panelet.',
];
