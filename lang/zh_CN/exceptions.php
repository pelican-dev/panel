<?php

return [
    'daemon_connection_failed' => '尝试与守护进程 (daemon) 通信时发生异常，导致 HTTP/:code 响应代码。此异常已记录。',
    'node' => [
        'servers_attached' => '节点必须没有链接的服务器才能被删除。',
        'error_connecting' => '连接到 :node 时出错',
        'daemon_off_config_updated' => '守护进程配置<strong>已更新</strong>，但在尝试自动更新守护进程上的配置文件时遇到错误。您需要手动更新守护进程的配置文件 (config.yml) 才能应用这些更改。',
    ],
    'allocations' => [
        'server_using' => '当前有服务器分配到了该端口 (allocation)。仅当没有服务器分配时，才能删除端口分配。',
        'too_many_ports' => '不支持一次在单个范围内添加超过 1000 个端口。',
        'invalid_mapping' => '为 :port 提供的映射无效且无法处理。',
        'cidr_out_of_range' => 'CIDR 表示法仅允许 /25 和 /32 之间的掩码。',
        'port_out_of_range' => '端口分配必须大于或等于 1024 且小于或等于 65535。',
    ],
    'egg' => [
        'delete_has_servers' => '无法从 Panel 删除附加了活动服务器的 Egg。',
        'invalid_copy_id' => '选择用于复制脚本的 Egg 不存在，或者其本身正在复制脚本。',
        'has_children' => '此 Egg 是一个或多个其他 Egg 的父级。请在删除此 Egg 之前删除那些 Egg。',
    ],
    'variables' => [
        'env_not_unique' => '环境变量 :name 必须对此 Egg 唯一。',
        'reserved_name' => '环境变量 :name 受保护，无法分配给变量。',
        'bad_validation_rule' => '验证规则 ":rule" 对此应用程序不是有效的规则。',
    ],
    'importer' => [
        'json_error' => '尝试解析 JSON 文件时出错：:error。',
        'file_error' => '提供的 JSON 文件无效。',
        'invalid_json_provided' => '提供的 JSON 文件格式无法识别。',
    ],
    'subusers' => [
        'editing_self' => '不允许编辑您自己的子用户帐户。',
        'user_is_owner' => '您不能将服务器所有者添加为此服务器的子用户。',
        'subuser_exists' => '具有该电子邮件地址的用户已被分配为此服务器的子用户。',
    ],
    'databases' => [
        'delete_has_databases' => '无法删除链接了活动数据库的数据库主机服务器。',
    ],
    'tasks' => [
        'chain_interval_too_long' => '链式任务的最大间隔时间为 15 分钟。',
    ],
    'locations' => [
        'has_nodes' => '无法删除附加了活动地理位置。',
    ],
    'users' => [
        'is_self' => '无法删除您自己的用户帐户。',
        'has_servers' => '无法删除其帐户下附加了活动服务器的用户。请在继续之前删除他们的服务器。',
        'node_revocation_failed' => '无法在 <a href=":link">节点 #:node</a> 上撤销密钥。:error',
    ],
    'deployment' => [
        'no_viable_nodes' => '找不到满足自动部署指定要求的节点。',
        'no_viable_allocations' => '找不到满足自动部署要求的端口分配 (allocations)。',
    ],
    'api' => [
        'resource_not_found' => '请求的资源不在此服务器上存在。',
    ],
    'mount' => [
        'servers_attached' => '挂载 (mount) 必须没有附加的服务器才能被删除。',
    ],
    'server' => [
        'marked_as_failed' => '此服务器尚未完成其安装过程，请稍后再试。',
    ],
];
