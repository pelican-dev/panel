<?php

return [
    'title' => '排程',
    'new' => '新排程',
    'edit' => '編輯排程',
    'save' => '儲存排程',
    'delete' => '刪除排程',
    'import' => '匯入排程',
    'export' => '匯出排程',
    'name' => '名稱',
    'cron' => 'Cron',
    'status' => '狀態',
    'schedule_status' => [
        'inactive' => '未啟用',
        'processing' => '處理中',
        'active' => '已啟用',
    ],
    'no_tasks' => '沒有工作',
    'run_now' => '立即執行',
    'online_only' => '僅在上線時',
    'last_run' => '上次執行',
    'next_run' => '下次執行',
    'never' => '從不',
    'cancel' => '取消',

    'only_online' => '僅當伺服器在線時？',
    'only_online_hint' => '僅當伺服器處於執行狀態時才執行此排程。',
    'enabled' => '啟用排程？',
    'enabled_hint' => '啟用時，此排程將自動執行。',

    'cron_body' => '請記住，下面的 cron 輸入始終假定為 UTC。',
    'cron_timezone' => '你時區中的下次執行 (:timezone)：<b>:next_run</b>',

    'invalid' => '無效',

    'time' => [
        'minute' => '分鐘',
        'hour' => '小時',
        'day' => '天',
        'week' => '週',
        'month' => '月',
        'day_of_month' => '月的日期',
        'day_of_week' => '週的日期',

        'hourly' => '每小時',
        'daily' => '每天',
        'weekly_mon' => '週一',
        'weekly_sun' => '週日',
        'monthly' => '每月',
        'every_min' => '每 x 分鐘',
        'every_hour' => '每 x 小時',
        'every_day' => '每 x 天',
        'every_week' => '每 x 週',
        'every_month' => '每 x 月',
        'every_day_of_week' => '每 x 週的日期',

        'every' => '每',
        'minutes' => '分鐘',
        'hours' => '小時',
        'days' => '天',
        'months' => '月',

        'monday' => '星期一',
        'tuesday' => '星期二',
        'wednesday' => '星期三',
        'thursday' => '星期四',
        'friday' => '星期五',
        'saturday' => '星期六',
        'sunday' => '星期日',
    ],

    'tasks' => [
        'title' => '工作',
        'create' => '建立工作',
        'limit' => '達到工作限制',
        'action' => '動作',
        'payload' => '負載',
        'no_payload' => '無參數',
        'time_offset' => '時間偏移',
        'first_task' => '首個工作',
        'seconds' => '秒',
        'continue_on_failure' => '失敗時繼續',

        'actions' => [
            'title' => '動作',
            'power' => [
                'title' => '發送電源動作',
                'action' => '電源動作',
                'start' => '啟動',
                'stop' => '停止',
                'restart' => '重新啟動',
                'kill' => '強制停止',
            ],
            'command' => [
                'title' => '發送命令',
                'command' => '命令',
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

    'notification_invalid_cron' => '提供的 cron 資料不符合有效表達式',

    'import_action' => [
        'file' => '檔案',
        'url' => '網址',
        'schedule_help' => '應為原始 .json 檔案 (schedule-daily-restart.json)',
        'url_help' => '網址必須直接指向原始 .json 檔案',
        'add_url' => '新網址',
        'import_failed' => '匯入失敗',
        'import_success' => '匯入成功',
    ],
];
