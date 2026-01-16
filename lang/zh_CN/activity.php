<?php

/**
 * Contains all of the translation strings for different activity log
 * events. These should be keyed by the value in front of the colon (:)
 * in the event name. If there is no colon present, they should live at
 * the top level.
 */
return [
    'auth' => [
        'fail' => '登录失败',
        'success' => '已登入',
        'password-reset' => '重设密码',
        'checkpoint' => '请求动态口令认证',
        'recovery-token' => '使用了动态口令恢复代码',
        'token' => '正确输入了动态口令',
        'ip-blocked' => '阻止不在IP白名单外对<b>:identifier</b>的请求',
        'sftp' => [
            'fail' => 'SFTP 登录失败',
        ],
    ],
    'user' => [
        'account' => [
            'username-changed' => '已将用户名从<b>:old</b>更改为<b>:new</b>',
            'email-changed' => '已将电子邮箱从<b>:old</b>更改为<b>:new</b>',
            'password-changed' => '已更改密码',
        ],
        'api-key' => [
            'create' => '创建新的 API 密钥 <b>:identifier</b>',
            'delete' => '已删除 API 密钥 <b>:identifier</b>',
        ],
        'ssh-key' => [
            'create' => '将 SSH 私钥 <b>:fingerprint</b> 添加到帐户',
            'delete' => '从帐户中删除了 SSH 私钥 :fingerprint',
        ],
        'two-factor' => [
            'create' => '启用动态口令认证',
            'delete' => '禁用动态口令认证',
        ],
    ],
    'server' => [
        'console' => [
            'command' => '在服务器上执行 "<b>:command</b>"',
        ],
        'power' => [
            'start' => '启动了服务器',
            'stop' => '停止了服务器',
            'restart' => '重启了服务器',
            'kill' => '强制停止了服务器',
        ],
        'backup' => [
            'download' => '下载了 <b>:name</b> 备份',
            'delete' => '删除了 <b>:name</b> 备份',
            'restore' => '恢复了 <b>:name</b> 备份 (已删除文件: <b>:truncate</b>)',
            'restore-complete' => '已成功恢复 <b>:name</b> 备份',
            'restore-failed' => '<b>:name</b> 备份恢复失败',
            'start' => '<b>:name</b> 开始了新的一轮备份',
            'complete' => '已将 <b>:name</b> 备份标记为完成',
            'fail' => '已将 <b>:name</b> 备份标记为失败',
            'lock' => '锁定了 <b>:name</b> 备份',
            'unlock' => '解锁了 <b>:name</b> 备份',
            'rename' => '重命名备份从 "<b>:old_name</b>" 到 "<b>:new_name</b>"',
        ],
        'database' => [
            'create' => '创建新数据库 <b>:name</b>',
            'rotate-password' => '为数据库 <b>:name</b> 轮换密码',
            'delete' => '已删除数据库 <b>:name</b>',
        ],
        'file' => [
            'compress' => '已删除 <b>:directory:files</b>|已删除 <b>A:count</b> 文件在 <b>:directory</b>',
            'read' => '查看了 <b>:file</b> 的内容',
            'copy' => '创建了 :file 的副本',
            'create-directory' => '已创建目录 <b>:directory:name</b>',
            'decompress' => '解压了 </b>:directory</b> 路径下的 </b>:files</b>',
            'delete' => '压缩的 <b>:directory:files</b>|压缩的 <b>:count</b> 文件在 <b>:directory</b>',
            'download' => '下载 <b>:file</b>',
            'pull' => '从 <b>:url</b> 下载远程文件到 <b>:directory</b> 路径下',
            'rename' => '移动/重命名 <b>:从</b> 到 <b>:to</b>|Moved/ 重命名 <b>:count</b> 文件在 <b>:directory</b>',
            'write' => '写了一些新内容到 <b>:file</b> 中',
            'upload' => '上传了一些文件',
            'uploaded' => '已上传 <b>:directory:file</b>',
        ],
        'sftp' => [
            'denied' => '由于权限原因阻止了 SFTP 访问',
            'create' => '已删除 <b>:files</b>|已删除 <b>:count</b> 文件',
            'write' => '修改了 <b>:files</b>|修改了 <b>:count</b> 文件的内容',
            'delete' => '已创建 <b>:files</b>|已创建 <b>:count</b> 新文件',
            'create-directory' => '已创建 <b>:files</b> 目录|已创建 <b>:count</b> 目录',
            'rename' => '重命名 <b>:从</b> 到 <b>:to</b>|重命名或移动 <b>:count</b> 文件',
        ],
        'allocation' => [
            'create' => '添加 <b>:allocation</b> 到服务器',
            'notes' => '将 <b>:allocation</b> 的备注从 "<b>:old</b>" 更新为 "<b>:new</b>"',
            'primary' => '将 <b>:allocation</b> 设置为服务器首选',
            'delete' => '删除了 <b>:allocation</b> 分配',
        ],
        'schedule' => [
            'create' => '创建了 <b>:name</b> 计划',
            'update' => '更新了 <b>:name</b> 计划',
            'execute' => '手动执行了 <b>:name</b> 计划',
            'delete' => '删除了 <b>:name</b> 计划',
        ],
        'task' => [
            'create' => '为 <b>:name</b> 计划创建了一个新的 "<b>:action</b>" 任务',
            'update' => '更新了 <b>:name</b> 计划的 "<b>:action</b>" 任务',
            'delete' => '删除了<b>:name</b>计划的“<b>:action</b>”任务',
        ],
        'settings' => [
            'rename' => '将服务器从 <b>:old</b> 重命名为 <b>:new</b>',
            'description' => '将服务器描述从 <b>:old</b> 更改为 <b>:new</b>',
            'reinstall' => '重装服务器',
        ],
        'startup' => [
            'edit' => '将 <b>:variable</b> 变量从 "<b>:old</b>" 更改为 "<b>:new</b>"',
            'image' => '将服务器的 Docker 映像从 <b>:old</b> 更新为 <b>:new</b>',
            'command' => '将服务器的 启动命令从 <b>:old</b> 更新为 <b>:new</b>',
        ],
        'subuser' => [
            'create' => '将 <b>:email</b> 添加为子用户',
            'update' => '更新了 <b>:email</b> 的子用户权限',
            'delete' => '将 <b>:email</b> 从子用户中删除',
        ],
        'crashed' => '服务器崩溃',
    ],
];
