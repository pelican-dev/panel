<?php

return [
    'user' => [
        'search_users' => '输入用户名、用户 ID 或电子邮箱地址',
        'select_search_user' => '要删除的用户ID (输入\'0\'重新搜索)',
        'deleted' => '已成功将该用户从面板中删除。',
        'confirm_delete' => '您确定要从面板中删除此用户吗？',
        'no_users_found' => '提供的搜索词未能找到相符的用户。',
        'multiple_found' => '提供的搜索词找到多个帐户，由于 --no-interaction 标签而无法删除用户。',
        'ask_admin' => '此用户是否为管理员？',
        'ask_email' => '电子邮箱地址',
        'ask_username' => '用户名',
        'ask_password' => '密码',
        'ask_password_tip' => '如果您想使用通过电子邮件发送给用户的随机密码创建一个帐户，请重新运行此命令 (CTRL+C) 并传递 `--no-password` 标签。',
        'ask_password_help' => '密码长度必须至少为 8 个字符，并且至少包含一个大写字母和数字。',
        '2fa_help_text' => '',
        '2fa_disabled' => '已为 :email 禁用动态口令认证。',
    ],
    'schedule' => [
        'output_line' => '为 `:schedule` (:hash) 中的第一个任务分配作业。',
    ],
    'maintenance' => [
        'deleting_service_backup' => '删除服务备份文件 :file。',
    ],
    'server' => [
        'rebuild_failed' => '在节点 ":node" 上对 ":name" (#:id) 的重建请求失败并出现错误：:message',
        'reinstall' => [
            'failed' => '在节点 ":node" 上重新安装 ":name" (#:id) 请求失败并出现错误: :message',
            'confirm' => '您即将针对一组服务器重新安装。你想继续吗？',
        ],
        'power' => [
            'confirm' => '您即将对 :count 服务器执行 :action。你想继续吗？',
            'action_failed' => '节点 ":node" 上 ":name" (#:id) 的电源操作请求失败并出现错误: :message',
        ],
    ],
    'environment' => [
        'mail' => [
            'ask_smtp_host' => 'SMTP 主机 (例如 smtp.gmail.com)',
            'ask_smtp_port' => 'SMTP 端口',
            'ask_smtp_username' => 'SMTP 用户名',
            'ask_smtp_password' => 'SMTP 密码',
            'ask_mailgun_domain' => 'Mailgun 域名',
            'ask_mailgun_endpoint' => 'Mailgun 节点',
            'ask_mailgun_secret' => 'Mailgun 密钥',
            'ask_mandrill_secret' => 'Mandrill 密钥',
            'ask_postmark_username' => 'Postmark API 密钥',
            'ask_driver' => '应该使用哪个驱动程序来发送电子邮件?',
            'ask_mail_from' => '发件人地址',
            'ask_mail_name' => '发件人',
            'ask_encryption' => '加密方式',
        ],
    ],
];
