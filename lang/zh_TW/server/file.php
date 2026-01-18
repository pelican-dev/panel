<?php

return [
    'title' => '檔案',
    'name' => '名稱',
    'size' => '大小',
    'modified_at' => '修改於',
    'actions' => [
        'open' => '開啟',
        'download' => '下載',
        'copy' => [
            'title' => '複製',
            'notification' => '檔案複製成功',
        ],
        'upload' => [
            'title' => '上傳',
            'from_files' => '上傳檔案',
            'from_url' => '通過 URL 上傳',
            'url' => '網址',
            'drop_files' => '投下要上傳的檔案',
            'success' => '成功上傳檔案',
            'failed' => '上傳檔案失敗',
            'header' => '正在上傳檔案',
            'error' => '上傳檔案時發生錯誤',
        ],
        'rename' => [
            'title' => '重新命名',
            'file_name' => '檔案名稱',
            'notification' => '已重新命名',
        ],
        'move' => [
            'title' => '移動',
            'directory' => '目錄',
            'directory_hint' => '輸入新目錄，相對於當前目錄。',
            'new_location' => '新的位置',
            'new_location_hint' => '輸入此檔案或資料夾的位置，相對於當前目錄。',
            'notification' => '檔案已移動',
            'bulk_notification' => ':count 個檔案已移至 :directory',
        ],
        'permissions' => [
            'title' => '權限',
            'read' => '讀',
            'write' => '寫',
            'execute' => '執行',
            'owner' => '擁有者',
            'group' => '群組',
            'public' => '公開',
            'notification' => '權限已變更為 :mode',
        ],
        'archive' => [
            'title' => '壓縮檔',
            'archive_name' => '壓縮檔名稱',
            'notification' => '已建立壓縮檔',
            'extension' => '副檔名',
        ],
        'unarchive' => [
            'title' => '解壓縮',
            'notification' => '解除封存完成',
        ],
        'new_file' => [
            'title' => '新檔案',
            'file_name' => '新檔案名稱',
            'syntax' => '語法高亮',
            'create' => '建立',
        ],
        'new_folder' => [
            'title' => '新資料夾',
            'folder_name' => '新資料夾名稱',
        ],
        'nested_search' => [
            'title' => '深度搜尋',
            'search_term' => '搜尋詞',
            'search_term_placeholder' => '輸入搜尋詞，例如：*.txt',
            'search' => '搜尋',
            'search_for_term' => '搜尋 :term',
        ],
        'delete' => [
            'notification' => '檔案已刪除',
            'bulk_notification' => ':count 個檔案已刪除',
        ],
        'edit' => [
            'title' => '編輯：:file',
            'save_close' => '儲存並關閉',
            'save' => '儲存',
            'cancel' => '取消',
            'notification' => '檔案已儲存',
        ],
    ],
    'alerts' => [
        'file_too_large' => [
            'title' => '<code>:name</code> 過大！',
            'body' => '最大為 :max',
        ],
        'file_not_found' => [
            'title' => '<code>:name</code> 未找到！',
        ],
        'file_not_editable' => [
            'title' => '<code>:name</code> 是一個目錄',
        ],
        'file_already_exists' => [
            'title' => '<code>:name</code> 已存在！',
        ],
        'files_node_error' => [
            'title' => '無法載入檔案！',
        ],
        'pelicanignore' => [
            'title' => '你正在編輯 <code>.pelicanignore</code> 檔案！',
            'body' => '此處列出的任何檔案或目錄都將從備份中排除。支援使用星號 (<code>*</code>) 作為通配符。<br>你可以在規則前加上驚嘆號 (<code>!</code>) 來否定之前的規則。',
        ],
    ],
];
