<?php

return [
    'title' => '運行狀況',
    'results_refreshed' => '運行狀況檢查結果已更新',
    'checked' => '已檢查 :time 的結果',
    'refresh' => '重新整理',
    'results' => [
        'cache' => [
            'label' => '快取 (Cache)',
            'ok' => '正常',
            'failed_retrieve' => '無法設定或擷取應用程式快取值。',
            'failed' => '應用程式快取發生異常：:error',
        ],
        'database' => [
            'label' => '資料庫',
            'ok' => '正常',
            'failed' => '無法連線到資料庫：:error',
        ],
        'debugmode' => [
            'label' => '偵錯模式',
            'ok' => '偵錯模式已停用',
            'failed' => '預期偵錯模式為 :expected，但實際上為 :actual',
        ],
        'environment' => [
            'label' => '環境',
            'ok' => '正常，已設定為 :actual',
            'failed' => '環境設定為 :actual ，預期為 :expected',
        ],
        'nodeversions' => [
            'label' => '節點版本',
            'ok' => '節點已是最新',
            'failed' => ':outdated/:all 個節點已過時',
            'no_nodes_created' => '未建立節點',
            'no_nodes' => '無節點',
            'all_up_to_date' => '全部最新',
            'outdated' => ':outdated/:all 已過時',
        ],
        'panelversion' => [
            'label' => 'Panel 版本',
            'ok' => 'Panel 已是最新',
            'failed' => '安裝的版本是 :currentVersion 但最新版本是 :latestVersion',
            'up_to_date' => '已是最新',
            'outdated' => '已過時',
        ],
        'schedule' => [
            'label' => '排程任務',
            'ok' => '正常',
            'failed_last_ran' => '排程任務上次執行是在 :time 分鐘之前',
            'failed_not_ran' => '排程任務尚未執行。',
        ],
        'useddiskspace' => [
            'label' => '磁碟空間',
        ],
    ],
    'checks' => [
        'successful' => '成功',
        'failed' => '失敗：:checks',
    ],
];
