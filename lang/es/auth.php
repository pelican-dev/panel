<?php

return [
    'sign_in' => 'Iniciar sesión',
    'go_to_login' => 'Ir al inicio de sesión',
    'failed' => 'No se pudo encontrar ninguna cuenta que coincida con esas credenciales.',

    'forgot_password' => [
        'label' => '¿Olvidaste tu contraseña?',
        'label_help' => 'Ingresa la dirección de correo electrónico de tu cuenta para recibir instrucciones sobre cómo restablecer tu contraseña.',
        'button' => 'Recuperar cuenta',
    ],

    'reset_password' => [
        'button' => 'Restablecer e iniciar sesión',
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
