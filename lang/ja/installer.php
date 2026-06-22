<?php

return [
    'title' => 'パネルインストーラー',
    'requirements' => [
        'title' => 'システム要件',
        'sections' => [
            'version' => [
                'title' => 'PHPバージョン',
                'or_newer' => ':version またはそれ以上',
                'content' => 'PHPのバージョンは :versionです。',
            ],
            'extensions' => [
                'title' => 'PHP拡張機能',
                'good' => 'インストールするにはすべてのPHP拡張機能が必要です',
                'bad' => 'PHP拡張モジュールがありません: :extensions',
            ],
            'permissions' => [
                'title' => 'フォルダー権限',
                'good' => 'すべてのフォルダが正しい権限を持っています。',
                'bad' => '次のフォルダの権限が正しくありません: :folders',
            ],
        ],
        'exception' => '一部の要件が満たされていません',
    ],
    'environment' => [
        'title' => '環境',
        'fields' => [
            'app_name' => 'アプリ名',
            'app_name_help' => 'パネルの名前になります。',
            'app_url' => 'アプリ URL',
            'app_url_help' => 'パネルにアクセスするための URL になります。',
            'account' => [
                'section' => '管理者ユーザー',
                'email' => 'メールアドレス',
                'username' => 'ユーザー名',
                'password' => 'パスワード',
            ],
        ],
    ],
    'database' => [
        'title' => 'データベース',
        'driver' => 'データベースドライバ',
        'driver_help' => 'パネルデータベースに使用するドライバ。"SQLite"を推奨します。',
        'fields' => [
            'host' => 'データベースホスト',
            'host_help' => 'データベースのホスト。アクセス可能であることを確認してください。',
            'port' => 'データベースポート',
            'port_help' => 'データベースのポート。',
            'path' => 'データベースパス',
            'path_help' => 'database フォルダからの相対パスで .sqlite ファイルのパスを指定します。',
            'name' => 'データベース名',
            'name_help' => 'パネルデータベースの名前。',
            'username' => 'データベースユーザー名',
            'username_help' => 'データベースユーザーの名前。',
            'password' => 'データベースパスワード',
            'password_help' => 'データベースユーザーのパスワード。空白でも可。',
        ],
        'exceptions' => [
            'connection' => 'データベース接続に失敗しました',
            'migration' => 'マイグレーションに失敗しました',
        ],
    ],
    'egg' => [
        'title' => 'Egg',
        'no_eggs' => 'Egg がありません',
        'background_install_started' => 'Egg のインストールを開始しました',
        'background_install_description' => ':count 個の Egg のインストールをキューに追加しました。バックグラウンドで続行されます。',
        'exceptions' => [
            'failed_to_update' => 'Egg インデックスの更新に失敗しました',
            'no_eggs' => '現在インストール可能な Egg がありません。',
            'installation_failed' => '選択した Egg のインストールに失敗しました。インストール後に Egg リストからインポートしてください。',
        ],
    ],
    'session' => [
        'title' => 'セッション',
        'driver' => 'セッションドライバ',
        'driver_help' => 'セッション保存に使用するドライバ。「ファイルシステム」または「データベース」を推奨します。',
    ],
    'cache' => [
        'title' => 'キャッシュ',
        'driver' => 'キャッシュドライバ',
        'driver_help' => 'キャッシュに使用するドライバ。「ファイルシステム」を推奨します。',
        'fields' => [
            'host' => 'Redis ホスト',
            'host_help' => 'Redis サーバーのホスト。アクセス可能であることを確認してください。',
            'port' => 'Redis ポート',
            'port_help' => 'Redis サーバーのポート。',
            'username' => 'Redis ユーザー名',
            'username_help' => 'Redis ユーザーの名前。空白でも可。',
            'password' => 'Redis パスワード',
            'password_help' => 'Redis ユーザーのパスワード。空白でも可。',
        ],
        'exception' => 'Redis 接続に失敗しました',
    ],
    'queue' => [
        'title' => 'キュー',
        'driver' => 'キュードライバ',
        'driver_help' => 'キュー処理に使用するドライバ。「データベース」を推奨します。',
        'fields' => [
            'done' => '以下の両方の手順を完了しました。',
            'done_validation' => '続行する前に両方の手順を完了する必要があります！',
            'crontab' => 'crontab を設定するには次のコマンドを実行してください。なお、<code>www-data</code> は Web サーバーのユーザーです。システムによってユーザー名が異なる場合があります！',
            'service' => 'キューワーカーサービスをセットアップするには、次のコマンドを実行するだけです。',
        ],
    ],
    'exceptions' => [
        'write_env' => '.env ファイルに書き込めませんでした',
        'migration' => 'マイグレーションを実行できませんでした',
        'create_user' => '管理者ユーザーを作成できませんでした',
    ],
    'finish' => '完了',
];
