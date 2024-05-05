<?php

return [
    'exceptions' => [
        'user_has_servers' => 'Impossibile eliminare un utente con server attivi collegati al proprio account. Si prega di eliminare i loro server prima di continuare.',
        'user_is_self' => 'Impossibile eliminare il proprio account utente.',
    ],
    'notices' => [
        'account_created' => 'Il conto è stato creato correttamente.',
        'account_updated' => 'Il tuo account è stato aggiornato correttamente.',
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
