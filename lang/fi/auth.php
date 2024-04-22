<?php

return [
    'sign_in' => 'Kirjaudu Sisään',
    'go_to_login' => 'Siirry kirjautumiseen',
    'failed' => 'Käyttäjätunnuksia vastaavaa tiliä ei löytynyt.',

    'forgot_password' => [
        'label' => 'Unohtuiko salasana?',
        'label_help' => 'Syötä tilisi sähköpostiosoite saadaksesi ohjeet salasanan vaihtamista varten.',
        'button' => 'Palauta Tili',
    ],

    'reset_password' => [
        'button' => 'Palauta ja kirjaudu sisään',
    ],

    'two_factor' => [
        'label' => 'Kaksivaiheinen Tunnus',
        'label_help' => 'Tämä tunnus vaatii kaksivaiheisen todennuksen jatkaaksesi. Ole hyvä ja syötä laitteesi luoma koodi, jotta voit suorittaa tämän kirjautumisen.',
        'checkpoint_failed' => 'Kaksivaiheisen todennuksen avain oli virheellinen.',
    ],

    'throttle' => 'Liian monta kirjautumisyritystä. Yritä uudelleen :seconds sekunnin kuluttua.',
    'password_requirements' => 'Salasanan on oltava vähintään 8 merkkiä pitkä ja sen tulisi olla ainutkertainen tälle sivustolle.',
    '2fa_must_be_enabled' => 'Järjestelmänvalvoja on vaatinut, että kaksivaiheinen todennus on oltava käytössä tililläsi, jotta voit käyttää paneelia.',
];
