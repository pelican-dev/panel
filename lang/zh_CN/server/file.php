<?php

return [
    'title' => '文件',
    'name' => '名称',
    'size' => '大小',
    'modified_at' => '修改时间',
    'actions' => [
        'open' => '打开',
        'download' => '下载',
        'copy' => [
            'title' => '复制',
            'notification' => '文件已复制',
        ],
        'upload' => [
            'title' => '上传',
            'from_files' => '上传文件',
            'from_url' => '从 URL 上传',
            'url' => 'URL',
            'drop_files' => '拖放文件到此处以上传',
            'success' => '文件上传成功',
            'failed' => '无法上传文件',
            'header' => '正在上传文件',
            'error' => '上传时发生错误',
        ],
        'rename' => [
            'title' => '重命名',
            'file_name' => '文件名',
            'notification' => '文件已重命名',
        ],
        'move' => [
            'title' => '移动',
            'directory' => '目录',
            'directory_hint' => '输入相对于当前目录的新目录。',
            'new_location' => '新位置',
            'new_location_hint' => '输入此文件或文件夹的位置（相对于当前目录）。',
            'notification' => '文件已移动',
            'bulk_notification' => ':count 个文件已移动到 :directory',
        ],
        'permissions' => [
            'title' => '权限',
            'read' => '读取',
            'write' => '写入',
            'execute' => '执行',
            'owner' => '所有者',
            'group' => '组',
            'public' => '公共',
            'notification' => '权限已更改为 :mode',
        ],
        'archive' => [
            'title' => '归档',
            'archive_name' => '归档名称',
            'notification' => '已创建归档',
            'extension' => '扩展名',
        ],
        'unarchive' => [
            'title' => '解档',
            'notification' => '解档已完成',
        ],
        'new_file' => [
            'title' => '新建文件',
            'file_name' => '新文件名',
            'syntax' => '语法高亮',
            'create' => '创建',
        ],
        'new_folder' => [
            'title' => '新建文件夹',
            'folder_name' => '新文件夹名称',
        ],
        'nested_search' => [
            'title' => '嵌套搜索',
            'search_term' => '搜索词',
            'search_term_placeholder' => '输入搜索词，例如 *.txt',
            'search' => '搜索',
            'search_for_term' => '搜索 :term',
        ],
        'delete' => [
            'notification' => '文件已删除',
            'bulk_notification' => ':count 个文件已删除',
        ],
        'edit' => [
            'title' => '正在编辑：:file',
            'save_close' => '保存并关闭',
            'save' => '保存',
            'cancel' => '取消',
            'notification' => '文件已保存',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> 太大了！',
            'body' => '最大值为 :max',
        ],
        'file_not_found' => [
            'title' => '找不到 <code>:name</code>！',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> 是一个目录',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> 已存在！',
        ],
        'files_node_error' => [
            'title' => '无法加载文件！',
        ],
        'pelicanignore' => [
            'title' => '您正在编辑 <code>.pelicanignore</code> 文件！',
            'body' => '此处列出的任何文件或目录都将从备份中排除。通过使用星号 (<code>*</code>) 支持通配符。<br>您可以通过在前面加上感叹号 (<code>!</code>) 来否定先前的规则。',
        ],
    ],
];
