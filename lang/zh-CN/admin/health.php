<?php

return [
    'title' => '健康',
    'results_refreshed' => '健康检查结果已更新',
    'checked' => '检查来自 :time 的结果',
    'refresh' => '刷新',
    'results' => [
        'cache' => [
            'label' => '缓存',
            'ok' => 'OK',
            'failed_retrieve' => '无法设置或检索应用程序缓存值。',
            'failed' => '应用程序缓存发生异常: :错误',
        ],
        'database' => [
            'label' => '数据库',
            'ok' => 'OK',
            'failed' => '无法连接到数据库: :错误',
        ],
        'debugmode' => [
            'label' => 'Debug模式',
            'ok' => '调试模式已禁用',
            'failed' => '调试模式预计是 :expected, 但实际是 :actual',
        ],
        'environment' => [
            'label' => '环境',
            'ok' => '好的，设置为 :actual',
            'failed' => '环境设置为 :actual , 预期的 :expected',
        ],
        'nodeversions' => [
            'label' => '节点版本',
            'ok' => '节点是最新的',
            'failed' => ':过时/:所有节点已经过时。',
            'no_nodes_created' => '侦测不到任何节点',
            'no_nodes' => '无节点',
            'all_up_to_date' => '所有最新的',
            'outdated' => ':过时/:所有已经过时',
        ],
        'panelversion' => [
            'label' => '面板版本',
            'ok' => '您的面板是最新的',
            'failed' => '安装的版本是 :currentversion 但最新版本是 :latestversion',
            'up_to_date' => '最新',
            'outdated' => '已过时',
        ],
        'schedule' => [
            'label' => '日程',
            'ok' => 'OK',
            'failed_last_ran' => '最后一次运行的时间安排超过:time 前',
            'failed_not_ran' => '计划尚未运行。',
        ],
        'useddiskspace' => [
            'label' => '磁盘空间',
        ],
    ],
    'checks' => [
        'successful' => '成功',
        'failed' => '失败',
    ],
];
