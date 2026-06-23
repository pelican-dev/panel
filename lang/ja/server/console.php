<?php

return [
    'title' => 'コンソール',
    'command' => 'コマンドを入力',
    'command_blocked' => 'オフライン',
    'command_blocked_title' => 'サーバーがオフラインなのでコマンドを送信できません',
    'open_in_admin' => '管理画面で開く',
    'power_actions' => [
        'start' => '起動',
        'stop' => '停止',
        'restart' => '再起動',
        'kill' => '強制終了',
        'kill_tooltip' => 'この操作はデータを破損させる可能性があります！',
    ],
    'labels' => [
        'cpu' => 'CPU',
        'memory' => 'メモリ',
        'network' => 'ネット',
        'disk' => 'ディスク',
        'name' => '名前',
        'status' => '状態',
        'address' => 'アドレス',
        'unavailable' => '利用不可',
    ],
    'status' => [
        'created' => '作成済み',
        'starting' => '起動準備',
        'running' => '起動中',
        'restarting' => '再起動中',
        'exited' => '終了済み',
        'paused' => '一時停止',
        'dead' => '強制終了',
        'removing' => '削除中',
        'stopping' => '停止中',
        'offline' => 'オフライン',
        'missing' => '見つかりません',
    ],
    'websocket_error' => [
        'title' => 'WebSocketに接続できません！',
        'body' => 'ブラウザのコンソールで詳細を確認してください',
    ],
];
