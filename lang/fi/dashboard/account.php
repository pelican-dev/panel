<?php

return [
    'title' => 'Tilin yleiskatsaus',
    'email' => [
        'title' => 'Päivitä sähköpostiosoitteesi',
        'button' => 'Päivitä Sähköposti',
        'updated' => 'Sähköpostiosoite on päivitetty.',
    ],
    'password' => [
        'title' => 'Vaihda salasanasi',
        'button' => 'Päivitä salasana',
        'requirements' => 'Uuden salasanan on oltava vähintään 8 merkkiä pitkä',
        'validation' => [
            'account_password' => 'Sinun on annettava tilisi salasana.',
            'current_password' => 'Syötä nykyinen salasana',
            'password_confirmation' => 'Salasanan vahvistus ei vastaa antamaasi salasanaa.',
        ],
        'updated' => 'Salasanasi on päivitetty.',
    ],
    'two_factor' => [
        'title' => 'Kaksivaiheinen todennus',
        'button' => 'Määritä Kaksivaiheinen Todennus',
        'disabled' => 'Kaksivaiheinen todennus on poistettu käytöstä tililtäsi. Sinua ei enää kehoteta antamaan tunnusta kirjautuessasi.',
        'enabled' => 'Kaksivaiheinen todennus on otettu käyttöön tililläsi! Tästä lähtien kun kirjaudut sisään, sinun on annettava laitteesi luoma koodi.',
        'invalid' => 'Annettu tunniste oli virheellinen.',
        'enable' => [
            'help' => 'Sinulla ei ole tällä hetkellä kaksivaiheista todennusta käytössä tililläsi. Aloita määrittäminen napsauttamalla alla olevaa painiketta.',
            'button' => 'Ota Kaksivaiheinen todennus Käyttöön',
        ],
        'disable' => [
            'help' => 'Kaksivaiheinen todennus on tällä hetkellä käytössä tililläsi.',
            'title' => 'Poista käytöstä kaksivaiheinen tunnistautuminen',
            'field' => 'Syötä tunnus',
            'button' => 'Poista Kaksivaiheinen todennus käytöstä',
        ],
        'setup' => [
            'title' => 'Aseta kaksivaiheinen todennus',
            'subtitle' => "Auta suojaamaan tilisi luvattomalta käytöltä. Sinulta pyydetään vahvistuskoodia joka kerta, kun kirjaudut sisään.",
            'help' => 'Koodia ei voi skannata? Syötä alla oleva koodi sovelluksesi:',
        ],

        'required' => [
            'title' => 'Kaksivaiheinen todennus vaaditaan',
            'description' => 'Tililläsi täytyy olla kaksivaiheinen todennus käytössä, jotta voit jatkaa.',
        ],
    ],
];
