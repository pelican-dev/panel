<?php

return [
    'return_to_login' => 'ログイン画面に戻る',
    'failed' => '入力された情報に一致するアカウントが見つかりませんでした。',

    'login' => [
        'title' => 'ログイン',
        'button' => 'ログイン',
        'required' => [
            'username_or_email' => 'ユーザー名またはメールアドレスを入力してください。',
            'password' => 'パスワードを入力してください。',
        ],
    ],

    'forgot_password' => [
        'title' => 'パスワードの再設定',
        'label' => 'パスワードを忘れた場合',
        'label_help' => 'パスワードを再設定する手順を受け取るには、アカウントのメールアドレスを入力してください。',
        'button' => 'メールを送信',
        'required' => [
            'email' => 'メールアドレスを入力してください。',
        ],
    ],

    'reset_password' => [
        'title' => 'パスワードの再設定',
        'button' => 'パスワードの再設定',
        'new_password' => '新しいパスワード',
        'confirm_new_password' => '新しいパスワードの確認',
        'requirement' => [
            'password' => '新しいパスワードは8文字以上で入力してください。',
        ],
        'required' => [
            'password' => '新しいパスワードを入力してください。',
            'password_confirmation' => '新しいパスワードが一致しません。',
        ],
        'validation' => [
            'password' => '新しいパスワードは8文字以上で入力してください。',
            'password_confirmation' => '新しいパスワードが一致しません。',
        ],
    ],

    'checkpoint' => [
        'title' => '二段階認証',
        'recovery_code' => '回復コード',
        'recovery_code_description' => 'このアカウントで二要素認証をセットアップしたときに生成された回復コードのうち、どれか1つを入力してください。',
        'authentication_code' => '認証コード',
        'authentication_code_description' => '6桁の二要素認証コードを入力してください。',
        'button' => '続行',
        'lost_device' => '回復コードを使用',
        'have_device' => '認証コードを使用',
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
