<?php

return [
    'appsettings' => [
        'comment' => [
            'author' => '请提供预设导出中作者所使用的有效电子邮箱地址。',
            'url' => '应用程序的URL必须根据您是否使用SSL，以https://（如果使用SSL）或http://（如果不使用SSL）开头。如果不包含协议部分，您的电子邮件和其他内容将会链接到错误的地址。',
            'timezone' => '时区应该与 PHP 支持的时区之一相匹配。如果您不确定，请参考
https://php.net/manual/zh/timezones.php。',
        ],
        'redis' => [
            'note' => '您为一个或多个选项选择了Redis驱动程序，请在下方提供有效的连接信息。在大多数情况下，除非您修改了设置，否则您可以使用提供的默认值。',
            'comment' => '默认情况下，Redis服务器实例没有密码，并且它在本地运行且外界无法访问。如果是这种情况，只需按回车键而不输入任何值。',
            'confirm' => '它似乎是一个 :field 已经定义为 Redis, 你想要更改它吗？',
        ],
    ],
    'database_settings' => [
        'DB_HOST_note' => '强烈建议不要使用“localhost”作为您的数据库主机，因为我们经常遇到socket连接问题。如果您想使用本地连接，您应该使用“127.0.0.1”。',
        'DB_USERNAME_note' => '使用MySQL的root账户进行连接是极其不推荐的做法，而且本应用程序也不允许这样做。您需要为此面板专门创建一个MySQL用户。',
        'DB_PASSWORD_note' => '似乎您已经设置了MySQL连接密码，您想更改它吗？',
        'DB_error_2' => '您的连接凭据尚未保存。在继续之前，您需要提供有效的连接信息。',
        'go_back' => '返回并重试',
    ],
    'make_node' => [
        'name' => '输入一个简短的标识符，用于将此节点与其他节点区分开来',
        'description' => '输入描述以识别该节点',
        'scheme' => '请为SSL连接输入https，或者为非SSL连接输入http',
        'fqdn' => '请输入用于连接守护程序的域名 (例如 node.example.com)。仅在您没有为此节点使用 SSL 连接的情况下才可以使用 IP 地址。',
        'public' => '是否公开此节点？请注意，如果将节点设置为私有，该节点将无法使用自动部署功能',
        'behind_proxy' => '您的 FQDN 是否在代理伺服器后运作？',
        'maintenance_mode' => '是否启用维护模式？',
        'memory' => '输入可用于新服务器的内存总量',
        'memory_overallocate' => '请输入要过度分配的内存量百分比，要禁用检查过度分配，请输入 -1 于此处，如果输入 0 这将在可能超出节点的最大内存总量时阻止创建新服务器',
        'disk' => '输入可用于新服务器的存储空间总量',
        'disk_overallocate' => '请输入要过度分配的存储空间百分比，要禁用检查过度分配，请输入 -1 于此处. 如果输入 0 这将在可能超出节点的最大存储空间总量时阻止创建新服务器',
        'cpu' => '输入可用于新服务器的内存总量',
        'cpu_overallocate' => '请输入要过度分配的存储空间百分比，要禁用检查过度分配，请输入 -1 于此处. 如果输入 0 这将在可能超出节点的最大存储空间总量时阻止创建新服务器',
        'upload_size' => '输入文件上传大小上限',
        'daemonListen' => '输入后端程序的监听端口',
        'daemonConnect' => '输入守护进程连接端口 (可以与监听端口相同)',
        'daemonSFTP' => '输入 SFTP 后端的监听端口',
        'daemonSFTPAlias' => '输入守护进程别名 (可以为空)',
        'daemonBase' => '输入根文件夹',
        'success' => '成功创建了一个名叫:name 的新节点并且具有一个 :id 的 id',
    ],
    'node_config' => [
        'error_not_exist' => '所选的节点不存在。',
        'error_invalid_format' => '无效的格式。有效的格式为 yaml 和 json',
    ],
    'key_generate' => [
        'error_already_exist' => '看来您已经配置了应用程序加密密钥。继续此过程将覆盖该密钥并损坏所有加密数据。除非您知道自己在做什么，否则请勿继续。',
        'understand' => '我了解执行此命令的后果并对加密数据的丢失承担全部责任。',
        'continue' => '您确定要继续吗？更改应用程序加密密钥将导致数据丢失。',
    ],
    'schedule' => [
        'process' => [
            'no_tasks' => '没有需要运行的服务器计划任务。',
            'error_message' => '处理以下计划任务时遇​​到错误: ',
        ],
    ],
];
