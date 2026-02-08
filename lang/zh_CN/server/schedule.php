<?php

return [
    'title' => '计划',
    'new' => '新计划',
    'edit' => '编辑计划',
    'save' => '保存计划',
    'delete' => '删除计划',
    'import' => '导入计划',
    'export' => '导出计划',
    'name' => '名称',
    'cron' => '自定义频率(Cron)',
    'status' => '状态',
    'schedule_status' => [
        'inactive' => '未启用',
        'processing' => '处理中',
        'active' => '已启用',
    ],
    'no_tasks' => '无任务',
    'run_now' => '现在运行',
    'online_only' => '仅在线时',
    'last_run' => '上次运行于',
    'next_run' => '下次运行于',
    'never' => '从来没有',
    'cancel' => '取消',

    'only_online' => '仅当服务器在线时？',
    'only_online_hint' => '只在服务器处于运行状态时执行此时间表。',
    'enabled' => '启用计划？',
    'enabled_hint' => '如果启用，此计划将自动执行。',

    'cron_body' => '请记住，下面的 cron 输入总是使用 UTC 。',
    'cron_timezone' => '下次运行于您的时区 (:timezon): <b> :next_run </b>',

    'invalid' => '无效',

    'time' => [
        'minute' => '分钟',
        'hour' => '小时',
        'day' => '天',
        'week' => '周',
        'month' => '月',
        'day_of_month' => '月份的日',
        'day_of_week' => '周中的日',

        'hourly' => '每小时',
        'daily' => '每天',
        'weekly_mon' => '每周(星期一)',
        'weekly_sun' => '每周(星期天)',
        'monthly' => '每月',
        'every_min' => '每 X 分钟',
        'every_hour' => '每 X 小时',
        'every_day' => '每 X 天',
        'every_week' => '每 X 周',
        'every_month' => '每 X 月',
        'every_day_of_week' => '每周 X 天',

        'every' => '每 ',
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
        'sunday' => '星期天',
    ],

    'tasks' => [
        'title' => '任务',
        'create' => '创建任务',
        'limit' => '已达到任务限制',
        'action' => '操作',
        'payload' => '负载',
        'no_payload' => '无负载',
        'time_offset' => '时间偏移',
        'first_task' => '首个任务',
        'seconds' => '秒',
        'continue_on_failure' => '在失败时继续',

        'actions' => [
            'title' => '操作',
            'power' => [
                'title' => '发送电源操作',
                'action' => '电源操作',
                'start' => '启动',
                'stop' => '停止',
                'restart' => '重新启动',
                'kill' => '结束进程',
            ],
            'command' => [
                'title' => '发送命令',
                'command' => '命令',
            ],
            'backup' => [
                'title' => '创建备份',
                'files_to_ignore' => '忽略的文件',
            ],
            'delete_files' => [
                'title' => '删除文件',
                'files_to_delete' => '要删除的文件',
            ],
        ],
    ],

    'notification_invalid_cron' => '提供的 cron 数据没有计算到一个有效表达式',

    'import_action' => [
        'file' => '文件',
        'url' => 'URL',
        'schedule_help' => '这应该是原始.json 文件(schedule-daily-restart.json )',
        'url_help' => 'URL必须直接指向原始.json 文件',
        'add_url' => '新 URL',
        'import_failed' => '导入失败',
        'import_success' => '导入成功',
    ],
];
