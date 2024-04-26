<?php

return [
    'exceptions' => [
        'user_has_servers' => 'Nem törölhető olyan felhasználó, amihez aktív szerver van társítva. Kérlek előbb töröld a szerverét a folytatáshoz.',
        'user_is_self' => 'A saját felhasználói fiókod nem törölheted.',
    ],
    'notices' => [
        'account_created' => 'Felhasználói fiók sikeresen létrehozva.',
        'account_updated' => 'Felhasználói fiók sikeresen frissítve.',
    ],
    'last_admin' => [
        'hint' => 'This is the last root administrator!',
        'helper_text' => 'You must have at least one root administrator in your system.',
    ],
    'root_admin' => 'Administrator (Root)',
    'language' => [
        'helper_text1' => 'Your language (:state) has not been translated yet!\nBut never fear, you can help fix that by',
        'helper_text2' => 'contributing directly here',
    ],
];
