<?php

return [
    'title' => 'ヘルス',
    'results_refreshed' => 'ヘルスチェック結果を更新しました',
    'checked' => ':time に結果を確認しました',
    'refresh' => '更新',
    'results' => [
        'cache' => [
            'label' => 'キャッシュ',
            'ok' => '正常',
            'failed_retrieve' => 'アプリケーションキャッシュの値を設定または取得できませんでした。',
            'failed' => 'アプリケーションキャッシュで例外が発生しました: :error',
        ],
        'database' => [
            'label' => 'データベース',
            'ok' => '正常',
            'failed' => 'データベースに接続できませんでした: :error',
        ],
        'debugmode' => [
            'label' => 'デバッグモード',
            'ok' => 'デバッグモードは無効です',
            'failed' => 'デバッグモードは :expected であることが期待しましたが、実際は :actual でした',
        ],
        'environment' => [
            'label' => '環境',
            'ok' => '正常, :actual に設定',
            'failed' => '環境は :actual に設定されていますが、期待値は :expected です',
        ],
        'nodeversions' => [
            'label' => 'ノードバージョン',
            'ok' => 'ノードは最新です',
            'failed' => ':outdated / :all のノードが古いです',
            'no_nodes_created' => 'ノードが作成されていません',
            'no_nodes' => 'ノードがありません',
            'all_up_to_date' => 'すべて最新です',
            'outdated' => ':outdated / :all 古い',
        ],
        'panelversion' => [
            'label' => 'パネルバージョン',
            'ok' => 'パネルは最新です',
            'failed' => 'インストールされているバージョンは :currentVersion ですが、最新は :latestVersion です',
            'up_to_date' => '最新',
            'outdated' => '古い',
        ],
        'schedule' => [
            'label' => 'スケジュール',
            'ok' => '正常',
            'failed_last_ran' => 'スケジュールの最終実行が :time 分以上前です',
            'failed_not_ran' => 'スケジュールはまだ実行されていません',
        ],
        'useddiskspace' => [
            'label' => 'ディスク容量',
        ],
    ],
    'checks' => [
        'successful' => '成功',
        'failed' => '失敗',
    ],
];
