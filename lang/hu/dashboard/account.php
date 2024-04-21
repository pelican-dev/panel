<?php

return [
    'email' => [
        'title' => 'Email címed frissítése',
        'updated' => 'Az email címed frissítve lett.',
    ],
    'password' => [
        'title' => 'Jelszóváltoztatás',
        'requirements' => 'Az új jelszavadnak legalább 8 karakter hosszúnak kell lennie.',
        'updated' => 'A jelszavad frissítve lett.',
    ],
    'two_factor' => [
        'button' => 'Két-faktoros hitelesítés beállítása',
        'disabled' => 'A két-faktoros hitelesítés ki van kapcsolva a fiókodnál. Bejelentkezéskor nem szükséges már megadnod a két-faktoros kulcsot.',
        'enabled' => 'Két-faktoros hitelesítés be van kapcsolva a fiókodnál! Ezentúl bejelentkezésnél meg kell adnod a két-faktoros kulcsot, amit a hitelesítő alkalmazás generál.',
        'invalid' => 'A megadott kulcs érvénytelen.',
        'setup' => [
            'title' => 'Két-faktoros hitelesítés beállítása',
            'help' => 'Nem tudod bescannelni a kódot? Írd be az alábbi kulcsot az alkalmazásba:',
            'field' => 'Kulcs megadása',
        ],
        'disable' => [
            'title' => 'Két-faktoros hitelesítés kikapcsolása',
            'field' => 'Kulcs megadása',
        ],
    ],
];
