<?php

return [
    'title' => 'Account Overview',
    'email' => [
        'title' => 'Изменить эл. почту',
        'button' => 'Update Email',
        'updated' => 'Ваш адрес эл. почты успешно изменен.',
    ],
    'password' => [
        'title' => 'Изменить пароль',
        'button' => 'Update Password',
        'requirements' => 'Длина вашего нового пароля должна быть не менее 8 символов.',
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
            'title' => 'Настройка двухфакторной авторизации',
            'subtitle' => "Help protect your account from unauthorized access. You'll be prompted for a verification code each time you sign in.",
            'help' => 'Не удается просканировать код? Введите код ниже в приложение:',
        ],

        'required' => [
            'title' => '2-Factor Required',
            'description' => 'Your account must have two-factor authentication enabled in order to continue.',
        ],
    ],
];
