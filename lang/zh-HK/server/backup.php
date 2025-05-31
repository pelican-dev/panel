<?php

return [
    'limit_reached' => '已達備份上限',
    'new_backup' => '建立備份',
    'backup_created' => '備份已建立',
    'backup_failed' => '備份失敗',
    'list' => [
        'name' => '名稱',
        'ignore' => '忽略的檔案與目錄',
        'lock' => '鎖定？',
        'lock_help' => '防止此備份被刪除，除非明確解鎖。',
        'size' => '大小',
        'created' => '建立時間',
        'status' => '狀態',
        'lock_status' => '鎖定狀態',
        'lockable' => [
            'lock' => '鎖定',
            'unlock' => '解鎖',
        ],
        'restore_help' => '您的伺服器將停止運行。在完成此過程之前，您將無法控制電源狀態、訪問檔案管理器或建立其他備份。',
        'restore_confirm' => '還原備份前刪除所有檔案？',
        'restore_failed' => [
            'cannot_restore' => '備份還原失敗',
            'cannot_restore_desc' => '此伺服器目前不允許還原備份。',
            'restore_incomplete' => '備份還原失敗',
            'restore_incomplete_desc' => '目前無法還原此備份：未完成或失敗。',
        ],
        'restoring' => '正在還原備份',
        'delete' => '刪除備份',
        'delete_confirm' => '確定刪除 :backup 嗎？',
    ],
];