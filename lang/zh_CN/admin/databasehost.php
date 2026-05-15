<?php

return [
    'nav_title' => '数据库主机',
    'model_label' => '数据库主机',
    'model_label_plural' => '数据库主机',
    'table' => [
        'database' => '数据库',
        'name' => '名称',
        'host' => '主机',
        'port' => '端口',
        'name_helper' => '留空将自动生成一个随机名称',
        'username' => '用户名',
        'password' => '密码',
        'remote' => '允许的连接来源',
        'remote_helper' => '允许连接的来源。留空以允许来自任何地方的连接。',
        'max_connections' => '最大连接数',
        'created_at' => '创建于',
        'connection_string' => 'JDBC 连接字符串',
    ],
    'error' => '连接到主机时出错',
    'host' => '主机',
    'host_help' => '从此 Panel 尝试连接到此 MySQL 主机以创建新数据库时应使用的 IP 地址或域名。',
    'port' => '端口',
    'port_help' => '此主机上 MySQL 运行的端口。',
    'max_database' => '最大数据库数量',
    'max_databases_help' => '可以在此主机上创建的数据库的最大数量。如果达到限制，则无法在此主机上创建新数据库。留空则无限制。',
    'display_name' => '显示名称',
    'display_name_help' => '应向最终用户显示的 IP 地址或域名。',
    'username' => '用户名',
    'username_help' => '具有足够权限在系统上创建新用户和数据库的帐户的用户名。',
    'password' => '密码',
    'password_help' => '该数据库用户的密码。',
    'linked_nodes' => '关联节点',
    'linked_nodes_help' => '只有在所选节点上向服务器添加数据库时，才会默认选择此数据库主机。',
    'connection_error' => '连接到数据库主机时出错',
    'no_database_hosts' => '没有数据库主机',
    'no_nodes' => '没有节点',
    'delete_help' => '数据库主机拥有数据库',
    'unlimited' => '无限制',
    'anywhere' => '任何地方',

    'rotate' => '轮换',
    'rotate_password' => '轮换密码',
    'rotated' => '密码已轮换',
    'rotate_error' => '密码轮换失败',
    'databases' => '数据库',

    'setup' => [
        'preparations' => '准备工作',
        'database_setup' => '数据库设置',
        'panel_setup' => 'Panel 设置',

        'note' => '目前，数据库主机仅支持 MySQL/MariaDB 数据库！',
        'different_server' => 'Panel 和数据库是否<i>不在</i>同一台服务器上？',

        'database_user' => '数据库用户',
        'cli_login' => '使用 <code>mysql -u root -p</code> 访问 mysql cli。',
        'command_create_user' => '创建用户的命令',
        'command_assign_permissions' => '分配权限的命令',
        'cli_exit' => '要退出 mysql cli，请运行 <code>exit</code>。',
        'external_access' => '外部访问',
        'allow_external_access' => '
                                    <p>您可能需要允许对该 MySQL 实例进行外部访问，以允许服务器连接到它。</p>
                                    <br>
                                    <p>为此，请打开 <code>my.cnf</code>，其位置因您的操作系统和 MySQL 安装方式而异。您可以键入 <code>find /etc -iname my.cnf</code> 来找到它。</p>
                                    <br>
                                    <p>打开 <code>my.cnf</code>，将下面的文本添加到文件底部并保存：<br>
                                    <code>[mysqld]<br>bind-address=0.0.0.0</code></p>
                                    <br>
                                    <p>重新启动 MySQL/MariaDB 以应用这些更改。这将覆盖默认的 MySQL 配置（默认只接受来自 localhost 的请求）。更新此设置将允许所有接口上的连接，即外部连接。确保在您的防火墙中允许 MySQL 端口（默认为 3306）。</p>
                                ',
    ],
];
