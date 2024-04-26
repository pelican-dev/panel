<?php

return [
    'exceptions' => [
        'user_has_servers' => 'No se puede eliminar un usuario con servidores activos asociados a su cuenta. Por favor, elimina sus servidores antes de continuar.',
        'user_is_self' => 'No se puede eliminar tu propia cuenta de usuario.',
    ],
    'notices' => [
        'account_created' => 'La cuenta se ha creado correctamente.',
        'account_updated' => 'La cuenta se ha actualizado correctamente.',
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
