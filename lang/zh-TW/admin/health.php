<?php

return [
    'title' => '健康狀況',
    'results_refreshed' => '健康檢查結果已更新',
    'checked' => '檢查結果來自 :time',
    'refresh' => '重新整理',
    'results' => [
        'cache' => [
            'label' => '快取',
            'ok' => '正常',
            'failed_retrieve' => '無法設定或讀取應用程式快取值。',
            'failed' => '應用程式快取發生異常：:error',
        ],
        'database' => [
            'label' => '資料庫',
            'ok' => '正常',
            'failed' => '無法連線到資料庫：:error',
        ],
        'debugmode' => [
            'label' => '除錯模式',
            'ok' => '除錯模式已停用',
            'failed' => '除錯模式預期為 :expected，但實際為 :actual',
        ],
        'environment' => [
            'label' => '環境',
            'ok' => '正常，設定為 :actual',
            'failed' => '環境設定為 :actual，預期為 :expected',
        ],
        'nodeversions' => [
            'label' => '節點版本',
            'ok' => '節點已是最新狀態',
            'failed' => ':outdated/:all 節點版本過舊',
            'no_nodes_created' => '未建立節點',
            'no_nodes' => '無節點',
            'all_up_to_date' => '全部最新',
            'outdated' => ':outdated/:all 過舊',
        ],
        'panelversion' => [
            'label' => '面板版本',
            'ok' => '面板已是最新狀態',
            'failed' => '安裝版本為 :currentVersion，但最新版本為 :latestVersion',
            'up_to_date' => '最新',
            'outdated' => '過舊',
        ],
        'schedule' => [
            'label' => '排程',
            'ok' => '正常',
            'failed_last_ran' => '排程上次執行是在 :time 分鐘前',
            'failed_not_ran' => '排程尚未執行。',
        ],
        'useddiskspace' => [
            'label' => '磁碟空間',
        ],
    ],
    'checks' => [
        'successful' => '成功',
        'failed' => '失敗 :checks',
    ],
];
