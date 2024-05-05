<?php

return [
    'exceptions' => [
        'user_has_servers' => 'Nelze odstranit uživatele s aktivními servery připojenými k jeho účtu. Před pokračováním prosím odstraňte jeho servery.',
        'user_is_self' => 'Nemůžete smazat svůj vlastní uživatelský účet!',
    ],
    'notices' => [
        'account_created' => 'Účet byl úspěšně vytvořen',
        'account_updated' => 'Účet byl úspěšně aktualizován.',
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
