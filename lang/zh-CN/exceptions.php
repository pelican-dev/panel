<?php

return [
    'daemon_connection_failed' => '尝试与守护程序通信时出现异常，导致 HTTP/:code 响应代码。已记录此异常。',
    'node' => [
        'servers_attached' => '一个节点必须没有关联任何服务器时才能被删除。',
        'error_connecting' => '连接到 :node 时出错',
        'daemon_off_config_updated' => '守护程序配置<strong>已更新</strong>，但是在尝试自动更新守护程序上的配置文件时遇到错误。您需要手动更新配置文件 (config.yml) 以使守护程序应用这些更改。',
    ],
    'allocations' => [
        'server_using' => '此分配正在被一台服务器使用，只有此分配未被服务器使用时才能删除此分配。',
        'too_many_ports' => '不支持一次在单个范围内添加超过 1000 个端口。',
        'invalid_mapping' => '为 :port 提供的映射无效并且无法处理。',
        'cidr_out_of_range' => 'CIDR 表示法只允许 /25 于 /32 之间的掩码。',
        'port_out_of_range' => '分配中的端口必须大于 1024 且小于或等于 65535。',
    ],
    'egg' => [
        'delete_has_servers' => '无法从面板中删除关联了服务器的预设。',
        'invalid_copy_id' => '用于复制选择用于复制脚本的预设不存在，或正在复制脚本本身。',
        'has_children' => '此预设是一个或多个其他预设的父级。请在删除此预设之前删除这些预设。',
    ],
    'variables' => [
        'env_not_unique' => '环境变量 :name 对于此预设必须是独一无二的。',
        'reserved_name' => '环境变量 :name 是受保护的，不能给分配变量。',
        'bad_validation_rule' => '验证规则 ":rule" 不是此应用程序的有效规则。',
    ],
    'importer' => [
        'json_error' => '尝试分析 JSON 文件时出现错误: :error 。',
        'file_error' => '提供的 JSON 文件无效。',
        'invalid_json_provided' => '提供的 JSON 文件是不可识别的格式。',
    ],
    'subusers' => [
        'editing_self' => '不允许您修改自己的子用户帐户。',
        'user_is_owner' => '您不能将服务器所有者添加为此服务器的子用户。',
        'subuser_exists' => '具有该电子邮箱地址的用户已被指定为该服务器的子用户。',
    ],
    'databases' => [
        'delete_has_databases' => '无法删除关联了数据库的数据库主机服务器。',
    ],
    'tasks' => [
        'chain_interval_too_long' => '链式任务的最大间隔时间是 15 分钟。',
    ],
    'locations' => [
        'has_nodes' => '无法删除关联了节点的地域。',
    ],
    'users' => [
        'is_self' => '',
        'has_servers' => '无法删除账户下有服务器的用户，请将其全部删除，然后再继续此操作。',
        'node_revocation_failed' => '无法撤销 <a href=":link">节点 #:node</a> 上的密钥。:error',
    ],
    'deployment' => [
        'no_viable_nodes' => '找不到满足自动部署要求的节点。',
        'no_viable_allocations' => '未找到满足自动部署要求的分配。',
    ],
    'api' => [
        'resource_not_found' => '请求的资源在此服务器上不存在。',
    ],
    'mount' => [
        'servers_attached' => '一个节点必须没有关联任何服务器时才能被删除。',
    ],
    'server' => [
        'marked_as_failed' => '此服务器尚未完成安装过程，请稍后再试。',
    ],
];
