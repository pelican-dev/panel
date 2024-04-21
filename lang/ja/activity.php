<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'ログインに失敗しました。',
        'success' => 'ログインしました。',
        'password-reset' => 'パスワードを再設定しました。',
        'reset-password' => 'パスワードの再設定が要求されました。',
        'checkpoint' => '二段階認証が要求されました。',
        'recovery-token' => '二段階認証の回復トークンを使用しました。',
        'token' => '二段階認証を有効化しました。',
        'ip-blocked' => '「:identifier」にないIPアドレスからのリクエストをブロックしました。',
        'sftp' => [
            'fail' => 'SFTPのログインに失敗しました。',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'メールアドレスを「:old」から「:new」に変更しました。',
            'password-changed' => 'パスワードを変更しました。',
        ],
        'api-key' => [
            'create' => 'APIキー「:identifier」を作成しました。',
            'delete' => 'APIキー「:identifier」を削除しました。',
        ],
        'ssh-key' => [
            'create' => 'SSHキー「:identifier」を追加しました。',
            'delete' => 'SSHキー「:identifier」を削除しました。',
        ],
        'two-factor' => [
            'create' => '二段階認証を有効化しました。',
            'delete' => '二段階認証を無効化しました。',
        ],
    ],
    'server' => [
        'reinstall' => 'サーバーを再インストールしました。',
        'console' => [
            'command' => 'サーバーで「:command」を実行しました。',
        ],
        'power' => [
            'start' => 'サーバーを起動しました。',
            'stop' => 'サーバーを停止しました。',
            'restart' => 'サーバーを再起動しました。',
            'kill' => 'サーバーを強制停止しました。',
        ],
        'backup' => [
            'download' => 'バックアップ「:name」をダウンロードしました。',
            'delete' => 'バックアップ「:name」を削除しました。',
            'restore' => 'バックアップ「:name」を復元しました。（削除されたファイル: :truncate）',
            'restore-complete' => 'バックアップ「:name」から復元しました。',
            'restore-failed' => 'バックアップ「:name」からの復元に失敗しました。',
            'start' => 'バックアップ「:name」を開始しました。',
            'complete' => 'バックアップ「:name」が完了しました。',
            'fail' => 'バックアップ「:name」に失敗しました。',
            'lock' => 'バックアップ「:name」をロックしました。',
            'unlock' => 'バックアップ「:name」のロックを解除しました。',
        ],
        'database' => [
            'create' => 'データベース「:name」を作成しました。',
            'rotate-password' => 'データベース「:name」のパスワードを変更しました。',
            'delete' => 'データベース「:name」を削除しました。',
        ],
        'file' => [
            'compress_one' => 'ファイル「:directory:files」を圧縮しました。',
            'compress_other' => '「:directory」内の:count個のファイルを圧縮しました。',
            'read' => 'ファイル「:file」の内容を表示しました。',
            'copy' => 'ファイル「:file」を複製しました。',
            'create-directory' => 'ディレクトリ「:directory:name」を作成しました。',
            'decompress' => 'ディレクトリ「:directory」内の「:files」を展開しました。',
            'delete_one' => 'ファイル「:directory:file.0」を削除しました。',
            'delete_other' => 'ディレクトリ「:directory」内の:count個のファイルを削除しました。',
            'download' => 'ファイル「:file」をダウンロードしました。',
            'pull' => '「:url」から「:directory」にダウンロードしました。',
            'rename_one' => '「:directory:files.0.from」から「:directory:files.0.to」にファイル名を変更しました。',
            'rename_other' => '「:directory」内の:count個のファイル名を変更しました。',
            'write' => 'ファイル「:file」の内容を変更しました。',
            'upload' => 'ファイルをアップロードしました。',
            'uploaded' => 'ファイル「:directory:file」をアップロードしました。',
        ],
        'sftp' => [
            'denied' => 'SFTPアクセスをブロックしました。',
            'create_one' => 'ファイル「:files.0」を作成しました。',
            'create_other' => ':count個のファイルを作成しました。',
            'write_one' => '「:files.0」の内容を変更しました。',
            'write_other' => ':count個のファイルの内容を変更しました。',
            'delete_one' => 'ファイル「:files.0」を削除しました。',
            'delete_other' => ':count個のファイルを削除しました。',
            'create-directory_one' => 'ディレクトリ「:files.0」を作成しました。',
            'create-directory_other' => ':count個のディレクトリを作成しました。',
            'rename_one' => '「:files.0.from」から「:files.0.to」に名前を変更しました。',
            'rename_other' => ':count個のファイル名を変更しました。',
        ],
        'allocation' => [
            'create' => 'ポート「:allocation」を割り当てました。',
            'notes' => 'ポート「:allocation」のメモを「:old」から「:new」に更新しました。',
            'primary' => 'ポート「:allocation」をプライマリとして割り当てました。',
            'delete' => 'ポート「:allocation」を削除しました。',
        ],
        'schedule' => [
            'create' => 'スケジュール「:name」を作成しました。',
            'update' => 'スケジュール「:name」を更新しました。',
            'execute' => 'スケジュール「:name」を手動で実行しました。',
            'delete' => 'スケジュール「:name」を削除しました。',
        ],
        'task' => [
            'create' => 'スケジュール「:name」にタスク「:action」を作成しました。',
            'update' => 'スケジュール「:name」のタスク「:action」を更新しました。',
            'delete' => 'スケジュール「:name」のタスクを削除しました。',
        ],
        'settings' => [
            'rename' => 'サーバー名を「:old」から「:new」に変更しました。',
            'description' => 'サーバー説明を「:old」から「:new」に変更しました。',
        ],
        'startup' => [
            'edit' => '変数「:variable」を「:old」から「:new」に変更しました。',
            'image' => 'Dockerイメージを「:old」から「:new」に更新しました。',
        ],
        'subuser' => [
            'create' => 'サブユーザー「:email」を追加しました。',
            'update' => 'サブユーザー「:email」の権限を変更しました。',
            'delete' => 'サブユーザー「:email」を削除しました。',
        ],
    ],
];
