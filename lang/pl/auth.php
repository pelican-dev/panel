<?php

return [
    'sign_in' => 'Zaloguj',
    'go_to_login' => 'Przejdź do logowania',
    'failed' => 'Nie znaleziono konta pasującego do tych danych.',

    'forgot_password' => [
        'label' => 'Nie pamiętasz hasła?',
        'label_help' => 'Wprowadź swój adres e-mail, aby otrzymać instrukcje resetowania hasła.',
        'button' => 'Odzyskaj konto',
    ],

    'reset_password' => [
        'button' => 'Zresetuj i zaloguj się',
    ],

    'two_factor' => [
        'label' => 'Token logowania 2-etapowego',
        'label_help' => 'To konto wymaga uwierzytelniania 2-etapowego, aby kontynuować. Wprowadź wygenerowany kod, aby dokończyć logowanie.',
        'checkpoint_failed' => 'Kod uwierzytelniania 2-etapowego jest nieprawidłowy.',
    ],

    'throttle' => 'Zbyt wiele prób logowania. Spróbuj ponownie za :seconds sekund.',
    'password_requirements' => 'Hasło musi mieć co najmniej 8 znaków.',
    '2fa_must_be_enabled' => 'Administrator zażądał włączenia uwierzytelniania dwuetapowego dla Twojego konta, by móc korzystać z Panelu.',
];
