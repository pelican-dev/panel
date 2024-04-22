<?php

return [
    'email' => [
        'title' => 'Päivitä sähköpostiosoitteesi',
        'updated' => 'Sähköpostiosoite on päivitetty.',
    ],
    'password' => [
        'title' => 'Vaihda salasanasi',
        'requirements' => 'Uuden salasanan on oltava vähintään 8 merkkiä pitkä',
        'updated' => 'Salasanasi on päivitetty.',
    ],
    'two_factor' => [
        'button' => 'Määritä Kaksivaiheinen Todennus',
        'disabled' => 'Kaksivaiheinen todennus on poistettu käytöstä tililtäsi. Sinua ei enää kehoteta antamaan tunnusta kirjautuessasi.',
        'enabled' => 'Kaksivaiheinen todennus on otettu käyttöön tililläsi! Tästä lähtien kun kirjaudut sisään, sinun on annettava laitteesi luoma koodi.',
        'invalid' => 'Annettu tunniste oli virheellinen.',
        'setup' => [
            'title' => 'Aseta kaksivaiheinen todennus',
            'help' => 'Koodia ei voi skannata? Syötä alla oleva koodi sovelluksesi:',
            'field' => 'Syötä tunnus',
        ],
        'disable' => [
            'title' => 'Poista käytöstä kaksivaiheinen tunnistautuminen',
            'field' => 'Syötä tunnus',
        ],
    ],
];
