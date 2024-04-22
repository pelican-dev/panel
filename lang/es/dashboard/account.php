<?php

return [
    'email' => [
        'title' => 'Actualizar tu correo electrónico',
        'updated' => 'Tu dirección de correo electrónico ha sido actualizada.',
    ],
    'password' => [
        'title' => 'Cambia tu contraseña',
        'requirements' => 'Tu nueva contraseña debe tener al menos 8 caracteres de longitud.',
        'updated' => 'Tu contraseña ha sido actualizada.',
    ],
    'two_factor' => [
        'button' => 'Configurar autenticación de 2 factores',
        'disabled' => 'La autenticación de dos factores ha sido desactivada en tu cuenta. Ya no se te pedirá que proporciones un token al iniciar sesión.',
        'enabled' => '¡La autenticación de dos factores ha sido activada en tu cuenta! A partir de ahora, al iniciar sesión, se te pedirá que proporciones el código generado por tu dispositivo.',
        'invalid' => 'El token proporcionado no era válido.',
        'setup' => [
            'title' => 'Configurar autenticación de dos factores',
            'help' => '¿No puedes escanear el código? Ingresa el código a continuación en tu aplicación:',
            'field' => 'Introduce el token',
        ],
        'disable' => [
            'title' => 'Desactivar autenticación de dos factores',
            'field' => 'Introduce el token',
        ],
    ],
];
