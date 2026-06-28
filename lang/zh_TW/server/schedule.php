<?php

return [
    'title' => '排程',
    'new' => '新增排程',
    'edit' => '編輯排程',
    'save' => '儲存排程',
    'delete' => '刪除排程',
    'import' => '匯入排程',
    'export' => '匯出排程',
    'name' => '名稱',
    'cron' => 'Cron',
    'status' => '狀態',
    'schedule_status' => [
        'new' => '新增',
        'inactive' => '未啟用',
        'processing' => '處理中',
        'active' => '使用中',
    ],
    'no_tasks' => '沒有任務',
    'run_now' => '立即執行',
    'online_only' => '僅在線上時',
    'last_run' => '最後執行',
    'next_run' => '下次執行',
    'never' => '從未',
    'cancel' => '取消',

    'only_online' => '僅在伺服器上線時？',
    'only_online_hint' => '僅在伺服器處於執行狀態時執行此排程。',
    'enabled' => '啟用排程？',
    'enabled_hint' => '如果啟用，此排程將會自動執行。',

    'cron_body' => '請記住，下方的 Cron 輸入始終假設為 UTC 時間。',
    'cron_timezone' => '在您的時區 (:timezone) 中的下次執行時間：<b> :next_run </b>',

    'invalid' => '無效',

    'time' => [
        'minute' => '分',
        'hour' => '時',
        'day' => '天',
        'week' => '週',
        'month' => '月',
        'day_of_month' => '每月的天',
        'day_of_week' => '每週的天',

        'hourly' => '每小時',
        'daily' => '每天',
        'weekly_mon' => '每週（星期一）',
        'weekly_sun' => '每週（星期日）',
        'monthly' => '每月',
        'every_min' => '每 x 分鐘',
        'every_hour' => '每 x 小時',
        'every_day' => '每 x 天',
        'every_week' => '每 x 週',
        'every_month' => '每 x 個月',
        'every_day_of_week' => '每週的第 x 天',

        'every' => '每',
        'minutes' => '分鐘',
        'hours' => '小時',
        'days' => '天',
        'months' => '個月',

        'monday' => '星期一',
        'tuesday' => '星期二',
        'wednesday' => '星期三',
        'thursday' => '星期四',
        'friday' => '星期五',
        'saturday' => '星期六',
        'sunday' => '星期日',
    ],

    'tasks' => [
        'title' => '任務',
        'create' => '建立任務',
        'limit' => '已達任務限制',
        'action' => '動作',
        'payload' => '指令內容',
        'no_payload' => '沒有 Payload',
        'time_offset' => '時間偏移',
        'first_task' => '第一個任務',
        'seconds' => '秒|秒',
        'continue_on_failure' => '失敗時繼續',

        'actions' => [
            'title' => '動作',
            'power' => [
                'title' => '傳送電源操作',
                'action' => '電源操作',
                'start' => '啟動',
                'stop' => '停止',
                'restart' => '重新啟動',
                'kill' => '強制終止',
            ],
            'command' => [
                'title' => '傳送指令',
                'command' => '指令',
            ],
            'backup' => [
                'title' => '建立備份',
                'files_to_ignore' => '要忽略的檔案',
            ],
            'delete_files' => [
                'title' => '刪除檔案',
                'files_to_delete' => '要刪除的檔案',
            ],
        ],
    ],

    'notification_invalid_cron' => '提供的 Cron 資料無法解析為有效的運算式',

    'import_action' => [
        'file' => '檔案',
        'url' => '網址',
        'schedule_help' => '這應該是原始的 .json 檔案（例如 schedule-daily-restart.json）',
        'url_help' => '網址必須直接指向原始的 .json 檔案',
        'add_url' => '新增網址',
        'import_failed' => '匯入失敗',
        'import_success' => '匯入成功',
    ],
];
