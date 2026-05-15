<?php

return [
    'greeting' => '你好 :name！',

    'account_created' => [
        'body' => '您收到此电子邮件是因为在 :app 上为您创建了一个帐户。',
        'username' => '用户名：:username',
        'email' => '电子邮件：:email',
        'action' => '设置您的帐户',
    ],

    'added_to_server' => [
        'body' => '您已被添加为以下服务器的子用户，允许您对服务器进行特定控制。',
        'server_name' => '服务器名称：:name',
        'action' => '访问服务器',
    ],

    'removed_from_server' => [
        'body' => '您已从以下服务器的子用户中移除。',
        'server_name' => '服务器名称：:name',
        'action' => '访问 Panel',
    ],

    'server_installed' => [
        'body' => '您的服务器已安装完成，现在可以使用了。',
        'server_name' => '服务器名称：:name',
        'action' => '登录并开始使用',
    ],

    'backup_completed' => [
        'body_success' => '备份创建成功。',
        'body_failed' => '备份创建失败。',
        'backup_name' => '备份名称：:name',
        'server_name' => '服务器名称：:name',
        'action' => '查看备份',
    ],

    'mail_tested' => [
        'subject' => 'Panel 测试消息',
        'body' => '这是 Panel 邮件系统的测试。系统一切正常！',
    ],
];
