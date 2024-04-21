<?php

return [
    'email' => [
        'title' => 'Actualizează emailul',
        'updated' => 'Adresa ta de email a fost actualizată.',
    ],
    'password' => [
        'title' => 'Schimbă-ți parola',
        'requirements' => 'Noua ta parolă ar trebui să aibă cel puțin 8 caractere.',
        'updated' => 'Parola ta a fost actualizată.',
    ],
    'two_factor' => [
        'button' => 'Configurează autentificarea cu doi factori',
        'disabled' => 'Autentificarea cu doi factori a fost dezactivată din contul tău Nu vei mai fi solicitat să furnizezi un token la autentificare.',
        'enabled' => 'Autentificarea cu doi factori a fost activată în contul tău! De acum înainte, când te conectezi, va trebui să introduci codul generat de pe dispozitivul tău.',
        'invalid' => 'Token-ul furnizat nu a fost valid.',
        'setup' => [
            'title' => 'Setează autentificarea cu doi factori',
            'help' => 'Nu poți scana codul? Introdu codul de mai jos din aplicație:',
            'field' => 'Introdu token-ul',
        ],
        'disable' => [
            'title' => 'Dezactivează autentificarea cu doi factori',
            'field' => 'Introdu token-ul',
        ],
    ],
];
