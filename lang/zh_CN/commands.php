<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => '提供从此 Panel 导出的 Egg 应该使用的电子邮件地址。这应该是一个有效的电子邮件地址。',
            'url' => '应用程序 URL 必须以 https:// 或 http:// 开头，具体取决于您是否使用 SSL。如果您不包含该协议 (scheme)，您的电子邮件和其他内容将链接到错误的位置。',
            'timezone' => "时区应该匹配 PHP 支持的时区之一。如果您不确定，请参考 https://php.net/manual/en/timezones.php。",
        ],
        'redis' => [
            'note' => '您已经为一个或多个选项选择了 Redis 驱动程序，请在下面提供有效的连接信息。在大多数情况下，您可以使用提供的默认值，除非您修改了您的设置。',
            'comment' => '默认情况下，Redis 服务器实例的用户名是 default 并且没有密码，因为它在本地运行且外部无法访问。如果情况确实如此，只需按 Enter 键，无需输入值。',
            'confirm' => '似乎已经为 Redis 定义了 :field，您想要更改它吗？',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => '强烈建议不要使用 "localhost" 作为您的数据库主机，因为我们经常看到套接字连接问题。如果您想使用本地连接，您应该使用 "127.0.0.1"。',
        'DB_USERNAME_note' => "使用 root 帐户进行 MySQL 连接不仅极不推荐，而且此应用程序也不允许。您需要为该软件创建一个 MySQL 用户。",
        'DB_PASSWORD_note' => '您似乎已经定义了 MySQL 连接密码，您想要更改它吗？',
        'DB_error_2' => '您的连接凭据未保存。在继续之前，您需要提供有效的连接信息。',
        'go_back' => '返回并重试',
    ],
    'make_node' => [
        'name' => '输入一个短标识符，用于将此节点与其他节点区分开来',
        'description' => '输入用于标识该节点的描述',
        'scheme' => '请输入 https 用于 SSL 或 http 用于非 SSL 连接',
        'fqdn' => '输入用于连接守护进程 (daemon) 的域名 (例如 node.example.com)。仅当您未在此节点上使用 SSL 时才可以使用 IP 地址',
        'public' => '这个节点应该是公开的吗？注意：将节点设置为私有将使您无法自动部署到该节点。',
        'behind_proxy' => '您的 FQDN 是否在代理后面？',
        'maintenance_mode' => '是否应该启用维护模式？',
        'memory' => '输入最大内存量',
        'memory_overallocate' => '输入允许超额分配的内存量，-1 将禁用检查，0 将阻止创建新服务器',
        'disk' => '输入最大磁盘空间',
        'disk_overallocate' => '输入允许超额分配的磁盘量，-1 将禁用检查，0 将阻止创建新服务器',
        'cpu' => '输入最大 CPU 量',
        'cpu_overallocate' => '输入允许超额分配的 CPU 量，-1 将禁用检查，0 将阻止创建新服务器',
        'upload_size' => '输入最大文件上传大小',
        'daemonListen' => '输入守护进程监听端口',
        'daemonConnect' => '输入守护进程连接端口 (可以与监听端口相同)',
        'daemonSFTP' => '输入守护进程 SFTP 监听端口',
        'daemonSFTPAlias' => '输入守护进程 SFTP 别名 (可以为空)',
        'daemonBase' => '输入基础文件夹',
        'success' => '成功创建了名称为 :name 且 ID 为 :id 的新节点',
    ],
    'node_config' => [
        'error_not_exist' => '所选节点不存在。',
        'error_invalid_format' => '指定的格式无效。有效选项为 yaml 和 json。',
    ],
    'key_generate' => [
        'error_already_exist' => '您似乎已经配置了应用程序加密密钥。继续此过程将覆盖该密钥，并导致任何现有加密数据损坏。除非您知道自己在做什么，否则请勿继续。',
        'understand' => '我了解执行此命令的后果，并承担丢失加密数据的所有责任。',
        'continue' => '您确定要继续吗？更改应用程序加密密钥将导致数据丢失。',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => '没有需要运行的服务器计划任务。',
            'error_message' => '处理计划任务时遇到错误: :schedules',
        ],
    ],
];
