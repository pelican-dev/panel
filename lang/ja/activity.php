<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => 'ログインに失敗しました',
        'success' => 'ログインしました',
        'password-reset' => 'パスワードのリセット',
        'reset-password' => 'パスワードリセットを要求する',
        'checkpoint' => '二段階認証がリクエストされました',
        'recovery-token' => '二段階認証回復トークン',
        'token' => '二段階認証が完了しました。',
        'ip-blocked' => 'リストにないIPアドレスをブロックしました。 :identifier',
        'sftp' => [
            'fail' => 'SFTPのログインに失敗しました。',
        ],
    ],
    'user' => [
        'account' => [
            'email-changed' => 'メールアドレスを変更しました。旧 :old 新 :new',
            'password-changed' => 'パスワードを変更しました。',
        ],
        'api-key' => [
            'create' => 'APIキーを作成しました。:identifier',
            'delete' => 'APIキーを削除しました。:identifier',
        ],
        'ssh-key' => [
            'create' => 'アカウントにSSHキーを追加しました。:fingerprint',
            'delete' => 'アカウントからSSHキーを削除しました。:fingerprint',
        ],
        'two-factor' => [
            'create' => '二段階認証を有効化しました。',
            'delete' => '二段階認証を無効化しました',
        ],
    ],
    'server' => [
        'reinstall' => 'サーバーを再インストールしました',
        'console' => [
            'command' => 'サーバーでコマンドを実行しました。:command',
        ],
        'power' => [
            'start' => 'サーバーを起動しました。',
            'stop' => 'サーバーを停止しました。',
            'restart' => 'サーバーを再起動しました。',
            'kill' => 'サーバーを強制終了しました。',
        ],
        'backup' => [
            'download' => 'バックアップをダウンロードしました。:name',
            'delete' => 'バックアップを削除しました。:name',
            'restore' => 'バックアップを復元しました。:name (削除されたファイル: :truncate)',
            'restore-complete' => 'バックアップからの復元が完了しました。:name',
            'restore-failed' => 'バックアップからの復元に失敗しました。:name',
            'start' => 'バックアップの作成を開始しました。:name',
            'complete' => 'バックアップが完了しました。:name',
            'fail' => 'バックアップに失敗しました。:name',
            'lock' => 'バックアップをロックしました。:name',
            'unlock' => 'バックアップをアンロックしました。:name',
        ],
        'database' => [
            'create' => 'データベースを作成しました。:name',
            'rotate-password' => 'データベースのパスワードをローテーションしました。:name',
            'delete' => 'データベースを削除しました。:name',
        ],
        'file' => [
            'compress_one' => 'ファイルを圧縮しました。:directory:file',
            'compress_other' => ':directoryの:count個のファイルを圧縮しました。',
            'read' => ':fileの内容を表示しました。',
            'copy' => ':fileのコピーを作成しました。',
            'create-directory' => 'ディレクトリを作成しました。:directory:name',
            'decompress' => ':directoryの:filesを展開しました。',
            'delete_one' => '削除しました。:directory:file.0',
            'delete_other' => ':directoryの:count個のファイルを削除しました。',
            'download' => ':file をダウンロードしました。',
            'pull' => ':url から :directory にリモートファイルをダウンロードしました。',
            'rename_one' => ':directory:files.0.from から :directory:files.0.to に名前を変更しました。',
            'rename_other' => ':directoryの:count個のファイル名を変更しました。',
            'write' => '新しい内容を :file に書き込みました。',
            'upload' => 'ファイルのアップロードを開始しました。',
            'uploaded' => ':directory:fileをアップロードしました。',
        ],
        'sftp' => [
            'denied' => '権限によりSFTPアクセスがブロックされました。',
            'create_one' => ':files.0 を作成しました。',
            'create_other' => ':count個の新規ファイルを作成しました。',
            'write_one' => ':files.0 の内容を変更しました。',
            'write_other' => ':count 個のファイルの内容を変更しました。',
            'delete_one' => ':files.0 を削除しました。',
            'delete_other' => ':count 個のファイルを削除しました。',
            'create-directory_one' => ':files.0 ディレクトリを作成しました。',
            'create-directory_other' => ':count 個のディレクトリを作成しました。',
            'rename_one' => ':files.0.from から :files.0.to に名前を変更しました。',
            'rename_other' => ':count 個のファイルの名前を変更または移動しました。',
        ],
        'allocation' => [
            'create' => 'サーバーに:allocationを追加しました。',
            'notes' => ':allocation のメモを ":old" から ":new" に更新しました。',
            'primary' => ':allocation をプライマリサーバーの割り当てとして設定しました。',
            'delete' => ':allocation を削除しました。',
        ],
        'schedule' => [
            'create' => 'スケジュールを作成しました。:name',
            'update' => 'スケジュールを更新しました。:name',
            'execute' => 'スケジュールを手動で実行しました。:name',
            'delete' => 'スケジュールを削除しました。:name',
        ],
        'task' => [
            'create' => 'スケジュールに新しい ":action" タスクを作成しました。:name',
            'update' => 'スケジュールの ":action" タスクを更新しました。:name',
            'delete' => 'スケジュールのタスクを削除しました。:name',
        ],
        'settings' => [
            'rename' => 'サーバー名を :old から :new に変更しました。',
            'description' => 'サーバーの説明を :old から :new に変更しました。',
        ],
        'startup' => [
            'edit' => '変数 :variable を ":old" から ":new" に変更しました。',
            'image' => 'サーバーの Docker イメージを :old から :new に更新しました。',
        ],
        'subuser' => [
            'create' => 'サブユーザーとして :email を追加しました。',
            'update' => ':email のサブユーザー権限を更新しました。',
            'delete' => 'サブユーザー :email を削除しました',
        ],
    ],
];
