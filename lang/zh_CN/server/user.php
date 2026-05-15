<?php

return [
    'title' => '用户',
    'username' => '用户名',
    'email' => '电子邮件',
    'assign_all' => '分配全部',
    'invite_user' => '邀请用户',
    'action' => '邀请',
    'remove' => '移除用户',
    'edit' => '编辑用户',
    'editing' => '正在编辑 :user',
    'delete' => '删除用户',
    'notification_add' => '用户已邀请！',
    'notification_edit' => '用户已更新！',
    'notification_delete' => '用户已删除！',
    'notification_failed' => '邀请用户失败！',
    'permissions' => [
        'title' => '权限',

        'activity_title' => '活动',
        'activity_desc' => '控制用户访问服务器活动日志的权限。',

        'startup_title' => '启动',
        'startup_desc' => '控制用户查看此服务器启动参数的能力的权限。',

        'settings_title' => '设置',
        'settings_desc' => '控制用户修改此服务器设置的能力的权限。',

        'control_title' => '控制',
        'control_desc' => '控制用户控制服务器电源状态或发送命令的能力的权限。',

        'user_title' => '用户',
        'user_desc' => '允许用户管理服务器上其他子用户的权限。他们永远无法编辑自己的帐户，也无法分配他们自己没有的权限。',

        'file_title' => '文件',
        'file_desc' => '控制用户修改此服务器文件系统的能力的权限。',

        'allocation_title' => '分配',
        'allocation_desc' => '控制用户修改此服务器的端口分配的能力的权限。',

        'database_title' => '数据库',
        'database_desc' => '控制用户访问此服务器的数据库管理的权限。',

        'backup_title' => '备份',
        'backup_desc' => '控制用户生成和管理服务器备份的能力的权限。',

        'schedule_title' => '计划任务',
        'schedule_desc' => '控制用户访问此服务器的计划任务管理的权限。',

        'startup_read' => '允许用户查看服务器的启动变量。',
        'startup_update' => '允许用户修改服务器的启动变量。',
        'startup_docker_image' => '允许用户修改运行服务器时使用的 Docker 镜像。',

        'settings_rename' => '允许用户重命名此服务器。',
        'settings_description' => '允许用户更改此服务器的描述。',
        'settings_reinstall' => '允许用户触发此服务器的重新安装。',
        'settings_change_icon' => '允许用户更改此服务器的图标。',

        'activity_read' => '允许用户查看服务器的活动日志。',

        'websocket_connect' => '允许用户访问此服务器的 websocket。',

        'control_console' => '允许用户将数据发送到服务器控制台。',
        'control_start' => '允许用户启动服务器实例。',
        'control_stop' => '允许用户停止服务器实例。',
        'control_restart' => '允许用户重启服务器实例。',
        'control_kill' => '允许用户强制停止服务器实例。',

        'user_create' => '允许用户为服务器创建新用户帐户。',
        'user_read' => '允许用户查看与此服务器关联的用户。',
        'user_update' => '允许用户修改与此服务器关联的其他用户。',
        'user_delete' => '允许用户删除与此服务器关联的其他用户。',

        'file_create' => '允许用户创建新文件和目录。',
        'file_read' => '允许用户查看目录的内容，但不能查看文件内容或下载文件。',
        'file_read_content' => '允许用户查看给定文件的内容。这也将允许用户下载文件。',
        'file_update' => '允许用户更新与服务器关联的文件和文件夹。',
        'file_delete' => '允许用户删除文件和目录。',
        'file_archive' => '允许用户创建文件归档和解压缩现有归档。',
        'file_sftp' => '允许用户使用 SFTP 客户端执行上述文件操作。',

        'allocation_read' => '允许用户查看当前分配给此服务器的所有分配。对该服务器具有任何级别访问权限的用户始终可以查看主要分配。',
        'allocation_update' => '允许用户更改主要服务器分配并将备注附加到每个分配。',
        'allocation_delete' => '允许用户从服务器中删除分配。',
        'allocation_create' => '允许用户为服务器分配其他分配。',

        'database_create' => '允许用户为服务器创建新数据库。',
        'database_read' => '允许用户查看服务器数据库。',
        'database_update' => '允许用户对数据库进行修改。如果用户没有“查看密码”权限，他们将无法修改密码。',
        'database_delete' => '允许用户删除数据库实例。',
        'database_view_password' => '允许用户在系统中查看数据库密码。',

        'schedule_create' => '允许用户为服务器创建新计划任务。',
        'schedule_read' => '允许用户查看服务器的计划任务。',
        'schedule_update' => '允许用户对现有服务器计划任务进行修改。',
        'schedule_delete' => '允许用户删除服务器的计划任务。',

        'backup_create' => '允许用户为此服务器创建新备份。',
        'backup_read' => '允许用户查看此服务器存在的所有备份。',
        'backup_delete' => '允许用户从系统中删除备份。',
        'backup_download' => '允许用户下载服务器的备份。危险：这允许用户访问备份中服务器的所有文件。',
        'backup_restore' => '允许用户恢复服务器的备份。危险：这允许用户在此过程中删除所有服务器文件。',
        'mount_desc' => '控制用户管理此服务器挂载的能力的权限。',
        'mount_read' => '允许用户查看挂载页面并查看可用的挂载。',
        'mount_update' => '允许用户为服务器开启或关闭挂载。',
    ],
];
