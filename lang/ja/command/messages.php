<?php

return [
    'user' => [
        'search_users' => 'ユーザー名、ユーザーID、またはメールアドレスを入力してください',
        'select_search_user' => '削除するユーザーのIDを入力してください（再検索する場合は「0」を入力）',
        'deleted' => 'パネルからユーザーを正常に削除しました。',
        'confirm_delete' => '本当にこのユーザーをパネルから削除してもよろしいですか？',
        'no_users_found' => '指定された検索条件に該当するユーザーが見つかりませんでした。',
        'multiple_found' => '指定されたユーザーに対して複数のアカウントが見つかりました。--no-interaction フラグのため、ユーザーを削除できません。',
        'ask_admin' => 'このユーザーは管理者ですか？',
        'ask_email' => 'メールアドレス',
        'ask_username' => 'ユーザー名',
        'ask_password' => 'パスワード',
        'ask_password_tip' => 'ランダムなパスワードをメールで送信してアカウントを作成したい場合は、このコマンドを再実行（CTRL+C）し、`--no-password` フラグを付けてください。',
        'ask_password_help' => 'パスワードは 8 文字以上で、少なくとも 1 つの大文字と数字を含める必要があります。',
        '2fa_help_text' => [
            'このコマンドは、ユーザーのアカウントに二段階認証が有効になっている場合に、無効にします。これは、ユーザーがアカウントにアクセスできなくなった際のアカウント回復用としてのみ使用してください。',
            'もし意図した操作でない場合は、CTRL+C を押してこのプロセスを終了してください。',
        ],
        '2fa_disabled' => ':email の二要素認証が無効になっています。',
    ],
    'schedule' => [
        'output_line' => '`:schedule` (:id) で最初のタスクのジョブを送信しています。',
    ],
    'maintenance' => [
        'deleting_service_backup' => 'サービスのバックアップファイル「:file」を削除します。',
    ],
    'server' => [
        'rebuild_failed' => 'ノード「:node」上の「:name」(#:id) の再構築リクエストがエラーで失敗しました: :message',
        'reinstall' => [
            'failed' => 'ノード「:node」上の「:name」(#:id) の再インストールリクエストがエラーで失敗しました: :message',
            'confirm' => '複数のサーバーに対して再インストールを実行しようとしています。続行してもよろしいですか？',
        ],
        'power' => [
            'confirm' => 'サーバー :count 台に対して :action を実行しようとしています。続行してもよろしいですか？',
            'action_failed' => 'ノード「:node」にある「:name」 (#:id) の電源操作要求が、エラー :message により失敗しました',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTPホスト (例: smtp.gmail.com)',
            'ask_smtp_port' => 'SMTPポート',
            'ask_smtp_username' => 'SMTPユーザー名',
            'ask_smtp_password' => 'SMTPパスワード',
            'ask_mailgun_domain' => 'Mailgunドメイン',
            'ask_mailgun_endpoint' => 'Mailgunエンドポイント',
            'ask_mailgun_secret' => 'Mailgunシークレット',
            'ask_mandrill_secret' => 'Mandrillシークレット',
            'ask_postmark_username' => 'Postmark APIキー',
            'ask_driver' => 'どのドライバを使用してメールを送信しますか？',
            'ask_mail_from' => '送信元メールアドレス',
            'ask_mail_name' => 'メールアドレスの表示名',
            'ask_encryption' => '使用する暗号化方式',
        ],
    ],
];
