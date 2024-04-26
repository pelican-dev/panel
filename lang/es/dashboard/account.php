<?php

return [
    'title' => 'Account Overview',
    'email' => [
        'title' => 'Update Email Address',
        'button' => 'Update Email',
        'updated' => 'Your primary email has been updated.',
    ],
    'password' => [
        'title' => 'Update Password',
        'button' => 'Update Password',
        'requirements' => 'Your new password should be at least 8 characters in length and unique to this website.',
        'validation' => [
            'account_password' => 'You must provide your account password.',
            'current_password' => 'You must provide your current password.',
            'password_confirmation' => 'Password confirmation does not match the password you entered.',
        ],
        'updated' => 'Tu contraseña ha sido actualizada.',
    ],
    'two_factor' => [
        'title' => 'Two-Step Verification',
        'button' => 'Configurar autenticación de 2 factores',
        'disabled' => 'La autenticación de dos factores ha sido desactivada en tu cuenta. Ya no se te pedirá que proporciones un token al iniciar sesión.',
        'enabled' => '¡La autenticación de dos factores ha sido activada en tu cuenta! A partir de ahora, al iniciar sesión, se te pedirá que proporciones el código generado por tu dispositivo.',
        'invalid' => 'El token proporcionado no era válido.',
        'enable' => [
            'help' => 'You do not currently have two-step verification enabled on your account. Click the button below to begin configuring it.',
            'button' => 'Enable Two-Step',
        ],
        'disable' => [
            'help' => 'Two-step verification is currently enabled on your account.',
            'title' => 'Desactivar autenticación de dos factores',
            'field' => 'Introduce el token',
            'button' => 'Disable Two-Step',
        ],
        'setup' => [
            'title' => 'Enable Two-Step Verification',
            'subtitle' => "Help protect your account from unauthorized access. You'll be prompted for a verification code each time you sign in.",
            'help' => 'Scan the QR code above using the two-step authentication app of your choice. Then, enter the 6-digit code generated into the field below.',
        ],

        'required' => [
            'title' => '2-Factor Required',
            'description' => 'Your account must have two-factor authentication enabled in order to continue.',
        ],
    ],
];
