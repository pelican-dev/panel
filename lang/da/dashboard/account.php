<?php

return [
    'email' => [
        'title' => 'Opdater din e-mail',
        'updated' => 'Din e-mailadresse er blevet opdateret.',
    ],
    'password' => [
        'title' => 'Skift din adgangskode',
        'requirements' => 'Din nye adgangskode skal være mindst 8 tegn lang.',
        'updated' => 'Din adgangskode er blevet opdateret.',
    ],
    'two_factor' => [
        'button' => 'Konfigurer 2-Factor godkendelse',
        'disabled' => '2-factor godkendelse er blevet deaktiveret på din konto. Du vil ikke længere blive bedt om at angive en token ved login.',
        'enabled' => '2-factor godkendelse er blevet aktiveret på din konto! Fra nu af, når du logger ind, vil du blive bedt om at angive koden genereret af din enhed.',
        'invalid' => 'Den angivne token var ugyldig.',
        'setup' => [
            'title' => 'Opsætning af 2-factor godkendelse',
            'help' => 'Kan ikke scanne koden? Indtast koden nedenfor i din applikation:',
            'field' => 'Indtast token',
        ],
        'disable' => [
            'title' => 'Deaktiver 2-factor godkendelse',
            'field' => 'Indtast token',
        ],
    ],
];
