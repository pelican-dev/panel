<?php

return [
    'title' => '健康狀態',
    'results_refreshed' => '健康檢查結果已更新',
    'checked' => '檢查結果來自 :time',
    'refresh' => '刷新',
    'results' => [
        'cache' => [
            'label' => '快取',
            'ok' => '正常',
            'failed_retrieve' => '無法設置或檢索應用程序快取值。',
            'failed' => '應用程序快取發生異常：:error',
        ],
        'database' => [
            'label' => '資料庫',
            'ok' => '正常',
            'failed' => '無法連接到資料庫：:error',
        ],
        'debugmode' => [
            'label' => '除錯模式',
            'ok' => '除錯模式已禁用',
            'failed' => '除錯模式預期為 :expected，但實際為 :actual',
        ],
        'environment' => [
            'label' => '環境',
            'ok' => '正常，設置為 :actual',
            'failed' => '環境設置為 :actual，預期為 :expected',
        ],
        'nodeversions' => [
            'label' => '節點版本',
            'ok' => '節點是最新的',
            'failed' => ':outdated/:all 個節點已過時',
            'no_nodes_created' => '未創建節點',
            'no_nodes' => '無節點',
            'all_up_to_date' => '全部最新',
            'outdated' => ':outdated/:all 過時',
        ],
        'panelversion' => [
            'label' => '面板版本',
            'ok' => '面板是最新的',
            'failed' => '安裝版本為 :currentVersion，但最新版本為 :latestVersion',
            'up_to_date' => '最新',
            'outdated' => '過時',
        ],
        'schedule' => [
            'label' => '排程',
            'ok' => '正常',
            'failed_last_ran' => '排程上次運行時間超過 :time 分鐘前',
            'failed_not_ran' => '排程尚未運行。',
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