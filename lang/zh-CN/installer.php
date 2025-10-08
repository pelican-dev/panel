<?php

return [
    'title' => '面板安装器',
    'requirements' => [
        'title' => '服务器要求',
        'sections' => [
            'version' => [
                'title' => 'PHP 版本',
                'or_newer' => ':version 或更新的',
                'content' => '您的 PHP 版本是 :version。',
            ],
            'extensions' => [
                'title' => 'PHP 扩展',
                'good' => '已安装所有必需的 PHP 扩展。',
                'bad' => '缺少以下PHP扩展: :extension',
            ],
            'permissions' => [
                'title' => '目录权限',
                'good' => '所有文件夹拥有正确的权限。',
                'bad' => '下列文件夹权限错误: :folds',
            ],
        ],
        'exception' => '缺少一些要求',
    ],
    'environment' => [
        'title' => '环境',
        'fields' => [
            'app_name' => '网站名称',
            'app_name_help' => '这是您的面板的名称。',
            'app_url' => '应用 URL',
            'app_url_help' => '这将是您访问面板的URL。',
            'account' => [
                'section' => '管理用户',
                'email' => '电子邮箱',
                'username' => '用户名',
                'password' => '密码',
            ],
        ],
    ],
    'database' => [
        'title' => '数据库',
        'driver' => '数据库驱动程序',
        'driver_help' => '用于面板数据库的驱动程序。我们推荐"SQLite"。',
        'fields' => [
            'host' => '数据库主机',
            'host_help' => '您的数据库主机。请确保它可以访问。',
            'port' => '数据库端口',
            'port_help' => '您的数据库端口。',
            'path' => '数据库名称',
            'path_help' => '您的 .sqlite 文件相对于数据库文件夹的路径。',
            'name' => '数据库名称',
            'name_help' => '面板数据库名称。',
            'username' => '数据库用户名',
            'username_help' => '您的数据库用户名。',
            'password' => '数据库密码',
            'password_help' => '您的数据库用户的密码。可以为空。',
        ],
        'exceptions' => [
            'connection' => '数据库连接失败',
            'migration' => '迁移失败',
        ],
    ],
    'session' => [
        'title' => '会话',
        'driver' => '会话驱动程序',
        'driver_help' => '用于存储会话的驱动程序。我们推荐"文件系统"或"数据库"。',
    ],
    'cache' => [
        'title' => '缓存',
        'driver' => '缓存驱动程序',
        'driver_help' => '用于缓存的驱动程序。我们推荐"文件系统"。',
        'fields' => [
            'host' => 'Redis 主机',
            'host_help' => '您的redis服务器的主机。请确保可以访问。',
            'port' => 'Redis 端口',
            'port_help' => '您的redis服务器端口。',
            'username' => 'Redis 用户名',
            'username_help' => '您的redis用户名。可以为空',
            'password' => 'Redis 密码',
            'password_help' => '您的redis用户的密码。可以为空。',
        ],
        'exception' => 'Redis 连接失败',
    ],
    'queue' => [
        'title' => '队列',
        'driver' => '队列驱动程序',
        'driver_help' => '用于处理队列的驱动程序。我们推荐"数据库"。',
        'fields' => [
            'done' => '我已经采取了以下两项步骤。',
            'done_validation' => '您需要先完成这两个步骤才能继续！',
            'crontab' => '运行以下命令来设置您的 crontab。请注意， <code>www-data</code> 是您的 webserver 用户。 在某些系统上，用户名可能不同！',
            'service' => '要设置队列工人服务，您只需运行以下命令。',
        ],
    ],
    'exceptions' => [
        'write_env' => '无法写入 .env 文件',
        'migration' => '无法运行迁移',
        'create_user' => '无法创建管理员用户',
    ],
    'next_step' => '下一步',
    'finish' => '完成',
];
