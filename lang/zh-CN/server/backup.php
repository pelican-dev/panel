<?php

return [
    'title' => '备份',
    'empty' => '无备份',
    'size' => '大小',
    'created_at' => '创建于',
    'status' => '状态',
    'is_locked' => '锁定状态',
    'backup_status' => [
        'in_progress' => '进行中',
        'successful' => '成功',
        'failed' => '失败',
    ],
    'actions' => [
        'create' => [
            'title' => '创建备份',
            'limit' => '已达备份上限',
            'created' => '成功创建 :name',
            'notification_success' => '备份已成功创建',
            'notification_fail' => '备份创建失败',
            'name' => '名称',
            'ignored' => '忽略的文件和目录',
            'locked' => '锁定？',
            'lock_helper' => '防止此备份被删除，直到被明确解锁。',
        ],
        'lock' => [
            'lock' => '锁定',
            'unlock' => '解锁',
        ],
        'download' => '下载',
        'rename' => [
            'title' => '重命名',
            'new_name' => '备份名称',
            'notification_success' => '备份重命名成功',
        ],
        'restore' => [
            'title' => '还原',
            'helper' => '您的服务器将被停止。 您将无法控制电源状态，无法访问文件管理器，也无法创建额外的备份，直到此过程完成。',
            'delete_all' => '还原备份前删除所有文件吗？',
            'notification_started' => '还原备份',
            'notification_success' => '备份成功恢复',
            'notification_fail' => '恢复备份失败',
            'notification_fail_body_1' => '此服务器目前不处于允许恢复备份的状态。',
            'notification_fail_body_2' => '此备份当前无法恢复：未完成或失败。',
        ],
        'delete' => [
            'title' => '删除备份',
            'description' => '您想要删除 :backup 吗？',
            'notification_success' => '备份已删除',
            'notification_fail' => '无法删除备份',
            'notification_fail_body' => '连接到节点失败。请重试。',
        ],
    ],
];
