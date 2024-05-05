<?php

return [
    'title' => 'Tilin yleiskatsaus',
    'email' => [
        'title' => 'Päivitä sähköpostiosoite',
        'button' => 'Päivitä Sähköposti',
        'updated' => 'Sinun ensisijainen sähköpostisi on päivitetty',
    ],
    'password' => [
        'title' => 'Päivitä salasana',
        'button' => 'Päivitä salasana',
        'requirements' => 'Uuden salasanasi tulisi olla vähintään 8 merkkiä pitkä ja uniikki tälle verkkosivustolle.',
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
            'title' => 'Ota kaksivaiheinen todennus käyttöön',
            'subtitle' => 'Auta suojaamaan tilisi luvattomalta käytöltä. Sinulta pyydetään vahvistuskoodia joka kerta, kun kirjaudut sisään.',
            'help' => 'Skannaa yllä oleva QR-koodi valitsemasi kaksivaiheisen todennuksen sovelluksella. Sitten syötä 6-numeroinen koodi alla olevaan kenttään.',
        ],

        'required' => [
            'title' => 'Kaksivaiheinen todennus vaaditaan',
            'description' => 'Tililläsi täytyy olla kaksivaiheinen todennus käytössä, jotta voit jatkaa.',
        ],
    ],
];
