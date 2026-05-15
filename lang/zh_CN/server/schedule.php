<?php

return [
    'title' => '计划任务',
    'new' => '新建计划任务',
    'edit' => '编辑计划任务',
    'save' => '保存计划任务',
    'delete' => '删除计划任务',
    'import' => '导入计划任务',
    'export' => '导出计划任务',
    'name' => '名称',
    'cron' => 'Cron',
    'status' => '状态',
    'schedule_status' => [
        'inactive' => '未激活',
        'processing' => '处理中',
        'active' => '已激活',
    ],
    'no_tasks' => '没有任务',
    'run_now' => '立即运行',
    'online_only' => '仅当在线时',
    'last_run' => '上次运行',
    'next_run' => '下次运行',
    'never' => '从不',
    'cancel' => '取消',

    'only_online' => '仅当服务器在线时？',
    'only_online_hint' => '仅当服务器处于运行状态时才执行此计划任务。',
    'enabled' => '启用计划任务？',
    'enabled_hint' => '如果启用，此计划任务将自动执行。',

    'cron_body' => '请记住，下面的 cron 输入始终假定为 UTC 时间。',
    'cron_timezone' => '您所在时区 (:timezone) 的下一次运行时间：<b> :next_run </b>',

    'invalid' => '无效',

    'time' => [
        'minute' => '分钟',
        'hour' => '小时',
        'day' => '天',
        'week' => '周',
        'month' => '月',
        'day_of_month' => '每月的第几天',
        'day_of_week' => '星期几',

        'hourly' => '每小时',
        'daily' => '每天',
        'weekly_mon' => '每周 (星期一)',
        'weekly_sun' => '每周 (星期日)',
        'monthly' => '每月',
        'every_min' => '每 x 分钟',
        'every_hour' => '每 x 小时',
        'every_day' => '每 x 天',
        'every_week' => '每 x 周',
        'every_month' => '每 x 月',
        'every_day_of_week' => '每周的星期 x',

        'every' => '每',
        'minutes' => '分钟',
        'hours' => '小时',
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
        'title' => '任务',
        'create' => '创建任务',
        'limit' => '已达到任务限制',
        'action' => '操作',
        'payload' => '有效负载 (Payload)',
        'no_payload' => '没有有效负载',
        'time_offset' => '时间偏移',
        'first_task' => '第一个任务',
        'seconds' => '秒',
        'continue_on_failure' => '失败时继续',

        'actions' => [
            'title' => '操作',
            'power' => [
                'title' => '发送电源操作',
                'action' => '电源操作',
                'start' => '启动',
                'stop' => '停止',
                'restart' => '重启',
                'kill' => '强制停止 (Kill)',
            ],
            'command' => [
                'title' => '发送命令',
                'command' => '命令',
            ],
            'backup' => [
                'title' => '创建备份',
                'files_to_ignore' => '要忽略的文件',
            ],
            'delete_files' => [
                'title' => '删除文件',
                'files_to_delete' => '要删除的文件',
            ],
        ],
    ],

    'notification_invalid_cron' => '提供的 cron 数据无法解析为有效的表达式',

    'import_action' => [
        'file' => '文件',
        'url' => 'URL',
        'schedule_help' => '这应该是原始的 .json 文件 (例如 schedule-daily-restart.json )',
        'url_help' => 'URL 必须直接指向原始的 .json 文件',
        'add_url' => '新的 URL',
        'import_failed' => '导入失败',
        'import_success' => '导入成功',
    ],
];
