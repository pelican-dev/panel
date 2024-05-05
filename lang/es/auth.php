<?php

return [
    'return_to_login' => 'Volver al inicio de sesión',
    'failed' => 'No se pudo encontrar ninguna cuenta que coincida con esas credenciales.',

    'login' => [
        'title' => 'Inicia sesión para continuar',
        'button' => 'Iniciar Sesión',
        'required' => [
            'username_or_email' => 'Debe proporcionar un nombre de usuario o correo electrónico.',
            'password' => 'Por favor, introduzca la contraseña de su cuenta.',
        ],
    ],

    'forgot_password' => [
        'title' => 'Solicitar restablecimiento de contraseña',
        'label' => '¿Olvidaste tu contraseña?',
        'label_help' => 'Ingresa la dirección de correo electrónico de tu cuenta para recibir instrucciones sobre cómo restablecer tu contraseña.',
        'button' => 'Enviar correo electrónico',
        'required' => [
            'email' => 'Debe proporcionarse una dirección de correo electrónico válida para continuar.',
        ],
    ],

    'reset_password' => [
        'title' => 'Restablecer Contraseña',
        'button' => 'Restablecer Contraseña',
        'new_password' => 'Nueva Contraseña',
        'confirm_new_password' => 'Confirmar Nueva Contraseña',
        'requirement' => [
            'password' => 'La contraseña debe contener 8 caracteres como mínimo.',
        ],
        'required' => [
            'password' => 'Se requiere una contraseña nueva.',
            'password_confirmation' => 'Tu nueva contraseña no coincide.',
        ],
        'validation' => [
            'password' => 'Tu nueva contraseña debe tener al menos 8 caracteres de longitud.',
            'password_confirmation' => 'Tu nueva contraseña no coincide.',
        ],
    ],

    'checkpoint' => [
        'title' => 'Punto de control',
        'recovery_code' => 'Código de Recuperación',
        'recovery_code_description' => 'Introduce uno de los códigos de recuperación generados cuando configuraste la autenticación de dos factores en esta cuenta para continuar.',
        'authentication_code' => 'Código de Autenticación',
        'authentication_code_description' => 'Introduzca el token de doble factor generado por su dispositivo.',
        'button' => 'Continuar',
        'lost_device' => 'Perdí mi dispositivo',
        'have_device' => 'Tengo mi dispositivo',
    ],

    'two_factor' => [
        'label' => 'Token de 2 Factores',
        'label_help' => 'Esta cuenta requiere un segundo nivel de autenticación para continuar. Por favor, ingresa el código generado por tu dispositivo para completar este inicio de sesión.',
        'checkpoint_failed' => 'El token de autenticación de dos factores no era válido.',
    ],

    'throttle' => 'Demasiados intentos de inicio de sesión. Por favor, inténtalo de nuevo en :seconds segundos.',
    'password_requirements' => 'La contraseña debe tener al menos 8 caracteres de longitud y debe ser única para este sitio.',
    '2fa_must_be_enabled' => 'El administrador ha requerido que la Autenticación de 2 Factores esté habilitada para tu cuenta para poder usar el Panel.',
];
