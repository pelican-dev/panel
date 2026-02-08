<?php

return [
    'title' => '文件',
    'name' => '名称',
    'size' => '大小',
    'modified_at' => '修改于',
    'actions' => [
        'open' => '打开',
        'download' => '下载',
        'copy' => [
            'title' => '复制',
            'notification' => '文件复制成功',
        ],
        'upload' => [
            'title' => '上传',
            'from_files' => '上传文件',
            'from_url' => '从网址上传',
            'url' => 'URL',
            'drop_files' => '拖拽文件并上传',
            'success' => '文件上传成功',
            'failed' => '文件上传失败',
            'header' => '上传文件中',
            'error' => '上传过程中发生错误',
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
            'new_location_hint' => '输入此文件或文件夹相对于当前目录的位置。',
            'notification' => '文件已移动',
            'bulk_notification' => ':count 文件已移动到 :directory',
        ],
        'permissions' => [
            'title' => '权限',
            'read' => '读取',
            'write' => '写入',
            'execute' => '执行',
            'owner' => '所有者',
            'group' => '组',
            'public' => '公开',
            'notification' => '权限更改为 :mode',
        ],
        'archive' => [
            'title' => '存档',
            'archive_name' => '存档名称',
            'notification' => '存档已创建',
            'extension' => '扩展',
        ],
        'unarchive' => [
            'title' => '取消存档',
            'notification' => '取消存档完成',
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
            'search_term' => '搜索关键词',
            'search_term_placeholder' => '输入搜索关键词，例如 *.txt',
            'search' => '搜索',
            'search_for_term' => '搜索 :term',
        ],
        'delete' => [
            'notification' => '文件已删除',
            'bulk_notification' => ':count 文件已删除',
        ],
        'edit' => [
            'title' => '编辑: :file',
            'save_close' => '保存并关闭',
            'save' => '保存',
            'cancel' => '取消',
            'notification' => '文件已保存',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> 太大了！',
            'body' => '最大值 :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> 未找到 ！',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> 是一个目录',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> 已经存在！',
        ],
        'files_node_error' => [
            'title' => '无法加载文件！',
        ],
        'pelicanignore' => [
            'title' => '您正在编辑 <code>.pelicanover</code> 文件！',
            'body' => '此处列出的任何文件或目录将被排除在备份之外。通配符将被星号支持(<code>*</code>)。<br>您可以通过预置一个采集点来否定先前的规则 (<code>！</code>)。',
        ],
    ],
];
