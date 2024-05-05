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
        'updated' => 'Ваш пароль был изменен.',
    ],
    'two_factor' => [
        'title' => 'Two-Step Verification',
        'button' => 'Настроить двухфакторную аутентификацию',
        'disabled' => 'Двухфакторная аутентификация была отключена для вашего аккаунта. Вам больше не будет предлагаться подтвердить авторизацию.',
        'enabled' => 'Двухфакторная аутентификация была включена для вашего аккаунта! Теперь при входе вам необходимо будет предоставить код, сгенерированный вашим устройством.',
        'invalid' => 'Указанный код недействителен.',
        'enable' => [
            'help' => 'You do not currently have two-step verification enabled on your account. Click the button below to begin configuring it.',
            'button' => 'Enable Two-Step',
        ],
        'disable' => [
            'help' => 'Two-step verification is currently enabled on your account.',
            'title' => 'Отключить двухфакторную авторизацию',
            'field' => 'Введите код',
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
