<?php

return [
    'email' => [
        'title' => 'Promjeni svoj email',
        'updated' => 'Vaša e-mail adresa je ažurirana.',
    ],
    'password' => [
        'title' => 'Promijeni lozinku',
        'requirements' => 'Vaša nova lozinka treba imati makar 8 karaktera.',
        'updated' => 'Vaša lozinka je ažurirana.',
    ],
    'two_factor' => [
        'button' => 'Konfiguriraj 2 Faktor authentikaciju.',
        'disabled' => '2 Faktor authentikacija je isključena na vašem računu. Više vas nećemo pitati za token kada se prijavljate.',
        'enabled' => '2 Faktor authentikacija je uključena na vašem računu. Od sada kada se prijavljate morate upisati kod koji je vaš uređaj generirio.',
        'invalid' => 'Token je netočan.',
        'setup' => [
            'title' => 'Postavi 2 faktor autentikaciju.',
            'help' => 'Ne možeš skenirati kod? Napiši ovaj kod u svoju aplikaciju:',
            'field' => 'Upiši token',
        ],
        'disable' => [
            'title' => 'Isključi dva faktor autentikaciju',
            'field' => 'Upiši token',
        ],
    ],
];
