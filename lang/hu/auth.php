<?php

return [
    'sign_in' => 'Bejelentkezés',
    'go_to_login' => 'Ugrás a bejelentkezéshez',
    'failed' => 'A megadott adatokkal nem található felhasználó.',

    'forgot_password' => [
        'label' => 'Elfelejtetted a jelszavad?',
        'label_help' => 'Add meg az email címed a jelszavad visszaállításához.',
        'button' => 'Fiók visszaállítása',
    ],

    'reset_password' => [
        'button' => 'Visszaállítás és bejelentkezés',
    ],

    'two_factor' => [
        'label' => '2-Faktoros kulcs',
        'label_help' => 'Ez a fiók egy második szintű hitelesítést igényel a folytatáshoz. Kérjük, add meg a hitelesítő alkalmazásod által generált kódot a bejelentkezés befejezéséhez.',
        'checkpoint_failed' => 'A két-faktoros hitelesítés kulcsa érvénytelen.',
    ],

    'throttle' => 'Túl sok bejelentkezési próbálkozás. Kérlek próbáld újra :seconds másodperc múlva.',
    'password_requirements' => 'A jelszónak legalább 8 karakter hosszúnak kell lennie.',
    '2fa_must_be_enabled' => 'A panel használatához két-faktoros hitelesítés engedélyezése szükséges.',
];
