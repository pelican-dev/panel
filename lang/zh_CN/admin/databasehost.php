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
        'name_helper' => '留空将自动生成随机名称',
        'username' => '用户名',
        'password' => '密码',
        'remote' => '连接来自',
        'remote_helper' => '允许连接的地方。留空以允许任何地方的连接。',
        'max_connections' => '最大连接数',
        'created_at' => '创建于',
        'connection_string' => 'JDBC 连接字符串',
    ],
    'error' => '连接到 :node 时出错',
    'host' => '主机',
    'host_help' => '尝试从本面板连接到此 MySQL 主机以创建新数据库时应使用的 IP 地址或域名。',
    'port' => '端口',
    'port_help' => 'MySQL 正在为此主机运行的端口。',
    'max_database' => '最大数据库',
    'max_databases_help' => '可以在此主机上创建的数据库的最大数量。 如果达到限制，无法在此主机上创建新的数据库。空白是无限的。',
    'display_name' => '显示名称',
    'display_name_help' => '应该显示给端用户的IP地址或域名。',
    'username' => '用户名',
    'username_help' => '拥有足够权限在系统上创建新用户和数据库的账户用户名。',
    'password' => '密码',
    'password_help' => '数据库用户的密码。',
    'linked_nodes' => '已连接的节点',
    'linked_nodes_help' => '此设置仅在将数据库添加到所选节点服务器时默认此数据库主机。',
    'connection_error' => '连接到主机出错',
    'no_database_hosts' => '没有数据库主机',
    'no_nodes' => '无节点',
    'delete_help' => '数据库主机有数据库',
    'unlimited' => '无限制',
    'anywhere' => '任何地方',

    'rotate' => '旋转',
    'rotate_password' => '更改您的密码',
    'rotated' => '密码已旋转',
    'rotate_error' => '密码旋转失败',
    'databases' => '数据库',

    'setup' => [
        'preparations' => '准备工作',
        'database_setup' => '数据库设定',
        'panel_setup' => '面板设定',

        'note' => '目前，数据库主机只支持 MySQL/MariaDB 数据库！',
        'different_server' => '同一服务器上的面板和数据库 <i>难道不是</i> 吗？',

        'database_user' => '数据库用户',
        'cli_login' => '使用 <code>mysql -u root -p</code> 访问mysql cli。',
        'command_create_user' => '创建用户的命令',
        'command_assign_permissions' => '分配权限的命令',
        'cli_exit' => '若要退出mysql cli 请运行 <code>exit</code>。',
        'external_access' => '外部访问',
        'allow_external_access' => '
                                    您需要允许外部访问此 MySQL 实例，以便允许服务器连接到它。</p>
                                    <br>
                                    <p>做到这一点， 打开 <code>my。 nf</code>, 因您的操作系统和如何安装 MySQL 的不同位置而异。 您可以输入 <code>/etc -iname my.cnf</code> 来定位它。</p>
                                    <br>
                                    <p>Open <code>my nf</code>, 将以下文本添加到文件底部并保存：<br>
                                    <code>[mysqld]<br>bind-address=0.0.0.0</code></p>
                                    <br>
                                    <p>重新启动MySQL/ MariaDB 以应用这些更改。 这将覆盖默认的 MySQL 配置，默认只接受本地主机的请求。 更新这将允许所有接口上的连接，从而允许外部连接。 请确保在防火墙中允许 MySQL 端口 (默认 3306)。</p>
                                ',
    ],
];
