<?php

return [
    'email' => [
        'title' => 'Zaktualizuj swój e-mail',
        'updated' => 'Twój adres e-mail został zaktualizowany.',
    ],
    'password' => [
        'title' => 'Zmień swoje hasło',
        'requirements' => 'Twoje nowe hasło powinno mieć co najmniej 8 znaków.',
        'updated' => 'Twoje hasło zostało zaktualizowane.',
    ],
    'two_factor' => [
        'button' => 'Skonfiguruj uwierzytelnianie dwuetapowe',
        'disabled' => 'Uwierzytelnianie dwuetapowe zostało wyłączone na Twoim koncie. Nie będziesz już proszony o podanie tokenu podczas logowania.',
        'enabled' => 'Uwierzytelnianie dwuetapowe zostało włączone na Twoim koncie! Od teraz podczas logowania będziesz musiał podać kod wygenerowany przez swoje urządzenie.',
        'invalid' => 'Podany token jest nieprawidłowy.',
        'setup' => [
            'title' => 'Skonfiguruj uwierzytelnianie dwuetapowe.',
            'help' => 'Nie udało Ci się zeskanować kodu? Wprowadź poniższy kod do swojej aplikacji:',
            'field' => 'Wprowadź token',
        ],
        'disable' => [
            'title' => 'Wyłącz uwierzytelnianie dwuetapowe',
            'field' => 'Wprowadź token',
        ],
    ],
];
