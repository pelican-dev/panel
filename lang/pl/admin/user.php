<?php

return [
    'exceptions' => [
        'user_has_servers' => 'Nie można usunąć użytkownika, który ma przypisane do swojego konta aktywne serwery. Proszę usunąć serwery przypisane do tego konta przed kontynuowaniem.',
        'user_is_self' => 'Nie można usunąć własnego konta użytkownika.',
    ],
    'notices' => [
        'account_created' => 'Konto zostało pomyślnie utworzone.',
        'account_updated' => 'Konto zostało pomyślnie zaktualizowane.',
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
