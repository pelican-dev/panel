<?php

return [
    'sign_in' => 'ログイン',
    'go_to_login' => 'ログイン画面に移動',
    'failed' => '入力された情報に一致するアカウントが見つかりませんでした。',

    'forgot_password' => [
        'label' => 'パスワードを忘れた場合',
        'label_help' => 'パスワードを再設定する手順を受け取るには、アカウントのメールアドレスを入力してください。',
        'button' => 'アカウントの回復',
    ],

    'reset_password' => [
        'button' => '再設定しログイン',
    ],

    'two_factor' => [
        'label' => '二段階認証のトークン',
        'label_help' => '続行するには、6桁の認証コードが必要です。お使いのデバイスで生成されたコードを入力してください。',
        'checkpoint_failed' => '二段階認証のコードが無効です。',
    ],

    'throttle' => 'ログイン試行回数が多すぎます。:seconds秒後にもう一度お試しください。',
    'password_requirements' => 'パスワードは8文字以上で、推測されにくいパスワードを使用してください。',
    '2fa_must_be_enabled' => '管理者は、このPanelの使用に二段階認証を必須にしています。',
];
