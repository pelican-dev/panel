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
        'checkpoint' => '二段階認証が要求されました',
        'recovery-token' => '二段階認証の回復トークンを使用しました',
        'token' => '二段階認証を有効化しました',
        'ip-blocked' => 'リスト外のIPアドレスからの <b>:identifier</b> へのリクエストはブロックされました。',
        'sftp' => [
            'fail' => 'SFTPのログインに失敗しました',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => 'ユーザー名を <b>:old</b> から <b>:new</b> に変更しました',
            'email-changed' => 'メールアドレスを <b>:old</b>から<b>:new</b>に変更しました',
            'password-changed' => 'パスワードを変更しました',
        ],
        'api-key' => [
            'create' => '新しいAPIキー <b>:identifier</b> を作成しました。',
            'delete' => 'APIキー <b>:identifier</b> を削除しました。',
        ],
        'ssh-key' => [
            'create' => 'アカウントにSSHキー <b>:fingerprint</b> を追加しました。',
            'delete' => 'アカウントからSSHキー <b>:fingerprint</b> を削除しました。',
        ],
        'two-factor' => [
            'create' => '2段階認証を有効化',
            'delete' => '2段階認証を無効化',
        ],
    ],
    'server' => [
        'console' => [
            'command' => 'サーバー上で "<b>:command</b>" を実行しました。',
        ],
        'power' => [
            'start' => 'サーバーを起動',
            'stop' => 'サーバーを停止',
            'restart' => 'サーバーを再起動',
            'kill' => 'サーバーを強制停止',
        ],
        'backup' => [
            'download' => '<b>:name</b> バックアップをダウンロードしました。',
            'delete' => '<b>:name</b> バックアップを削除しました。',
            'restore' => '<b>:name</b> バックアップを復元しました (削除されたファイル: <b>:truncate</b>)。',
            'restore-complete' => '<b>:name</b> バックアップの復元が完了しました。',
            'restore-failed' => '<b>:name</b> バックアップの復元を完了できませんでした。',
            'start' => '新しいバックアップ <b>:name</b> を開始しました。',
            'complete' => 'バックアップ <b>:name</b> を完了としてマークしました。',
            'fail' => '<b>:name</b> バックアップを失敗としてマークしました。',
            'lock' => '<b>:name</b> バックアップをロックしました。',
            'unlock' => '<b>:name</b> バックアップのロックを解除しました。',
            'rename' => 'バックアップの名前を "<b>:old_name</b>" から "<b>:new_name</b>" に変更しました',
        ],
        'database' => [
            'create' => '新しいデータベース <b>:name</b> を作成しました。',
            'rotate-password' => 'データベース <b>:name</b> のパスワードを更新しました。',
            'delete' => 'データベース <b>:name</b> を削除しました。',
        ],
        'file' => [
            'compress' => '<b>:directory:files</b> を圧縮しました | <b>:directory</b> 内で <b>:count</b> 個のファイルを圧縮しました。',
            'read' => '<b>:file</b> の内容を表示しました。',
            'copy' => '<b>:file</b> のコピーを作成しました。',
            'create-directory' => 'ディレクトリ <b>:directory:name</b> を作成しました。',
            'decompress' => '<b>:directory</b> 内で <b>:file</b> を解凍しました。',
            'delete' => '<b>:directory:files</b> を削除しました | <b>:directory</b> 内の <b>:count</b> 個のファイルを削除しました。',
            'download' => '<b>:file</b> をダウンロードしました。',
            'pull' => '<b>:url</b> から <b>:directory</b> にリモートファイルをダウンロードしました。',
            'rename' => '<b>:from</b> を <b>:to</b> に移動/名前変更しました|<b>:directory</b> 内の <b>:count</b> 個のファイルを移動/名前変更しました',
            'write' => '<b>:file</b> に新しい内容を書き込みました。',
            'upload' => 'ファイルをアップロードしました。',
            'uploaded' => '<b>:directory:file</b> をアップロードしました。',
        ],
        'sftp' => [
            'denied' => 'SFTPアクセスをブロックしました。',
            'create' => '<b>:files</b> を作成しました | 新しいファイルを <b>:count</b> 個作成しました。',
            'write' => '<b>:files</b> の内容を変更しました | <b>:count</b> 個のファイルの内容を変更しました。',
            'delete' => '<b>:files</b> を削除しました | <b>:count</b> 個のファイルを削除しました。',
            'create-directory' => '<b>:files</b> ディレクトリを作成しました | ディレクトリを <b>:count</b> 個作成しました。',
            'rename' => '<b>:from</b> を <b>:to</b> にリネームまたは移動しました | <b>:count</b> 個のファイルをリネームまたは移動しました。',
        ],
        'allocation' => [
            'create' => 'サーバーに <b>:allocation</b> を追加しました。',
            'notes' => '<b>:allocation</b> のメモを "<b>:old</b>" から "<b>:new</b>" に更新しました。',
            'primary' => 'サーバーの主要な割り当てとして <b>:allocation</b> を設定しました。',
            'delete' => '割り当て <b>:allocation</b> を削除しました。',
        ],
        'schedule' => [
            'create' => 'スケジュール <b>:name</b> を作成しました。',
            'update' => '<b>:nane</b>のスケジュールを更新しました',
            'execute' => '<b>:name</b> スケジュールを手動で実行しました',
            'delete' => 'スケジュールを削除しました',
        ],
        'task' => [
            'create' => '<b>:action</b>を<b>:name</b>のスケジュールに作成しました',
            'update' => '「<b>:name</b>」スケジュールの「<b>:action</b>」タスクを更新しました',
            'delete' => 'スケジュール「<b>:name</b>」のタスク「<b>:action</b>」を削除しました',
        ],
        'settings' => [
            'rename' => 'サーバー名を「<b>:old</b>」から「<b>:new</b>」に変更しました。',
            'description' => 'サーバーの説明を「<b>:old</b>」から「<b>:new</b>」に変更しました。',
            'reinstall' => 'サーバーを再インストール',
        ],
        'startup' => [
            'edit' => '変数「<b>:variable</b>」の値を「<b>:old</b>」から「<b>:new</b>」に変更しました',
            'image' => 'サーバーのDockerイメージを <b>:old</b> から <b>:new</b> に更新しました。',
            'command' => 'サーバーの起動コマンドを<b>:old</b>から<b>:new</b>に変更しました。',
        ],
        'subuser' => [
            'create' => '<b>:email</b> をサブユーザーとして追加しました',
            'update' => '<b>:email</b> のサブユーザー権限を更新しました',
            'delete' => '<b>:email</b> のサブユーザー権限を更新しました',
        ],
        'mount' => [
            'update' => 'サーバーのマウントを変更しました',
        ],
        'crashed' => 'サーバーがクラッシュしました',
    ],
];
