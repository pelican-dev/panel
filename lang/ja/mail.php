<?php

return [
    'greeting' => ':name さん、こんにちは！',

    'account_created' => [
        'body' => 'このメールは、:app でアカウントが作成されたので送信されました。',
        'username' => 'ユーザー名: :username',
        'email' => 'メール: :email',
        'action' => 'アカウントをセットアップ',
    ],

    'added_to_server' => [
        'body' => 'あなたは次のサーバーのサブユーザーとして追加されており、サーバーに対する特定のコントロールが可能です。',
        'server_name' => 'サーバー名: :name',
        'action' => 'サーバーにアクセス',
    ],

    'removed_from_server' => [
        'body' => '以下のサーバーのサブユーザーとして削除されました。',
        'server_name' => 'サーバー名: :name',
        'action' => 'パネルを開く',
    ],

    'server_installed' => [
        'body' => 'サーバーのインストールが完了し、使用できるようになりました。',
        'server_name' => 'サーバー名: :name',
        'action' => 'ログイン',
    ],

    'backup_completed' => [
        'body_success' => 'バックアップの作成が完了しました。',
        'body_failed' => 'バックアップの作成に失敗しました。',
        'backup_name' => 'バックアップ名: :name',
        'server_name' => 'サーバー名: :name',
        'action' => 'バックアップを表示',
    ],

    'mail_tested' => [
        'subject' => 'パネルテストメッセージ',
        'body' => 'これはパネルメールシステムのテストです。問題ありません！',
    ],
];
