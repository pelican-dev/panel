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
        'hint' => '¡Este es el último administrador root!',
        'helper_text' => 'Debes tener al menos un administrador root en tu sistema.',
    ],
    'root_admin' => 'Administrador (Root)',
    'language' => [
        'helper_text1' => '¡Tu idioma (:state) no ha sido traducido todavía!\nPero no te preocupes, puedes ayudar a arreglarlo con',
        'helper_text2' => 'contribuyendo directamente aquí',
    ],
];
