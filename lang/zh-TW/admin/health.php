<?php

return [
    'title' => '健康狀態',
    'results_refreshed' => '健康狀態已更新',
    'checked' => '檢查時間：:time',
    'refresh' => '重新整理',
    'results' => [
        'cache' => [
            'label' => '快取',
            'ok' => '確定',
            'failed_retrieve' => '無法設定或讀取應用程式快取值。',
            'failed' => '應用程式快取發生錯誤：:error',
        ],
        'database' => [
            'label' => '資料庫',
            'ok' => '確定',
            'failed' => '無法連接到資料庫：:error',
        ],
        'debugmode' => [
            'label' => '除錯模式',
            'ok' => '除錯模式已停用',
            'failed' => '除錯模式預期為 :expected，但實際為 :actual',
        ],
        'environment' => [
            'label' => '環境',
            'ok' => '已成功設為 :actual',
            'failed' => '系統環境設定為 :actual，但預期為 :expected',
        ],
        'nodeversions' => [
            'label' => '節點版本',
            'ok' => '節點已是最新版本',
            'failed' => ':outdated/:all 個節點不是最新版本',
            'no_nodes_created' => '沒有建立任何節點',
            'no_nodes' => '沒有節點',
            'all_up_to_date' => '全部為最新版本',
            'outdated' => ':outdated／:all 已過時',
        ],
        'panelversion' => [
            'label' => '面板版本',
            'ok' => '面板已是最新版本',
            'failed' => '目前安裝的版本為 :currentVersion，最新版本為 :latestVersion',
            'up_to_date' => '已是最新',
            'outdated' => '已過時',
        ],
        'schedule' => [
            'label' => '排程',
            'ok' => '確定',
            'failed_last_ran' => '排程上次執行是在 :time 分鐘前',
            'failed_not_ran' => '排程尚未執行。',
        ],
        'useddiskspace' => [
            'label' => '磁碟空間',
        ],
    ],
    'checks' => [
        'successful' => '成功',
        'failed' => '失敗',
    ],
];
