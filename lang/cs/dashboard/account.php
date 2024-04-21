<?php

return [
    'email' => [
        'title' => 'Aktualizovat e-mail',
        'updated' => 'E-mailová adresa byla úspěšně změněna.',
    ],
    'password' => [
        'title' => 'Změnit heslo',
        'requirements' => 'Vaše heslo by mělo mít délku alespoň 8 znaků.',
        'updated' => 'Vaše heslo bylo změněno.',
    ],
    'two_factor' => [
        'button' => 'Nastavení dvoufázového ověření',
        'disabled' => 'Dvoufázové ověřování bylo na vašem účtu zakázáno. Po přihlášení již nebudete vyzváni k poskytnutí tokenu.',
        'enabled' => 'Dvoufázové ověřování bylo na vašem účtu povoleno! Od nynějška při přihlášení budete muset zadat kód vygenerovaný vaším zařízením.',
        'invalid' => 'Zadaný token není platný.',
        'setup' => [
            'title' => 'Nastavit dvoufázové ověřování',
            'help' => 'Nelze naskenovat kód? Zadejte kód níže do vaší aplikace:',
            'field' => 'Zadejte token',
        ],
        'disable' => [
            'title' => 'Zakázat dvoufázové ověření',
            'field' => 'Zadejte token',
        ],
    ],
];
