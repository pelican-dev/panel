<?php

return [
    'appsettings' => [
        'redis' => [ 
            'title_field' => 'Redis 主機',
            'user_field' => 'Redis 用戶',
            'password_field' => 'Redis 密碼',
            'port_field' => 'Redis 端口',
        ],
    ],
    'console' => [
        'overview' => [
            'name' => '名稱',
            'status' => '狀態',
            'address' => '地址',
            'cpu' => 'CPU',
            'memory' => '記憶體',
            'disk' => '磁碟',
            'diskusage' => [
                'unavailable' => '不可用',
            ],
        ],
        'settings' => [
            'basic' => [
                'heading' => '伺服器資訊',
                'title' => '資訊',
                'server_name' => '伺服器名稱',
                'server_descriptions' => '伺服器描述',
                'server_uuid' => '伺服器 UUID',
                'server_id' => '伺服器 ID',
                'limits' => [
                    'title' => '限制',
                    'cpu_prefix' => 'CPU',
                    'memory_prefix' => '記憶體',
                    'disk_prefix' => '磁碟空間',
                    'backups_prefix' => '備份',
                    'databases_prefix' => '資料庫',
                ],
            ],
            'node' => [
                'heading' => '節點資訊',
                'node_name' => '節點名稱',
                'sftp_header' => 'SFTP 資訊',
                'sftp_connection' => '連接',
                'sftp_calltoaction' => '連接至 SFTP',
                'sftp_username' => '用戶名',
                'sftp_password' => '密碼',
            ],
            'reinstall' => [
                'header' => '重新安裝伺服器',
                'button' => '重新安裝',
                'action_heading' => '您確定要重新安裝伺服器嗎？',
                'action_desc' => '此過程可能會刪除或修改部分文件，請在繼續之前備份您的資料。',
                'action_confirm' => '是的，重新安裝',
                'started' => '伺服器重新安裝已開始',
                'action_detail_line1' => '重新安裝伺服器將會停止伺服器，然後重新執行初始設定的安裝腳本。',
                'action_detail_line2' => '此過程可能會刪除或修改部分文件，請在繼續之前備份您的資料。',
                'failed' => '伺服器重新安裝失敗',
            ],
            'tag_unlimited' => '無限制',
            'tag_nobackups' => '無備份',
            'tag_nodatabases' => '無資料庫',
            'tag_noadditionalallocations' => '無額外分配',
        ],
    ],
    'containerstatus' => [
        'created' => '已創建',
        'starting' => '啟動中',
        'running' => '運行中',
        'restarting' => '重新啟動中',
        'exited' => '已退出',
        'paused' => '已暫停',
        'dead' => '已終止',
        'removing' => '移除中',
        'stopping' => '停止中',
        'offline' => '離線',
    ],
    'backupstatus' => [
        'InProgress' => '進行中',
        'Successful' => '成功',
        'Failed' => '失敗',        
    ],
    'consolestatus' => [
        'start' => '啟動',
        'restart' => '重啟',
        'stop' => '停止',
        'kill' =>'強制停止',
        'kill_help' => '警告：此操作可能造成資料損壞或/及永久遺失！',
    ],
    'server' => [
        'widgets' => [
            'headings' => [
                'CPU' => 'CPU',
                'Memory' => '記憶體',
                'Network1' => '網路 - ↓',
                'Network2' => ' - ↑',
            ],
            'tablecolumn' => [
                'cpu' => 'CPU',
                'timestamp' => '時間戳',
            ],
        ],
    ],
];