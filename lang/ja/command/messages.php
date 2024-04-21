<?php

return [
    'user' => [
        'search_users' => 'ユーザー名、ユーザーID、またはメールアドレスを入力してください。',
        'select_search_user' => '削除するユーザーID（再検索する場合は「0」を入力してください。）',
        'deleted' => 'ユーザーを削除しました。',
        'confirm_delete' => 'ユーザーを削除しますか？',
        'no_users_found' => '検索条件に一致するユーザーが見つかりませんでした。',
        'multiple_found' => '指定されたユーザーに対して複数のアカウントが見つかりました。「--no-interaction」フラグがあるため、ユーザーを削除できません。',
        'ask_admin' => 'このユーザーは管理者ですか？',
        'ask_email' => 'メールアドレス',
        'ask_username' => 'ユーザー名',
        'ask_name_first' => '名',
        'ask_name_last' => '姓',
        'ask_password' => 'パスワード',
        'ask_password_tip' => 'ランダムなパスワードでアカウントを作成したい場合は、コマンド「CTRL+C」を実行し、フラグ「--no-password」を設定してください。',
        'ask_password_help' => 'パスワードは8文字以上で、1文字以上の大文字、数字が必要です。',
        '2fa_help_text' => [
            'このコマンドが有効になっている場合、ユーザーのアカウントの二段階認証を無効にします。 これは、二段階認証アプリへのアクセス権を失った場合にのみ、アカウントを回復するためのコマンドとして使用してください。',
            'この動作を行わない場合は、「CTRL+C」でこのプロセスを終了します。',
        ],
        '2fa_disabled' => '「:email」で二段階認証が無効になりました。',
    ],
    'schedule' => [
        'output_line' => 'スケジュール「:schedule」（:hash）で最初のタスクのジョブを送信します。',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'サービスのバックアップファイル「:file」を削除します。',
    ],
    'server' => [
        'rebuild_failed' => 'ノード「:node」上の「:name」(#:id) の再構築リクエストがエラーで失敗しました: :message',
        'reinstall' => [
            'failed' => 'ノード「:node」上の「:name」(#:id) の再インストールリクエストがエラーで失敗しました: :message',
            'confirm' => 'サーバーのグループに対して再インストールしようとしています。続行しますか？',
        ],
        'power' => [
            'confirm' => ':count個のサーバーに対して「:action」を実行しようとしています。続行しますか？',
            'action_failed' => 'ノード「:node」上の「:name」(#:id) の電源アクションリクエストがエラーで失敗しました: :message',
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
            'ask_driver' => 'どのドライバを使用してメールを送信しますか？',
            'ask_mail_from' => 'メールアドレスのメール送信元',
            'ask_mail_name' => 'メールアドレスの表示先名',
            'ask_encryption' => '暗号化方法',
        ],
    ],
];
