<?php

return [
    'title' => '运行状况',
    'results_refreshed' => '运行状况检查结果已更新',
    'checked' => '已检查 :time 的结果',
    'refresh' => '刷新',
    'results' => [
        'cache' => [
            'label' => '缓存 (Cache)',
            'ok' => '正常',
            'failed_retrieve' => '无法设置或检索应用程序缓存值。',
            'failed' => '应用程序缓存发生异常：:error',
        ],
        'database' => [
            'label' => '数据库',
            'ok' => '正常',
            'failed' => '无法连接到数据库：:error',
        ],
        'debugmode' => [
            'label' => '调试模式',
            'ok' => '调试模式已禁用',
            'failed' => '预期调试模式为 :expected，但实际上为 :actual',
        ],
        'environment' => [
            'label' => '环境',
            'ok' => '正常，已设置为 :actual',
            'failed' => '环境设置为 :actual ，预期为 :expected',
        ],
        'nodeversions' => [
            'label' => '节点版本',
            'ok' => '节点已是最新',
            'failed' => ':outdated/:all 个节点已过时',
            'no_nodes_created' => '未创建节点',
            'no_nodes' => '无节点',
            'all_up_to_date' => '全部最新',
            'outdated' => ':outdated/:all 已过时',
        ],
        'panelversion' => [
            'label' => 'Panel 版本',
            'ok' => 'Panel 已是最新',
            'failed' => '安装的版本是 :currentVersion 但最新版本是 :latestVersion',
            'up_to_date' => '已是最新',
            'outdated' => '已过时',
        ],
        'schedule' => [
            'label' => '计划任务',
            'ok' => '正常',
            'failed_last_ran' => '计划任务上次运行是在 :time 分钟之前',
            'failed_not_ran' => '计划任务尚未运行。',
        ],
        'useddiskspace' => [
            'label' => '磁盘空间',
        ],
    ],
    'checks' => [
        'successful' => '成功',
        'failed' => '失败：:checks',
    ],
];
