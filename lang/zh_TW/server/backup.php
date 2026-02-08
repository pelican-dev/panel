<?php

return [
    'title' => '備份',
    'empty' => '沒有備份',
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
            'limit' => '已達備份上限',
            'created' => '已建立 :name',
            'notification_success' => '備份建立成功',
            'notification_fail' => '備份建立失敗',
            'name' => '名稱',
            'ignored' => '已忽略的檔案與目錄',
            'locked' => '是否鎖定？',
            'lock_helper' => '在手動解除鎖定前，將防止此備份被刪除。',
        ],
        'lock' => [
            'lock' => '鎖定',
            'unlock' => '解除鎖定',
        ],
        'download' => '下載',
        'rename' => [
            'title' => '重新命名',
            'new_name' => '備份名稱',
            'notification_success' => '備份重新命名成功',
        ],
        'restore' => [
            'title' => '還原',
            'helper' => '伺服器將會停止，在此流程完成之前，你將無法控制電源狀態、存取檔案管理器或建立其他備份。',
            'delete_all' => '在還原備份前要刪除所有檔案嗎？',
            'notification_started' => '正在還原備份',
            'notification_success' => '備份還原成功',
            'notification_fail' => '備份還原失敗',
            'notification_fail_body_1' => '此伺服器目前的狀態不允許還原備份。',
            'notification_fail_body_2' => '目前無法還原此備份：尚未完成或已失敗。',
        ],
        'delete' => [
            'title' => '刪除備份',
            'description' => '確定要刪除 :backup 嗎？',
            'notification_success' => '備份已刪除',
            'notification_fail' => '無法刪除備份',
            'notification_fail_body' => '無法連線到節點，請再試一次。',
        ],
    ],
];
