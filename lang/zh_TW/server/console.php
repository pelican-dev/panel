<?php

return [
    'title' => '主控台',
    'command' => '輸入指令...',
    'command_blocked' => '伺服器離線中...',
    'command_blocked_title' => '伺服器離線時無法傳送指令',
    'open_in_admin' => '在管理區中開啟',
    'power_actions' => [
        'start' => '啟動',
        'stop' => '停止',
        'restart' => '重新啟動',
        'kill' => '強制終止',
        'kill_tooltip' => '這可能會導致資料損毀及/或資料遺失！',
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
        'exited' => '已結束',
        'paused' => '已暫停',
        'dead' => '已無回應',
        'removing' => '移除中',
        'stopping' => '停止中',
        'offline' => '離線',
        'missing' => '遺失',
    ],
    'websocket_error' => [
        'title' => '無法連線至 WebSocket！',
        'body' => '請檢查您的瀏覽器主控台以取得更多詳細資訊。',
    ],
];
