<?php

return [
    'title' => '控制台',
    'command' => '輸入指令...',
    'command_blocked' => '伺服器離線...',
    'command_blocked_title' => '當伺服器離線時無法發送指令',
    'open_in_admin' => '在管理後台開啟',
    'power_actions' => [
        'start' => '啟動',
        'stop' => '停止',
        'restart' => '重新啟動',
        'kill' => '強制終止',
        'kill_tooltip' => '這可能會導致資料損壞和/或資料遺失！',
    ],
    'labels' => [
        'cpu' => 'CPU',
        'memory' => '記憶體',
        'network' => '網路',
        'disk' => '磁碟',
        'name' => '名稱',
        'status' => '狀態',
        'address' => '位址',
        'unavailable' => '無法使用',
    ],
    'status' => [
        'created' => '已建立',
        'starting' => '啟動中',
        'running' => '執行中',
        'restarting' => '重新啟動中',
        'exited' => '已退出',
        'paused' => '已暫停',
        'dead' => '已死亡',
        'removing' => '移除中',
        'stopping' => '停止中',
        'offline' => '離線',
        'missing' => '遺失',
    ],
    'websocket_error' => [
        'title' => '無法連線到 WebSocket！',
        'body' => '請查看您的瀏覽器控制台以獲取更多詳細資訊。',
    ],
];
