<?php

return [
    'title' => 'Account Overview',
    'email' => [
        'title' => 'Atualize seu e-mail',
        'button' => 'Update Email',
        'updated' => 'Seu endereço de e-mail foi atualizado.',
    ],
    'password' => [
        'title' => 'Altere sua senha',
        'button' => 'Update Password',
        'requirements' => 'Sua nova senha deve ter pelo menos 8 caracteres de comprimento.',
        'validation' => [
            'account_password' => 'You must provide your account password.',
            'current_password' => 'You must provide your current password.',
            'password_confirmation' => 'Password confirmation does not match the password you entered.',
        ],
        'updated' => 'Sua senha foi atualizada.',
    ],
    'two_factor' => [
        'title' => 'Two-Step Verification',
        'button' => 'Configurar Autenticação de Dois Fatores',
        'disabled' => 'A autenticação de dois fatores foi desativada em sua conta. Você não será mais solicitado a fornecer um token ao fazer login.',
        'enabled' => 'A autenticação de dois fatores foi ativada em sua conta! A partir de agora, ao fazer login, você precisará fornecer o código gerado pelo seu dispositivo.',
        'invalid' => 'O token fornecido era inválido.',
        'enable' => [
            'help' => 'You do not currently have two-step verification enabled on your account. Click the button below to begin configuring it.',
            'button' => 'Enable Two-Step',
        ],
        'disable' => [
            'help' => 'Two-step verification is currently enabled on your account.',
            'title' => 'Desativar autenticação de dois fatores',
            'field' => 'Insira o token',
            'button' => 'Disable Two-Step',
        ],
        'setup' => [
            'title' => 'Configurar autenticação de dois fatores',
            'subtitle' => "Help protect your account from unauthorized access. You'll be prompted for a verification code each time you sign in.",
            'help' => 'Não pode escanear o código? Insira o código abaixo em seu aplicativo:',
        ],

        'required' => [
            'title' => '2-Factor Required',
            'description' => 'Your account must have two-factor authentication enabled in order to continue.',
        ],
    ],
];
