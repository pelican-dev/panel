<?php

return [
    'return_to_login' => 'Palaa kirjautumiseen',
    'failed' => 'Käyttäjätunnuksia vastaavaa tiliä ei löytynyt.',

    'login' => [
        'title' => 'Kirjaudu sisään jatkaaksesi',
        'button' => 'Kirjaudu',
        'required' => [
            'username_or_email' => 'Käyttäjätunnus tai sähköpostiosoite on annettava.',
            'password' => 'Ole hyvä ja syötä tilisi salasana.',
        ],
    ],

    'forgot_password' => [
        'title' => 'Pyydä Salasanan Palautusta',
        'label' => 'Unohtuiko salasana?',
        'label_help' => 'Syötä tilisi sähköpostiosoite saadaksesi ohjeet salasanan vaihtamista varten.',
        'button' => 'Lähetä Sähköposti',
        'required' => [
            'email' => 'Voimassa oleva sähköpostiosoite on annettava jatkaaksesi.',
        ],
    ],

    'reset_password' => [
        'title' => 'Palauta Salasana',
        'button' => 'Palauta Salasana',
        'new_password' => 'Uusi Salasana',
        'confirm_new_password' => 'Vahvista Uusi Salasana',
        'requirement' => [
            'password' => 'Salasanan on oltava vähintään 8 merkkiä pitkä.',
        ],
        'required' => [
            'password' => 'Uusi salasana on pakollinen.',
            'password_confirmation' => 'Uusi salasanasi ei täsmää',
        ],
        'validation' => [
            'password' => 'Uuden salasanan on oltava vähintään 8 merkkiä pitkä',
            'password_confirmation' => 'Uusi salasanasi ei täsmää',
        ],
    ],

    'checkpoint' => [
        'title' => 'Laitteen Tarkistuspiste',
        'recovery_code' => 'Palautuskoodi',
        'recovery_code_description' => 'Syötä yksi palautuskoodeista, jotka luotiin, kun asetat kaksivaiheisen todennuksen tälle tilille jatkaaksesi.',
        'authentication_code' => 'Vahvistuskoodi',
        'authentication_code_description' => 'Syötä laitteesi tuottama kaksivaiheinen tunniste.',
        'button' => 'Jatka',
        'lost_device' => 'Olen Kadonnut Laitteen',
        'have_device' => 'Minulla On Laitteeni',
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
