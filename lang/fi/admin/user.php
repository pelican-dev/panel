<?php

return [
    'exceptions' => [
        'user_has_servers' => 'Ei voida poistaa käyttäjää, jolla on aktiivisia palvelimia heidän tililleen. Poista heidän palvelimensa ennen jatkamista.',
        'user_is_self' => 'Omaa käyttäjätiliä ei voi poistaa.',
    ],
    'notices' => [
        'account_created' => 'Tili on luotu onnistuneesti.',
        'account_updated' => 'Tili on päivitetty onnistuneesti.',
    ],
    'last_admin' => [
        'hint' => 'Tämä on viimeinen järjestelmänvalvoja!',
        'helper_text' => 'Sinulla täytyy olla ainakin yksi järjestelmänvalvoja järjestelmässä.',
    ],
    'root_admin' => 'Järjestelmänvalvoja (Root)',
    'language' => [
        'helper_text1' => 'Kielesi (:state) ei ole vielä käännetty!\nMutta älä pelkää, voit auttaa korjaamaan sen',
        'helper_text2' => 'osallistumalla suoraan täältä',
    ],
];
