<?php

return [
    'title' => 'Resumen de la cuenta',
    'email' => [
        'title' => 'Actualizar dirección de correo',
        'button' => 'Actualizar Correo',
        'updated' => 'Su correo principal ha sido actualizado.',
    ],
    'password' => [
        'title' => 'Actualizar Contraseña',
        'button' => 'Actualizar Contraseña',
        'requirements' => 'Su nueva contraseña debe tener al menos 8 caracteres de longitud y ser usada únicamente para este sitio web.',
        'validation' => [
            'account_password' => 'Debes proporcionar la contraseña de tu cuenta.',
            'current_password' => 'Debes proporcionar tu contraseña actual.',
            'password_confirmation' => 'La confirmación de contraseña no coincide con la contraseña introducida.',
        ],
        'updated' => 'Tu contraseña ha sido actualizada.',
    ],
    'two_factor' => [
        'title' => 'Verificación en dos pasos',
        'button' => 'Configurar autenticación de 2 factores',
        'disabled' => 'La autenticación de dos factores ha sido desactivada en tu cuenta. Ya no se te pedirá que proporciones un token al iniciar sesión.',
        'enabled' => '¡La autenticación de dos factores ha sido activada en tu cuenta! A partir de ahora, al iniciar sesión, se te pedirá que proporciones el código generado por tu dispositivo.',
        'invalid' => 'El token proporcionado no era válido.',
        'enable' => [
            'help' => 'Actualmente no tienes habilitada la verificación en dos pasos en tu cuenta. Haz clic en el botón de abajo para empezar a configurarla.',
            'button' => 'Activar AF2',
        ],
        'disable' => [
            'help' => 'La verificación en dos pasos está activada en tu cuenta.',
            'title' => 'Desactivar autenticación de dos factores',
            'field' => 'Introduce el token',
            'button' => 'Desactivar AF2',
        ],
        'setup' => [
            'title' => 'Habilitar verificación en dos pasos',
            'subtitle' => 'Ayuda a proteger tu cuenta de acceso no autorizado. Se te pedirá un código de verificación cada vez que inicies sesión.',
            'help' => 'Escanee el código QR de arriba usando la aplicación de autenticación en dos pasos de su elección. Luego, introduzca el código de 6 dígitos generado en el campo de abajo.',
        ],

        'required' => [
            'title' => 'Requiere verificación de 2 pasos',
            'description' => 'Tu cuenta debe tener habilitada la autenticación en dos factores para continuar.',
        ],
    ],
];
