<?php

return [
    'user' => [
        'search_users' => 'ユーザー名、ユーザーID、またはメールアドレスを入力してください',
        'select_search_user' => '削除するユーザーID（再検索するには\'0\'を入力してください）',
        'deleted' => 'ユーザーをパネルから削除しました。',
        'confirm_delete' => 'グループからこのユーザを削除しますか？',
        'no_users_found' => '指定された検索条件に対するユーザーが見つかりませんでした。',
        'multiple_found' => '指定されたユーザーに対して複数のアカウントが見つかりました。--no-interaction フラグのため、ユーザーを削除できません。',
        'ask_admin' => 'このユーザーは管理者ですか？',
        'ask_email' => 'メールアドレス',
        'ask_username' => 'ユーザー名',
        'ask_name_first' => '名',
        'ask_name_last' => '姓',
        'ask_password' => 'パスワード',
        'ask_password_tip' => 'ランダムなパスワードでアカウントを作成したい場合は、このコマンド(CTRL+C) を実行し、`--no-password` フラグを渡してください。',
        'ask_password_help' => 'パスワードは8文字以上で、少なくとも1つの大文字と数字が含まれている必要があります。',
        '2fa_help_text' => [
            'このコマンドが有効になっている場合、ユーザーのアカウントの二段階認証を無効にします。 これは、ユーザーがアカウントからロックアウトされている場合にのみ、アカウント回復コマンドとして使用する必要があります。',
            'この動作を行わない場合は、CTRL+C を押してこのプロセスを終了します。',
        ],
        '2fa_disabled' => '二段階認証が:emailで無効になりました。',
    ],
    'schedule' => [
        'output_line' => '`:schedule` (:hash) で最初のタスクのジョブを送信しています。',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'サービスバックアップファイル:fileを削除しています。',
    ],
    'server' => [
        'rebuild_failed' => 'ノード":node"の":name"(#:id)の再構築リクエストがエラーで失敗しました: :message',
        'reinstall' => [
            'failed' => 'ノード ":node" の ":name" (#:id) の再インストールリクエストがエラーで失敗しました: :message',
            'confirm' => 'サーバーのグループに対して再インストールしようとしています。続行しますか？',
        ],
        'power' => [
            'confirm' => ':countサーバーに対して:actionを実行しようとしています。続行しますか？',
            'action_failed' => 'ノード ":node" の ":name" (#:id) の電源アクションリクエストはエラーで失敗しました: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTPホスト (例: smtp.gmail.com)',
            'ask_smtp_port' => 'SMTPポート',
            'ask_smtp_username' => 'SMTPユーザ名',
            'ask_smtp_password' => 'SMTPパスワード',
            'ask_mailgun_domain' => 'Mailgunドメイン',
            'ask_mailgun_endpoint' => 'Mailgun エンドポイント',
            'ask_mailgun_secret' => 'Mailgunシークレット',
            'ask_mandrill_secret' => 'Mandrillシークレット',
            'ask_postmark_username' => 'Postmark APIキー',
            'ask_driver' => 'メールを送信するためにどのドライバーを使用する必要がありますか？',
            'ask_mail_from' => 'メールアドレスのメール送信元',
            'ask_mail_name' => 'メールアドレスの表示先名',
            'ask_encryption' => '暗号化の方法',
        ],
    ],
];
