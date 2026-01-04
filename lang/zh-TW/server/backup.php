<?php

return [
    'title' => '備份',
    'empty' => '無備份',
    'size' => '大小',
    'created_at' => '建立時間',
    'status' => '狀態',
    'is_locked' => '鎖定狀態',
    'backup_status' => [
        'in_progress' => '進行中',
        'successful' => '成功',
        'failed' => '失敗',
    ],
    'actions' => [
        'create' => [
            'title' => '建立備份',
            'limit' => '已達備份限制',
            'created' => ':name 已建立',
            'notification_success' => '備份建立成功',
            'notification_fail' => '備份建立失敗',
            'name' => '名稱',
            'ignored' => '忽略的檔案與目錄',
            'locked' => '鎖定？',
            'lock_helper' => '防止此備份被刪除，直到明確解鎖。',
        ],
        'lock' => [
            'lock' => '鎖定',
            'unlock' => '解鎖',
        ],
        'download' => '下載',
        'rename' => [
            'title' => '重新命名',
            'new_name' => '備份名稱',
            'notification_success' => '備份重新命名成功',
        ],
        'restore' => [
            'title' => '還原',
            'helper' => '您的伺服器將會停止。在此過程完成之前，您將無法控制電源狀態、存取檔案管理器或建立額外備份。',
            'delete_all' => '在還原備份之前刪除所有檔案？',
            'notification_started' => '正在還原備份',
            'notification_success' => '備份還原成功',
            'notification_fail' => '備份還原失敗',
            'notification_fail_body_1' => '此伺服器目前處於無法還原備份的狀態。',
            'notification_fail_body_2' => '此備份目前無法還原：未完成或已失敗。',
        ],
        'delete' => [
            'title' => '刪除備份',
            'description' => '您希望刪除 :backup 嗎？',
            'notification_success' => '備份已刪除',
            'notification_fail' => '無法刪除備份',
            'notification_fail_body' => '連線到節點失敗。請再試一次。',
        ],
    ],
];
