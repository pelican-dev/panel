<?php

return [
    'title' => '控制台',
    'command' => '输入命令...',
    'command_blocked' => '服务器离线...',
    'command_blocked_title' => '服务器离线时无法发送命令',
    'open_in_admin' => '在管理后台打开',
    'power_actions' => [
        'start' => '启动',
        'stop' => '停止',
        'restart' => '重启',
        'kill' => '强制停止 (Kill)',
        'kill_tooltip' => '这可能会导致数据损坏和/或数据丢失！',
    ],
    'labels' => [
        'cpu' => 'CPU',
        'memory' => '内存',
        'network' => '网络',
        'disk' => '磁盘',
        'name' => '名称',
        'status' => '状态',
        'address' => '地址',
        'unavailable' => '不可用',
    ],
    'status' => [
        'created' => '已创建',
        'starting' => '正在启动',
        'running' => '运行中',
        'restarting' => '正在重启',
        'exited' => '已退出',
        'paused' => '已暂停',
        'dead' => '已停止 (Dead)',
        'removing' => '正在移除',
        'stopping' => '正在停止',
        'offline' => '离线',
        'missing' => '丢失',
    ],
    'websocket_error' => [
        'title' => '无法连接到 websocket！',
        'body' => '查看您的浏览器控制台以获取更多详细信息。',
    ],
];
